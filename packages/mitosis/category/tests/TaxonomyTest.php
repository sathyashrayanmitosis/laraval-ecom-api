<?php
/**
 * Contains the TaxonomyTest class.
 *
 * 
 * 
 * 
 * @since       2018-08-27
 *
 */

namespace Mitosis\Category\Tests;

use Mitosis\Category\Models\Taxon;
use Mitosis\Category\Models\Taxonomy;

class TaxonomyTest extends TestCase
{
    /** @test */
    public function taxonomies_must_have_a_name()
    {
        $this->expectExceptionMessageMatches('/NOT NULL constraint failed: taxonomies\.name/');

        Taxonomy::create();
    }

    /** @test */
    public function slug_is_autogenerated_from_name()
    {
        $taxonomy = Taxonomy::create(['name' => 'Example Category']);

        $this->assertEquals('example-category', $taxonomy->slug);
    }

    public function slug_can_be_explicitly_set()
    {
        $taxonomy = Taxonomy::create(['name' => 'Wine Regions', 'slug' => 'regions']);

        $this->assertEquals('regions', $taxonomy->slug);
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $this->expectExceptionMessageMatches('/UNIQUE constraint failed: taxonomies\.slug/');

        Taxonomy::create(['name' => 'Category']);
        Taxonomy::create(['name' => 'Category', 'slug' => 'category']);
    }

    /** @test */
    public function slug_is_automatically_extended_in_case_it_already_exists_to_prevent_duplicate()
    {
        $category1 = Taxonomy::create(['name' => 'Category']);
        $category2 = Taxonomy::create(['name' => 'Category']);

        $this->assertEquals('category', $category1->slug);
        $this->assertNotEquals('category', $category2->slug);
    }

    /** @test */
    public function can_return_the_root_level_taxons()
    {
        $category = Taxonomy::create(['name' => 'Category']);

        $root1 = Taxon::create(['taxonomy_id' => $category->id, 'name' => 'Root 1']);
        $root2 = Taxon::create(['taxonomy_id' => $category->id, 'name' => 'Root 2']);
        $root3 = Taxon::create(['taxonomy_id' => $category->id, 'name' => 'Root 3']);
        $root4 = Taxon::create(['taxonomy_id' => $category->id, 'name' => 'Root 4']);

        Taxon::create(['taxonomy_id' => $category->id, 'name' => 'Child of Root 1', 'parent_id' => $root1]);
        Taxon::create(['taxonomy_id' => $category->id, 'name' => 'Second Child of Root 1', 'parent_id' => $root1]);
        Taxon::create(['taxonomy_id' => $category->id, 'name' => 'Child of Root 3', 'parent_id' => $root3]);

        $roots = $category->rootLevelTaxons();

        $this->assertCount(4, $roots);
        $this->assertArrayHasKey($root1->name, $roots->keyBy('name'));
        $this->assertArrayHasKey($root2->name, $roots->keyBy('name'));
        $this->assertArrayHasKey($root3->name, $roots->keyBy('name'));
        $this->assertArrayHasKey($root4->name, $roots->keyBy('name'));
    }

    /** @test */
    public function root_level_taxons_method_returns_an_empty_collection_if_no_taxons_are_defined()
    {
        $category = Taxonomy::create(['name' => 'Category']);

        $this->assertCount(0, $category->rootLevelTaxons());
    }

    /** @test */
    public function taxonomy_can_be_retrieved_by_name_with_the_dedicated_finder_method()
    {
        Taxonomy::create(['name' => 'Category']);
        Taxonomy::create(['name' => 'Brands']);

        $this->assertEquals('Category', Taxonomy::findOneByName('Category')->name);
        $this->assertEquals('Brands', Taxonomy::findOneByName('Brands')->name);
        $this->assertNull(Taxonomy::findOneByName('I dont exist'));
    }
}
