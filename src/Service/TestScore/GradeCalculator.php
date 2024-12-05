<?php

namespace App\Service\TestScore;

use App\DTO\TestScore\Grader;
use App\DTO\TestScore\TestScoreStats;

class GradeCalculator {
    
    private Grader $grader;
    
    public function __construct() {
        $this->grader = new Grader(1.0, 10.0, 20, 100);
    }
    
    public function calculateGrades(TestScoreStats $data): void {
        $maxScore = $data->getMaxScores();
        foreach ($data->students as $student) {
            $student->grade = $this->grader->calculateGrade(
                $student->results,
                $maxScore
            );
        }
    }
}