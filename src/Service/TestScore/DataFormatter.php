<?php

namespace App\Service\TestScore;

use App\DTO\TestScore\Question;
use App\DTO\TestScore\Student;
use App\DTO\TestScore\TestScoreStats;

class DataFormatter {
    
    const string SCORE_PREFIX = 'Score ';
    
    public function formatData(array $data): TestScoreStats {
        $headers = array_shift($data);
        array_shift($headers); // Remove first column (student IDs).
        
        // Remove header prefix.
        $headers = array_map(
            fn($header) => str_starts_with($header, self::SCORE_PREFIX) ? substr($header, strlen(self::SCORE_PREFIX)) : $header,
            $headers
        );
        
        $maxScores = array_shift($data);
        array_shift($maxScores); // Remove first column (Question ID).
        
        $dto = new TestScoreStats();
        foreach ($headers as $index => $header) {
            $dto->questions[$header] = new Question($header, $maxScores[$index]);
        }
        
        foreach ($data as $row) {
            $id = array_shift($row);
            $dto->students[$id] = new Student($id, array_combine($headers, array_map('intval', $row)));
        }
        
        return $dto;
    }
    
}