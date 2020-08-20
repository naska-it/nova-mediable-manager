<?php

namespace NaskaIt\NovaMediableManager;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Field;
use NaskaIt\NovaMediableManager\Models\MediaUploader;
use NaskaIt\NovaMediableManager\Models\Media;
use NaskaIt\NovaMediableManager\Http\Resources\Media as MediaResource;

class Mediable extends Field
{
    public $flexible = false;
    public $flexibleSuffix = null;
    public $single = false;
    
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'mediable';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        \Log::info('Mediable Construct');
    }

    public function isFlexible()
    {
        $this->flexible = true;
        return $this;
    }

    public function isSingle()
    {
        $this->single = true;
        return $this;
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        $attribute = $attribute ?? $this->attribute;
        $collection = $attribute;

        if ($this->flexible) {
            \Log::info('Flexible: '.$attribute);
            \Log::info('Flexible: '.$resource->key);
            $collection = $this->resolveFlexibleCollection($attribute);
        }

        $media = $resource->getMedia($collection);

        $this->value = $this->value ?: [];

        $this->withMeta([
            'is_flexible' => $this->flexible,
            'media' => MediaResource::collection($media),
            'mediable' => [
                'model' => get_class($resource),
                'model_id' => $resource->id,
                'collection' => $collection,
                'is_single' => $this->single,
                'is_flexible' => $this->flexible,
            ]
        ]);

        if ($media) {
            $this->value['media'] = MediaResource::collection($media);
        } else {
            $this->value['media'] = [];
        }
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return mixed
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        
        // - use suffix to greb value but set collection without suffix, because of coversion definision
        if ($request->exists($requestAttribute)) {
            $value = json_decode($request[$requestAttribute]);

            // - mediable manager is handling attaching this only when resource in create editMode
            //$model->{$attribute} = $this->isNullValue($value) ? null : $value;

            // - check if flexible to get block suffix before u set Media model

            $model = $this->mediableModel($model);

            // - check if resource is in editMode=create
            if (!$model->id) {
                \Log::info('Resource in create editMode!');

                $single = $this->single;
                $flexibleSuffix = $this->flexibleSuffix;
                $flexible = $this->flexible;

                if (is_subclass_of($model, 'Illuminate\Database\Eloquent\Model')) {
                    $model::created(function ($model) use ($single, $value, $flexibleSuffix, $flexible, $requestAttribute) {
                        
                        $collection =   $requestAttribute;//.$flexibleSuffix;

                        if ($single && $model->hasMedia($collection)) {
                            $model->clearMediaGroup($collection);
                        }

                        if ($flexible) {
                            if ($model->key == ltrim($flexibleSuffix, '_')) {
                                foreach ($value as $id) {
                                    $media = new Media;
                                    $media->id = $id;
                                    $model->attachMedia($media, $collection);
                                }
                            }
                        } else {
                            foreach ($value as $id) {
                                $media = new Media;
                                $media->id = $id;
                                $model->attachMedia($media, $collection);
                            }
                        }
                    });
                }
            }
        }
    }

    protected function mediableModel($model)
    {
        $mediaModel = $model;

        if ($this->flexible) {
            $this->flexibleSuffix = $model->getSuffix();
            $mediaModel = $model->getMediaModel();
        }

        return $mediaModel;
    }

    protected function resolveFlexibleCollection($attribute)
    {
        $collection = $attribute;

        $data = explode('_', $attribute, 2);

        if (is_array($data) && count($data) == 2) {
            $collection = $data[0];
        }

        return $collection;
    }
}
