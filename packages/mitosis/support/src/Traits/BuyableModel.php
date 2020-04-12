<?php


namespace Mitosis\Support\Traits;

use Carbon\Carbon;

/**
 * Trait for Eloquent Models with default implementation of the Buyable interface
 */
trait BuyableModel
{
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function addSale(Carbon $date, $units = 1)
    {
        $this->last_sale_at = $date;
        $this->units_sold += $units;
        $this->save();
    }

    public function removeSale($units = 1)
    {
        $this->units_sold -= $units;
        $this->save();
    }

    public function morphTypeName(): string
    {
        return shorten(static::class);
    }
}
