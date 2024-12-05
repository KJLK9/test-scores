<?php

namespace App\Service\TestScore;

use Aspera\Spreadsheet\XLSX\Reader;
use Aspera\Spreadsheet\XLSX\ReaderConfiguration;
use Aspera\Spreadsheet\XLSX\ReaderSkipConfiguration;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

readonly class DataReader {
    public function __construct(private LoggerInterface $logger) {
    }
    
    /**
     * Simply returns the content data without altering it.
     */
    public function readData(string $filePath): array {
        $config = new ReaderConfiguration();
        $config->setSkipEmptyRows(ReaderSkipConfiguration::SKIP_EMPTY);
        
        try {
            $reader = new Reader($config);
            $reader->open($filePath);
            
            $data = [];
            foreach ($reader as $row) {
                $data[] = $row;
            }
            $reader->close();
            
            return $data;
        } catch (Exception $e) {
            $this->logger->error('Error reading file', ['filePath' => $filePath, 'exception' => $e]);
            throw new FileException('Failed to read the file.');
        }
    }
}
