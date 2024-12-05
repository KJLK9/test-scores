<?php

namespace App\DTO\TestScore;

use InvalidArgumentException;

readonly class Grader {
    public function __construct(
        private float $minGrade,// Minimum grade.
        private float $maxGrade,// Maximum grade.
        private float $minPercentage, // Minimum percentage for min grade.
        private float $maxPercentage // Maximum percentage for max grade.
    ) {
        if ($minPercentage >= $maxPercentage || $minGrade >= $maxGrade) {
            throw new InvalidArgumentException("Invalid input.");
        }
    }
    
    /**
     * Calculates the grade based on scores gained and maximum scores.
     *
     * @param array $gainedScores Array of scores the student gained.
     * @param array $maxScores Array of maximum possible scores.
     * @return float The calculated grade, rounded to 1 decimal place.
     */
    public function calculateGrade(array $gainedScores, array $maxScores): float {
        // Calculate total scores
        $totalGainedScore = array_sum($gainedScores);
        $totalMaxScore = array_sum($maxScores);
        
        // Validate totalMaxScore
        if ($totalMaxScore === 0) {
            throw new InvalidArgumentException("Total maximum score should not be zero.");
        }
        
        // Calculate the percentage score
        $percentageScore = ($totalGainedScore / $totalMaxScore) * 100;
        
        // Handle edge cases: percentage below min or above max
        if ($percentageScore <= $this->minPercentage) {
            return round($this->minGrade, 1);
        }
        
        if ($percentageScore >= $this->maxPercentage) {
            return round($this->maxGrade, 1);
        }
        
        // Calculate grade using linear interpolation
        $grade = $this->minGrade + (
                ($percentageScore - $this->minPercentage)
                * ($this->maxGrade - $this->minGrade)
                / ($this->maxPercentage - $this->minPercentage)
            );
        
        // Return the grade rounded to 1 decimal place
        return round($grade, 1);
    }
}
