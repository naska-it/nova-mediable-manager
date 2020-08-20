<?php

namespace NaskaIt\NovaMediableManager\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Media extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            'name' => $this->name,
            'caption' => $this->caption ?? $this->name,
            'size' => $this->size,
            'url' => $this->getUrl(),
            'mime_type' => $this->mime_type,
            'conversions' => [
                'preview' => $this->isOfType('image') ? $this->getUrl('preview') : null,
                //'preview' => $this->getUrl(),
            ]
        ];
    }
}
