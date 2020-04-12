<?php


namespace Mitosis\Category\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Mitosis\Category\Contracts\Taxon as TaxonContract;
use Mitosis\Category\Contracts\Taxonomy as TaxonomyContract;

class Taxon extends Model implements TaxonContract
{
    use Sluggable, SluggableScopeHelpers;

    /** @var Collection */
    private $_parents;

    protected $table = 'taxons';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getParentsAttribute(): Collection
    {
        if (!$this->_parents) {
            $this->_parents = collect();

            $parent = $this->parent;
            while ($parent) {
                $this->_parents->push($parent);
                $parent = $parent->parent;
            }
        }

        return $this->_parents;
    }

    public function getLevelAttribute(): int
    {
        return $this->parents->count();
    }

    public function isRootLevel(): bool
    {
        return (bool) ($this->parent_id == null);
    }

    public function lastNeighbour(bool $excludeSelf = false)
    {
        if ($excludeSelf) {
            return $this->neighbours()->except($this)->sortReverse()->first();
        }

        return $this->neighbours()->sortReverse()->first();
    }

    /**
     * @inheritdoc
     */
    public function firstNeighbour(bool $excludeSelf = false)
    {
        if ($excludeSelf) {
            return $this->neighbours()->except($this)->sort()->first();
        }

        return $this->neighbours()->sort()->first();
    }

    public function setParentIdAttribute($value)
    {
        $this->attributes['parent_id'] = $value;

        $this->_parents = null;
    }

    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(TaxonomyProxy::modelClass(), 'taxonomy_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TaxonProxy::modelClass(), 'parent_id');
    }

    public function neighbours(): HasMany
    {
        return $this->hasMany(TaxonProxy::modelClass(), 'parent_id', 'parent_id')
                    ->byTaxonomy($this->taxonomy);
    }

    public function removeParent()
    {
        $this->parent()->dissociate();
    }

    public function setParent(TaxonContract $taxon)
    {
        $this->parent()->associate($taxon);
    }

    public function setTaxonomy(TaxonomyContract $taxonomy)
    {
        $this->taxonomy()->associate($taxonomy);
    }

    public function children(): HasMany
    {
        return $this->hasMany(TaxonProxy::modelClass(), 'parent_id')->sort();
    }

    public function scopeByTaxonomy($query, $taxonomy)
    {
        $id = is_object($taxonomy) ? $taxonomy->id : $taxonomy;

        return $query->where('taxonomy_id', $id);
    }

    public function scopeSort($query)
    {
        return $query->orderBy('priority');
    }

    public function scopeSortReverse($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    public function scopeRoots($query)
    {
        return $query->where('parent_id', null);
    }

    public function scopeExcept($query, TaxonContract $taxon)
    {
        return $query->where('id', '<>', $taxon->id);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'unique' => false
            ]
        ];
    }
}
