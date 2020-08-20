<?php

namespace NaskaIt\NovaMediableManager;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use NaskaIt\NovaMediableManager\Models\Media;

trait HasFlexibleMediable
{
    public function getMediaModel()
    {
        return $this->resolveModel();
    }

    /**
     * Resolve the field's value for display.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolveForDisplay(array $attributes = [])
    {
        $this->fields->each(function ($field) use ($attributes) {

            if ($field->component == 'mediable') {
                $field->resolveForDisplay($this->resolveModel(), $field->attribute . $this->getSuffix());
            } else {
                $field->resolveForDisplay($attributes);
            }
        });

        return $this->getResolvedValue();
    }

    /**
     * Resolve fields using given attributes.
     *
     * @param  boolean $empty
     * @return void
     */
    public function resolve($empty = false)
    {
        $this->fields->each(function ($field) use ($empty) {
            if ($field->component == 'mediable') {
                $field->resolve($this->resolveModel(), $field->attribute . $this->getSuffix());
            } else {
                $field->resolve($empty ? $this->duplicate($this->inUseKey()) : $this);
            }
        });

        return $this->getResolvedValue();
    }
}
