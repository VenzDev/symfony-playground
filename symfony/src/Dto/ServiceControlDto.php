<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ServiceControlDto
{
    const SUPPORTED_OPTIONS = ['suspend', 'unsuspend', 'terminate'];

    #[NotBlank]
    #[Choice(choices: self::SUPPORTED_OPTIONS)]
    private string $option;

    #[Type(type: "int")]
    private int $delay;

    /**
     * @return string
     */
    public function getOption(): string
    {
        return $this->option;
    }

    /**
     * @param string $option
     */
    public function setOption(string $option): void
    {
        $this->option = $option;
    }

    /**
     * @return int
     */
    public function getDelay(): int
    {
        return $this->delay;
    }

    /**
     * @param int $delay
     */
    public function setDelay(int $delay): void
    {
        $this->delay = $delay;
    }
}