<?php


namespace Mitosis\Properties\Tests;

use Mitosis\Properties\Models\Property;
use Mitosis\Properties\Models\PropertyValue;

class PropertyValueTest extends TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $property = factory(Property::class)->create(['type' => 'integer']);

        $value1 = PropertyValue::create([
            'property_id' => $property->id,
            'value'       => 1,
            'title'       => '1'
        ]);

        $value2 = PropertyValue::create([
            'property_id' => $property->id,
            'value'       => 2,
            'title'       => '2'
        ]);

        $this->assertEquals('1', $value1->value);
        $this->assertEquals('2', $value2->value);
    }

    /** @test */
    public function get_value_method_returns_the_transformed_value_on_integer_fields()
    {
        $property = factory(Property::class)->create(['type' => 'integer']);

        $value3007 = PropertyValue::create([
            'property_id' => $property->id,
            'value'       => 3007,
            'title'       => '3007'
        ]);

        $this->assertEquals(3007, $value3007->getValue());
        $this->assertIsInt($value3007->getValue());
    }

    /** @test */
    public function get_value_method_returns_the_transformed_value_on_boolean_fields()
    {
        $property = factory(Property::class)->create(['type' => 'boolean']);

        $valueTrue = PropertyValue::create([
            'property_id' => $property->id,
            'value'       => true,
            'title'       => 'Yes'
        ]);

        $valueFalse = PropertyValue::create([
            'property_id' => $property->id,
            'value'       => 'false',
            'title'       => 'No'
        ]);

        $valueZero = PropertyValue::create([
            'property_id' => $property->id,
            'value'       => '0',
            'title'       => 'No'
        ]);

        $this->assertEquals(true, $valueTrue->getValue());
        $this->assertIsBool($valueTrue->getValue());

        $this->assertEquals(false, $valueFalse->getValue());
        $this->assertIsBool($valueFalse->getValue());

        $this->assertEquals(false, $valueZero->getValue());
        $this->assertIsBool($valueZero->getValue());
    }

    /** @test */
    public function get_value_method_returns_the_transformed_value_on_number_fields()
    {
        $property = factory(Property::class)->create(['type' => 'number']);

        $value11point27 = PropertyValue::create([
            'property_id' => $property->id,
            'value'       => 11.27,
            'title'       => '11.27'
        ]);

        $this->assertEquals(11.27, $value11point27->getValue());
        $this->assertIsFloat($value11point27->getValue());
    }

    /** @test */
    public function the_settings_field_is_an_array()
    {
        $property = factory(Property::class)->create();

        $valueX = PropertyValue::create([
            'property_id' => $property->id,
            'value'       => 'x',
            'title'       => 'X',
            'settings'    => ['x' => 123, 'y' => 456]
        ]);

        $this->assertEquals(['x' => 123, 'y' => 456], $valueX->settings);
    }
}
