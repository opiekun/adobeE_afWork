<?php

declare(strict_types=1);

namespace Ecommerce121\AttributeAssetsIcon\Model\Product\Attribute\Backend;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;

class File extends AbstractBackend
{
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $file;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $fileUploaderFactory;


    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $file,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
    ) {
        $this->file = $file;
        $this->filesystem = $filesystem;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->logger = $logger;
    }

    public function afterSave($object)
    {
        $path = $this->filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(
            'catalog/product/file/'
        );
        $delete = $object->getData($this->getAttribute()->getName() . '_delete');

        if ($delete) {
            $fileName = $object->getData($this->getAttribute()->getName());
            $object->setData($this->getAttribute()->getName(), '');
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
            if ($this->file->isExists($path.$fileName))  {
                $this->file->deleteFile($path.$fileName);
            }
        }

        if (empty($_FILES)) {
            return $this;// if no image is set then nothing to do
        }

        try {
            /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
            $uploader = $this->fileUploaderFactory->create(['fileId' => 'product['.$this->getAttribute()->getName().']']);
            $uploader->setAllowedExtensions(['pdf','jpeg','png', 'jpg']);
            $uploader->setAllowRenameFiles(true);
            $result = $uploader->save($path);
            $object->setData($this->getAttribute()->getName(), $result['file']);
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
        } catch (\Exception $e) {
            if ($e->getCode() != \Magento\MediaStorage\Model\File\Uploader::TMP_NAME_EMPTY) {
                $this->logger->critical($e);
            }
        }

        return $this;
    }
}
