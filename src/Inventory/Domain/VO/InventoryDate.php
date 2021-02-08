<?php

declare(strict_types=1);

namespace App\Inventory\Domain\VO;

use App\Inventory\Domain\Exception\InvalidInventoryDate;

final class InventoryDate
{
    private \DateTimeImmutable $date;

    public function __construct(\DateTimeImmutable $date)
    {
        if (false === date("Y-m-t", strtotime($date)) || ('Sunday' !== date_format(date_create($date), 'l'))) {
            throw new InvalidInventoryDate();
        }
        $this->date = $date;
    }

    public static function fromDate(\DateTimeImmutable $date): self
    {
        return new self($date);
    }

    public function getValue(): \DateTimeImmutable
    {
        return $this->date;
    }
}
