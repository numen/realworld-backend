<?php
declare(strict_types=1);

namespace Shared\Domain\ValueObjects;

use DateTime;

abstract class DateTimeValueObject
{
	private ?DateTime $value = null;

    public function __construct(string $value, $format = 'Y-m-d H:i:s')
    {
        $date = DateTime::createFromFormat($format, $value);
        if($date) {
		    $this->value = $date;
        }
    }

    function checkIsAValidDate($myDateString){
        return (bool)strtotime($myDateString);
    }

    public function value(): ?DateTime
    {
        return $this->value;
    }
    public function serialize(): string
    { return $this->value->format('Y-m-d H:i:s');
    }
}

