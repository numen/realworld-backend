<?php
declare(strict_types=1);

namespace Shared\Domain\ValueObjects;

abstract class StringNullableValueObject
{
	private ?string $value;

    public function __construct(?string $value=null)
	{
		$this->value = $value;
    }

    public function value(): ?string
    {
        return $this->value;
    }
}

