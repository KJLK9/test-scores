<?php

namespace App\DTO\TestScore;

class Student {
    public function __construct(
        public string $id,
        public array  $results,
        public ?float $grade = null
    ) {
    }
    
    
    /**
     * @return float|null
     */
    public function getGrade(): ?float {
        return round($this->grade, 1);
    }
}