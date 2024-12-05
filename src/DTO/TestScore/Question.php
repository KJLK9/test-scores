<?php

namespace App\DTO\TestScore;

class Question {
    public function __construct(
        public string $id,
        public float  $maxScore,
        public ?float $PValue = null,
        public ?float $RITValue = null
    ) {
    }
    
    public function getPValue(): ?float {
        return round(min(max($this->PValue, 0), 1), 2);
    }
    
    public function getRITValue(): ?float {
        return round(min(max($this->RITValue, 0), 1), 2);
    }
    
}
