<?php

namespace App\Contracts;

interface NewsProviderInterface
{
    public function fetch(int $page = 1, int $pageSize = 50): array;
    public function key(): string;
    public function name(): string;
}
