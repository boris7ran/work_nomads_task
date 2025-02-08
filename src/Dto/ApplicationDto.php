<?php

namespace App\Dto;

class ApplicationDto
{
    public function __construct(
        public string $id,
        public string $name,
        public array $roles = [],
    ) {}
}