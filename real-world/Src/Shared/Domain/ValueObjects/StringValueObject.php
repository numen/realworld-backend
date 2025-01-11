<?php
declare(strict_types=1);

namespace Shared\Domain\ValueObjects;

abstract class StringValueObject
{
	private string $value;

    public function __construct(string $value)
	{
		$this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}

