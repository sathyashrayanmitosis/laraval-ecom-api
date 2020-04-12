<?php


namespace Mitosis\Product\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Konekt\Enum\Eloquent\CastsEnums;
use Mitosis\Product\Contracts\Product as ProductContract;

class Product extends Model implements ProductContract
{
    use CastsEnums, Sluggable, SluggableScopeHelpers;

    protected $table = 'products';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $enums = [
        'state' => 'ProductStateProxy@enumClass'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return $this->getSlugKeyName();
    }

    /**
     * @inheritdoc
     */
    public function isActive()
    {
        return $this->state->isActive();
    }

    /**
     * @return bool
     */
    public function getIsActiveAttribute()
    {
        return $this->isActive();
    }

    public function isOnStock(): bool
    {
        return $this->stock > 0;
    }

    public function title()
    {
        return isset($this->ext_title) ? $this->ext_title : $this->name;
    }

    /**
     * @return string
     */
    public function getTitleAttribute()
    {
        return $this->title();
    }

    /**
     * Scope for returning the products with active state
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActives($query)
    {
        return $query->whereIn(
            'state',
            ProductStateProxy::getActiveStates()
        );
    }
}
