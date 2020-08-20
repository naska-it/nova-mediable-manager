<?php

return [

    /*
     * The fully qualified class name of the media model.
     */
    'model' => NaskaIt\NovaMediableManager\Models\Media::class,

    /**
     * Default filesystem disk.
     *
     * @example `local` or `public` or `s3`
     * @var string
     */

    'disk' => env('FILESYSTEM_DRIVER', 'public'),

    /**
     * This option allow you to filter your files by types and extensions
     * Format: Label => ['array', 'of', 'extensions']
     *
     * @example ['*'] - allow you to save any file extensions to the specified type
     * @var array - not empty!
     */

    'types' => [
        'Image' => ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/svg', 'image/webp'],
        'Docs' => ['application/doc', 'application/xls', 'application/docx', 'application/xlsx', 'application/pdf'],
        'Audio' => ['audio/mp3'],
        'Video' => ['video/mp4'],
        #'Other' => ['*'],
    ],

    /**
     * Maximum upload size for each type
     * Add `Label` => `max_size` in bytes for needed types to enable limitation
     * If you want to disable the limitation - leave empty array
     *
     * @var array
     */

    'max_size' => [
        'Image' => 2097152,
        'Docs' => 5242880,
    ],

    /**
     * The number of files that will be returned with each step
     *
     * @var integer
     */

    'step' => 40,

    /**
     * Allow you to resize original images by width\height. Using http://image.intervention.io library.
     * Width and height can be integer or null. If one of them is null - will resize image proportionally.
     *
     * @see supports image formats: http://image.intervention.io/getting_started/formats.
     * @var array
     */

    'conversions' => [
        'driver' => 'gd',
        'resize' => [
            'enable' => false,
            'width' => '1280',
            'height' => null
        ],
        'preview' => [
            'type' => 'resize',
            'quality' => '90%',
            'upsize' => true,
            'width' => '300',
            'height' => null,
        ],
        'jobs' => null
    ],

    'fields' => [
        'index' => [
            'allow_manager' => true,
        ],
        'details' => [
            'allow_manager' => true,
        ],
        'form' => [
            'allow_manager' => true,
        ],
    ]

];
