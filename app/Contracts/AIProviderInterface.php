<?php

namespace App\Contracts;

interface AIProviderInterface
{
    public function generateReply(
        string $subject,
        string $body,
        array $config
    ): array;
}
