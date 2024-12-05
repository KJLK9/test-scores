<?php

namespace App\Service\TestScore;

use App\DTO\TestScore\TestScoreStats;
use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;

readonly class TestScoreService {
    
    public function __construct(
        private DataReader          $dataReader,
        private DataFormatter       $dataFormatter,
        private GradeCalculator     $gradeCalculator,
        private StatisticalAnalyzer $statisticalAnalyzer,
        private LoggerInterface     $logger
    ) {
    }
    
    public function getStatsFromFilePath(string $filePath): TestScoreStats {
        try {
            $rawData = $this->dataReader->readData($filePath);
            if (empty($rawData)) {
                return new TestScoreStats();
            }
            
            $formattedData = $this->dataFormatter->formatData($rawData);
            
            $this->gradeCalculator->calculateGrades($formattedData);
            $this->statisticalAnalyzer->calculatePValues($formattedData);
            $this->statisticalAnalyzer->calculateRITValues($formattedData);
            
            return $formattedData;
        } catch (Exception $e) {
            $this->logger->error('Error processing test scores', ['exception' => $e]);
            throw new RuntimeException('An error occurred while processing the file.');
        }
    }
}
