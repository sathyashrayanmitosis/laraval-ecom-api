<?php


namespace Mitosis\Properties\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mitosis\Properties\Contracts\PropertyValue as PropertyValueContract;

/**
 * @property \Mitosis\Properties\Contracts\Property $property
 * @property string                                $value      The value as stored in the db @see getValue()
 * @property string                                $title
 * @property integer                               $priority
 * @property array|null                            $settings
 *
 */
class PropertyValue extends Model implements PropertyValueContract
{
    use Sluggable, SluggableScopeHelpers;

    protected $table = 'property_values';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'settings' => 'array'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(PropertyProxy::modelClass());
    }

    public function scopeSort($query)
    {
        return $query->orderBy('priority');
    }

    public function scopeSortReverse($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    public function scopeByProperty($query, $property)
    {
        $id = is_object($property) ? $property->id : $property;

        return $query->where('property_id', $id);
    }

    /**
     * Returns the transformed value according to the underlying type
     */
    public function getValue()
    {
        return $this->property->getType()->transformValue($this->value, $this->settings);
    }

    public function scopeWithUniqueSlugConstraints(Builder $query, Model $model, $attribute, $config, $slug)
    {
        return $query->where('property_id', $model->property->id);
    }

    public function sluggable(): array
    {
        return [
            'value' => [
                'source' => 'title'
            ]
        ];
    }
}
