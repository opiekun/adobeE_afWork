<?php

declare(strict_types=1);

namespace Ecommerce121\AmastyFinder\Cron;

use Amasty\Finder\Api\FinderRepositoryInterface;
use Amasty\Finder\Model\ResourceModel\Value\Collection;
use Ecommerce121\AmastyFinder\Model\Store\Config;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Psr\Log\LoggerInterface;

class ExportPartsFinder
{
    const CSV_HEADERS = [
        'year',
        'make',
        'model',
        'chassis_code',
        'fuel',
        'engine',
        'engine_type',
        'engine_designation',
        'carb_eo',
        'fitment_notes',
        'sku'
    ];

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var FinderRepositoryInterface
     */
    protected $finderRepository;

    /**
     * @var Collection
     */
    protected $valueCollection;

    /**
     * @var WriteInterface
     */
    protected $directory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param FinderRepositoryInterface $finderRepository
     * @param FileFactory $fileFactory
     * @param Filesystem $filesystem
     * @param Collection $valueCollection
     * @param Config $config
     * @throws FileSystemException
     */
    public function __construct(
        FinderRepositoryInterface $finderRepository,
        FileFactory $fileFactory,
        Filesystem $filesystem,
        Collection $valueCollection,
        Config $config
    )
    {
        $this->fileFactory = $fileFactory;
        $this->finderRepository = $finderRepository;
        $this->valueCollection = $valueCollection;
        $this->config = $config;
        $this->directory = $filesystem->getDirectoryWrite(
            DirectoryList::MEDIA
        );
    }

    /**
     * @return void
     * @throws FileSystemException
     */
    public function execute(): void
    {
        $finderId = $this->config->getFinderId();
        if ($finderId) {
            $finder = $this->finderRepository->getById($finderId);
            if ($finder->getId()) {
                $filepath = $this->config->getFilePathAndName();
                $stream = $this->directory->openFile($filepath, 'w+');
                $stream->lock();

                $stream->writeCsv(self::CSV_HEADERS);
                $valueCollection = $this->valueCollection->joinAllFor($finder);
                foreach ($valueCollection->getItems() as $valueItems) {
                    $value = $valueItems->getData();
                    unset($value['val']);
                    unset($value['vid']);
                    unset($value['finder_id']);

                    $data = [];
                    foreach ($value as $val) {
                        $data[] = $val;
                    }
                    $stream->writeCsv($data);
                }
            }
        }
    }
}
