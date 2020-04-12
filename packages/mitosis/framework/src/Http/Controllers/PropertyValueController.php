<?php

namespace Mitosis\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Mitosis\Framework\Contracts\Requests\CreatePropertyValue;
use Mitosis\Framework\Contracts\Requests\CreatePropertyValueForm;
use Mitosis\Framework\Contracts\Requests\SyncModelPropertyValues;
use Mitosis\Framework\Contracts\Requests\UpdatePropertyValue;
use Mitosis\Properties\Contracts\Property;
use Mitosis\Properties\Contracts\PropertyValue;
use Mitosis\Properties\Models\PropertyProxy;
use Mitosis\Properties\Models\PropertyValueProxy;

class PropertyValueController extends BaseController
{
    public function create(CreatePropertyValueForm $request, Property $property)
    {
        $propertyValue = app(PropertyValue::class);

        $propertyValue->property_id = $property->id;

        $propertyValue->priority = $request->getNextPriority($propertyValue);

        return view('mitosis::property-value.create', [
            'property'       => $property,
            'properties'     => PropertyProxy::get()->pluck('name', 'id'),
            'hideProperties' => true,
            'propertyValue'  => $propertyValue
        ]);
    }

    public function store(Property $property, CreatePropertyValue $request)
    {
        try {
            $propertyValue = PropertyValueProxy::create(
                array_merge(
                    $request->all(),
                    ['property_id' => $property->id]
                )
            );

            flash()->success(__(':title :property has been created', [
                'title'    => $propertyValue->title,
                'property' => $property->name
            ]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('mitosis.property.show', $property));
    }

    public function edit(Property $property, PropertyValue $property_value)
    {
        return view('mitosis::property-value.edit', [
            'property'      => $property,
            'properties'    => PropertyProxy::get()->pluck('name', 'id'),
            'propertyValue' => $property_value
        ]);
    }

    public function update(Property $property, PropertyValue $property_value, UpdatePropertyValue $request)
    {
        try {
            $property_value->update($request->all());

            flash()->success(__(':title has been updated', ['title' => $property_value->title]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('mitosis.property.show', $property));
    }

    public function destroy(Property $property, PropertyValue $property_value)
    {
        try {
            $title = $property_value->title;
            $property_value->delete();

            flash()->warning(__(':title has been deleted', ['title' => $title]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect(route('mitosis.property.show', $property));
    }

    public function sync(SyncModelPropertyValues $request, $for, $forId)
    {
        $model = $request->getFor();
        $model->propertyValues()->sync($request->getPropertyValueIds());

        return redirect(route(sprintf('mitosis.%s.show', shorten(get_class($model))), $model));
    }
}
