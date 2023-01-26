<?php

namespace Ecommerce121\Garage\Plugin;

use Amasty\Finder\Api\DropdownRepositoryInterface;
use Amasty\Finder\Api\FinderRepositoryInterface;
use Amasty\Finder\Model\Dropdown;
use Amasty\Finder\Model\FileValidator;
use Amasty\Finder\Model\ResourceModel\Value;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Amasty\Finder\Model\Finder as FinderModel;

class ReindexAfterSaveValue extends Value
{
    const POS_DROPDOWN_ID = 1;
    const POS_VALUE_ID = 2;
    const POS_END_LABEL_FLAG = 6;
    const PARTS_DROPDOWN_KEY = 3;

    /**
     * @var FinderRepositoryInterface
     */
    private $finderRepository;

    /**
     * @var DropdownRepositoryInterface
     */
    private $dropdownRepository;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $file;

    /**
     * @var Filesystem\Driver\File
     */
    private $fileDriver;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var FileValidator
     */
    private $fileValidator;

    /**
     * @var string
     */
    private $rootDirectory;

    const IMAGES_DIR = '/amasty/finder/images/';

    /**
     * @var Dropdown
     */
    private $dropdownIndexer;

    public function __construct(
        \Ecommerce121\Garage\Model\Indexer\Dropdown $dropdownIndexer,
        \Amasty\Finder\Api\FinderRepositoryInterface $finderRepository,
        \Amasty\Finder\Api\DropdownRepositoryInterface $dropdownRepository,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Io\File $file,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        Filesystem $fileSystem,
        UploaderFactory $uploaderFactory,
        FileValidator $fileValidator,
        $connectionName = null
    ) {
        $this->dropdownIndexer = $dropdownIndexer;
        $this->finderRepository = $finderRepository;
        $this->dropdownRepository = $dropdownRepository;
        $this->rootDirectory = $directoryList->getPath(DirectoryList::MEDIA);
        $this->file = $file;
        $this->fileDriver = $fileDriver;
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->fileValidator = $fileValidator;
        parent::__construct(
            $finderRepository,
            $dropdownRepository,
            $context,
            $directoryList,
            $file,
            $fileDriver,
            $fileSystem,
            $uploaderFactory,
            $fileValidator,
        );
    }

    /**
     * @param array $data
     * @return int
     */
    public function saveNewFinder(array $data)
    {
        $connection = $this->getConnection();

        $insertData = [];
        $parentId = 0;
        $deleteData = [];
        foreach ($data as $element => $value) {
            if (strpos($element, 'image_delete_') !== false) {
                $dropdownIdForDelete = substr($element, strripos($element, '_') + 1);
                $deleteData[$dropdownIdForDelete] = $dropdownIdForDelete;
            }

            if (substr($element, 0, self::POS_END_LABEL_FLAG) == 'label_') {
                $ids = explode('_', $element);
                $dropdownId = count($ids) == self::PARTS_DROPDOWN_KEY
                    ? $ids[self::POS_DROPDOWN_ID]
                    : substr($element, self::POS_END_LABEL_FLAG);
                $valueId = isset($ids[self::POS_VALUE_ID]) ? $ids[self::POS_VALUE_ID] : '';

                $insertData[] = [
                    'dropdown_id' => $dropdownId,
                    'name' => $value,
                    'value_id' => $valueId,
                    'delete_image' => isset($deleteData[$dropdownId]) && $dropdownId == $deleteData[$dropdownId]
                        ? $dropdownId
                        : 0
                ];
            }
        }

        foreach ($insertData as $key => $row) {
            $name[$key] = $row['name'];
            $dropdownIds[$key] = $row['dropdown_id'];
        }
        array_multisort($dropdownIds, SORT_ASC, $name, SORT_ASC, $insertData);
        $dropdown = $this->dropdownRepository->getById($insertData[0]['dropdown_id']);
        $finderId = $dropdown->getFinderId();

        $savedData = [];

        foreach ($insertData as $insertElement) {
            $insertDropdownId = isset($insertElement['dropdown_id']) ? $insertElement['dropdown_id'] : '';
            $file = isset($data['files']) ? $data['files']->get($insertDropdownId) : null;
            $resultImagePath = '';
            if (!empty($file)) {
                $result = [];
                if ($insertElement['value_id']) {
                    $result = $this->getCurrentImage($connection->select(), $insertElement) ?: '';
                } else {
                    $image = $this->getExistImage($connection, $insertElement);
                    $this->deleteImageFromDir($image);
                }

                if ($file['name']) {
                    $resultImagePath = $this->uploadImage(
                        $insertDropdownId,
                        $this->getCorrectNameFolder($insertElement['name']),
                        $file['name'],
                        $finderId
                    );
                }

                if ($insertElement['delete_image'] && isset($result['image'])) {
                    $resultImagePath = '';
                    $this->deleteImageFromDir($result['image']);
                } elseif (!$resultImagePath) {
                    $resultImagePath = isset($result['image']) ? $result['image'] : '';
                }
            }

            $connection->insertOnDuplicate($this->getTable('amasty_finder_value'), [
                'parent_id' => $parentId,
                'dropdown_id' => $insertDropdownId,
                'name' => $insertElement['name'],
                'image' => $resultImagePath
            ]);

            $select = $connection->select();
            $select->from($this->getTable('amasty_finder_value'))
                ->where('dropdown_id =?', $insertDropdownId)
                ->where('parent_id =?', $parentId)
                ->where('name =?', $insertElement['name']);
            $result = $this->getConnection()->fetchRow($select);

            $savedData[] = $result['value_id'];

            $parentId = isset($result['value_id']) ? $result['value_id'] : '';
        }

        $connection->insertOnDuplicate($this->getTable('amasty_finder_map'), [
            'value_id' => $parentId,
            'sku' => $data['sku']
        ]);

        $this->finderRepository->updateLinks();
        $this->dropdownIndexer->executeByValueIds($savedData);
        return $finderId;
    }

    /**
     * @param $connection
     * @param array $insertElement
     * @return bool|string
     */
    private function getExistImage($connection, $insertElement)
    {
        $select = $connection->select();
        $select->from($this->getTable('amasty_finder_value'))->where('name=?', $insertElement['name']);
        $value = $this->getConnection()->fetchRow($select);

        return isset($value['image']) ? $value['image'] : false;
    }

    /**
     * @param string $name
     * @return string
     */
    private function getCorrectNameFolder($name)
    {
        return str_replace('/', '', $name);
    }
}
