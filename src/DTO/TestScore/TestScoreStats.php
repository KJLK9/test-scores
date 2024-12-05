<?php

namespace App\DTO\TestScore;

class TestScoreStats {
    
    /**
     * @var \App\DTO\TestScore\Student[]
     */
    public array $students = [];
    
    /**
     * @var \App\DTO\TestScore\Question[]
     */
    public array $questions = [];
    
    public function getMaxScores(): array {
        return array_map(fn($q) => $q->maxScore, $this->questions);
    }
    
    public function getQuestionScores(string $questionId): array {
        return array_column(array_map(fn($q) => $q->results, $this->students), $questionId);
    }
    
    public function getTotalScores(): array {
        return array_values(array_map(fn($student) => array_sum($student->results), $this->students));
    }
    
    public function isEmpty(): bool {
        return empty($this->students) || empty($this->questions);
    }
    
}