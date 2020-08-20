<?php

namespace NaskaIt\NovaMediableManager\Http\Controllers;

use NaskaIt\NovaMediableManager\Models\Media;
use NaskaIt\NovaMediableManager\MediaUploader;
use Illuminate\Http\Resources\Json\JsonResource;
use NaskaIt\NovaMediableManager\Http\Resources\Media as MediaResource;
use Intervention\Image\ImageManagerStatic as Image;

class MediableController
{

    public function __construct()
    {
        JsonResource::withoutWrapping();
    }

    public function index()
    {
        $filter = $this->getItemFilters();

        $data = Media::orderBy('created_at', 'desc')
                    ->when($filter['name'], function ($query, $name) {
                        $query->where('name', 'like', '%'.$name.'%');
                    })
                    ->when($filter['mime_type'], function ($query, $mime_type) {
                        $query->where('mime_type', 'like', $mime_type.'%');
                    })
                    ->paginate($filter['per_page']);

        return MediaResource::collection($data);
    }

    public function stats($disk = null, $type = null)
    {
        return Media::all()->sum('size'); //17566633 return item count, total size grouped
    }

    public function upload()
    {
        $disk = config('nova-mediable-manager.disk');
        $item = null;
        $status = 'success';
        $message = null;

        $file = request()->file('file');
        $item =  MediaUploader::fromFile($file)->toDisk($disk)->upload();

        if ($item->isOfType('image')) {
            //@todo support s3 conversions, problem to read source as well
            $fileSize = $this->runDefaultConversionConversions($item, $file);
        }

        return [
            'status' => $status,
            'data' => $item,
            'message' => $message
        ];
    }

    public function runDefaultConversionConversions($item, $file)
    {
        $data = null;

        $config = config('nova-mediable-manager.conversions');

        $previewDir = $item->getKey().'/'.'conversions/preview/';

        if ($item->filesystem()->exists($previewDir)) {
            // conversion already exist redo ?!?
        } elseif ($item->filesystem()->makeDirectory($previewDir)) {
            $item->filesystem()->makeDirectory($previewDir);

            $manipulate = $config['preview'];

            Image::configure(array('driver' => $config['driver']));

            // $image = Image::make($item->getFullPath());
            $image = Image::make($file);

            $preview = Image::make(clone $image);

            $preview->resize($manipulate['width'], $manipulate['height'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $item->filesystem()->put($previewDir.$item->file_name, (string) $preview->encode());

            $data = $preview->filesize();

            $image->destroy();
            
            $preview->destroy();
        } else {
            // trow some error
        }

        return $data;
    }

    public function delete($id)
    {
        return Media::findOrFail($id)->delete();
    }

    public function mediable()
    {
        $data = [];

        $mediable = $this->getMediable();

        $collection = request()->get('collection');

        if ($mediable) {
            $data = $mediable->getMedia($collection);
        }

        return MediaResource::collection($data);
    }

    public function attach($id)
    {
        $mediable = $this->getMediable();
        $media = new Media;
        $media->id = $id;

        $collection = request()->get('collection');
        $single = request()->get('is_single');
        //$flexible = request()->get('is_flexible');

        if ($single && $mediable->hasMedia($collection)) {
            $mediable->clearMediaGroup($collection);
        }

        return $mediable->attachMedia($media, $collection);
    }

    public function detach($id)
    {
        $mediable = $this->getMediable();
        $media = new Media;
        $media->id = $id;

        $collection = request()->get('collection');

        return $mediable->detachMedia($media, $collection);
    }


    private function getItemFilters()
    {
        $filter = [];
        $filter['per_page'] = request()->get('per_page') ?? 5;
        $filter['name'] = request()->get('name') ?? null;
        $filter['mime_type'] = request()->get('mime_type') ?? null;
        $filter['from'] = request()->get('from') ?? null;
        $filter['to'] = request()->get('to') ?? null;
        /*$filter['as_list'] = filter_var($request->get('as_list'), FILTER_VALIDATE_BOOLEAN) ? true : false;
        $filter['cats'] = $request->get('cats') ? array_map('intval', $request->get('cats')) : [];
        $filter['tags'] = $request->get('tags') ? array_map('intval', $request->get('tags')) : [];*/

        return $filter;
    }

    private function getMediable()
    {
        $model =  app(request()->get('model'));
        $model_id = request()->get('model_id');

        return $model::find($model_id);
    }

}
