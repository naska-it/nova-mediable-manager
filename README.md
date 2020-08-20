# Nova Mediable Manager

## This package is still in heavy development, please dont use in production
Laravel Nova Media Manager and Field to manage Polymorphic ManyToMany Relationships.

##### Table of Contents

* [Install](#install)
* [Configuration](#configuration)
* [Using With Flexible Field](#flexible)
* [Credits](#credits)
* [Alternatives](#alternatives)
* [Preview](#examples)

![Manager](../assets/manager-1..gif)

## Install
```bash
composer require naska-it/nova-mediable-manager
php artisan vendor:publish --provider="NaskaIt\NovaMediableManager\ToolServiceProvider"
php artisan migrate
php artisan storage:link
```
## Configuration

 Check in config/nova-mediable-manager.php for some extra configuration
 Enable Manager add to app/Providers/NovaServiceProvider.php

```php
/**
 * Get the tools that should be listed in the Nova sidebar.
 *
 * @return array
 */
public function tools()
{
    return [
        new \NaskaIt\NovaMediableManager\NovaMediableManager
    ];
}
```
 Using Field - first add HasMedia trait to your model

```php
use NaskaIt\NovaMediableManager\HasMedia;

class Page extends Model
{
    use HasMedia;
```

 Using Field - Nova Resource 

```php
use NaskaIt\NovaMediableManager\Mediable;

class Page extends Resource
{
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('Name'),
            Trix::make('Body'),

            Mediable::make('Image')
                ->hideFromIndex()
                ->isSingle(),

            Mediable::make('Featured')
                ->isSingle(),

            Mediable::make('Gallery'),
        ];
    }
```
## Flexible

If you use Mediable field inside Flexible Layout

```php
use NaskaIt\NovaMediableManager\Mediable;

public function fields()
{
    return [
        Text::make('Headline'),
        Text::make('Slogan'),
        Mediable::make('Block')
        	->isSingle()
        	->isFlexible(),
    ];
}
```
Add to your layout HasFlexibleMediable trait

```php
use NaskaIt\NovaMediableManager\HasFlexibleMediable;

class BlockLayout extends Layout
{
    use HasFlexibleMediable;
```
# Credits
Where I get some code from :)

* [optixsolutions/laravel-media](https://github.com/optixsolutions/laravel-media)
* [https://github.com/classic-o/nova-media-library](https://github.com/classic-o/nova-media-library)

# Alternatives
What I was using Before

* [ebess/advanced-nova-media-library](https://github.com/ebess/advanced-nova-media-library)

## Examples

![Manager](../assets/manager-1..gif)

![Field](../assets/mediable-field.gif)
