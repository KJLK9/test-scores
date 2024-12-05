<?php

namespace App\Service\TestScore;

use App\DTO\TestScore\TestScoreStats;
use DivisionByZeroError;

class StatisticalAnalyzer {
    
    /**
     * Calculate the P value of a question.
     */
    public function calculatePValues(TestScoreStats $data): void {
        foreach ($data->questions as $question) {
            $questionScores = $data->getQuestionScores($question->id);
            
            if (empty($questionScores)) {
                $question->PValue = 0;
                continue;
            }
            
            try {
                $averageScore = array_sum($questionScores) / count($questionScores);
                
                $question->PValue = $averageScore / $question->maxScore;
            } catch (DivisionByZeroError $e) {
                $question->PValue = 0;
            }
        }
    }
    
    /**
     * Calculate the RIT value of a question.
     */
    public function calculateRITValues(TestScoreStats $data): void {
        $totalScores = $data->getTotalScores();
        foreach ($data->questions as $question) {
            $questionScores = $data->getQuestionScores($question->id);
            if (empty($questionScores)) {
                $question->RITValue = 0;
                continue;
            }
            try {
                $meanX = array_sum($questionScores) / count($questionScores);
                $meanY = array_sum($totalScores) / count($totalScores);
                
                $numerator = $denominatorX = $denominatorY = 0;
                foreach ($questionScores as $i => $score) {
                    $diffX = $score - $meanX;
                    $diffY = $totalScores[$i] - $meanY;
                    $numerator += $diffX * $diffY;
                    $denominatorX += $diffX ** 2;
                    $denominatorY += $diffY ** 2;
                }
                
                $denominator = sqrt($denominatorX * $denominatorY);
                $question->RITValue = $numerator / $denominator;
            } catch (DivisionByZeroError $e) {
                $question->RITValue = 0;
                continue;
            }
        }
    }
}
