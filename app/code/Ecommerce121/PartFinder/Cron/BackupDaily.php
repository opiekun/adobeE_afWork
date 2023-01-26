<?php declare(strict_types=1);

namespace Ecommerce121\PartFinder\Cron;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Filesystem\Glob;
use Psr\Log\LoggerInterface;

/**
 * Backup part finder daily
 */
class BackupDaily {
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
    private const PREFIX = "partfinder_export_backup_daily_";
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
     * @throws FileSystemException
     */
    public function execute() {
        $varDir = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->deleteOldFiles($varDir);
        $fileName = self::PREFIX . date('Y-m-d_H') . "." . self::EXTENSION;
        $result = 0;
        $output = "";
        $fullPath = $varDir->getAbsolutePath($fileName);

        exec("bin/magento partfinder:export 10 > " . $fullPath, $output,$result);

        if ($result == 0) {
            $this->logger->info('Part finder export backup daily cron ran successfully ' . $fileName);
            $filenameLatest = $varDir->getAbsolutePath(self::PREFIX . "latest." . self::EXTENSION);
            @unlink($filenameLatest);
            symlink($fileName, $filenameLatest);
        } else {
            $this->logger->error('Part finder export backup daily cron error code ' . $result . " output: " . $output);
        }
    }

    /**
     * Delete one of the 2 week old files
     * @param WriteInterface $varDir
     * @return void
     */
    private function deleteOldFiles(ReadInterface $varDir) {
        $time2WeeksOld = mktime(0, 0, 0, (int)date("m"), (int)date("d") - 14, (int)date("Y"));
        $files = Glob::glob($varDir->getAbsolutePath() . self::PREFIX . "*." . self::EXTENSION);
        foreach ($files as $file) {
            $matches = [];
            if (preg_match("/" . self::PREFIX . "(\\d+-\\d+-\\d+)_.*\." . self::EXTENSION . "/", basename($file), $matches)) {
                $fileDate = $matches[1];
                $time = strtotime($fileDate);
                if ($time < $time2WeeksOld) {
                    $this->logger->info("Part finder backup daily cron deleted old file: " . $file);
                    unlink($file);
                    return;
                }
            }
        }
    }
}
