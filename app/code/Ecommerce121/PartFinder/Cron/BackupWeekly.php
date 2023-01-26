<?php declare(strict_types=1);

namespace Ecommerce121\PartFinder\Cron;

use Psr\Log\LoggerInterface;

/**
 * Backup part finder weekly
 */
class BackupWeekly {
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;
    /**
     * @var \Magento\Framework\Filesystem
     */
    private \Magento\Framework\Filesystem $filesystem;
    /**
     * File prefix
     */
    private const PREFIX = "partfinder_export_backup_weekly_";
    /**
     * File extension
     */
    private const EXTENSION = "csv";

    /**
     * @param LoggerInterface $logger
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(LoggerInterface $logger, \Magento\Framework\Filesystem $filesystem) {
        $this->logger = $logger;
        $this->filesystem = $filesystem;
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute() {
        $varDir = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

        $fileName = self::PREFIX . date('Y-m-d_H') . "." . self::EXTENSION;
        $result = 0;
        $output = "";
        $fullPath = $varDir->getAbsolutePath($fileName);

        exec("bin/magento partfinder:export 10 > " . $fullPath, $output,$result);

        if ($result == 0) {
            $this->logger->info('Part finder export backup weekly ran successfully ' . $fileName);
            $filenameLatest = $varDir->getAbsolutePath(self::PREFIX . "latest." . self::EXTENSION);
            @unlink($filenameLatest);
            symlink($fileName, $filenameLatest);
        } else {
            $this->logger->error('Part finder export backup weekly error code ' . $result . " output: " . $output);
        }
    }
}
