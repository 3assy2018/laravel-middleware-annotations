# Laravel Middleware Annotations

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]


## Documantation

- [Installation](#installation)
- [Usage](#usage)
- [Middleware Annotations](#middleware_annotations)
- [Integration With Laratrust](#laratrust_integration)
- [Future Features](#future_features)

<a name="installation"></a>
## Installation

Via Composer

``` bash
$ composer require m3assy/laravelannotation
```

If you are developing your application using Laravel 5.5+, the service providers and facades are registered automatically.
If you are using lower versions add those lines to config/app.php

``` php
'providers' => [
    // Service Providers
    M3assy\LaravelAnnotations\LaravelAnnotationServiceProvider::class,
],
'aliases' => [
    // Facade Aliases
    'Annotation' => M3assy\LaravelAnnotations\Facades\Annotation::class,
],
```

<a name="usage"></a>
## Usage

Now, You don't need to add any trait, the package register a custom controller dispatcher which register middlewares under the hood

Example:

```php
<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

/**
 * Example For Middleware Class Annotation
 * @Auth
 */
class UserController extends Controller
{
    /**
     * Example For Middleware Method Annotation
     * @Auth
     * @ExampleAnnotationWithParameter("values") 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response();
    }
}
```

This package built-in `auth` middleware annotation so all what you need is to use annotation like so:
If you need to apply middleware over all controller then add you doc block over controller class, and if you need to specify a controller method with middleware then you can add your annotation above the method in its DocBlock.

if you have a middleware with parameters, so you can use the annotation with parentheses and the values syntax.

<a name="middleware_annotations"></a>
Creating Middleware Annotation is pretty easy, all what you need is to call our artisan `make:annotation` command like so:

```bash
  $ php artisan make:annotation Guest
```  

This will generate a file like the following:

```php
<?php

namespace App\Foundation\Annotations;

use M3assy\LaravelAnnotations\Foundation\Types\MiddlewareAnnotation;

/**
 * @Annotation
 */
class Guest extends MiddlewareAnnotation
{

}
```

If you have middleware parameters that required kind of validation then you can override the following method:

```php
<?php

namespace App\Foundation\Annotations;

use M3assy\LaravelAnnotations\Foundation\Types\MiddlewareAnnotation;

/**
 * @Annotation
 */
class Guest extends MiddlewareAnnotation
{
    /**
    * @return bool
    */
    public function validateGivenValue()
    {
        // Your Validation Logic Goes Here
    }
}
```

Notice: If the given value is invalid the engine will neglect this annotation.

You can also access internal controller properties and methods dynamically like so:

```php
<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

/**
 * Example For Middleware Class Annotation
 * @Auth
 */
class UserController extends Controller
{
    public $value;
    /**
     * Example For Middleware Method Annotation
     * @Auth
     * @ExampleAnnotationWithParameter("{$this->value}") 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response();
    }
}
```


<a name="laratrust_integration"></a>
## Laratrust Integration
This package is delivered with Implementation For `Laratrust ACL Annotations System`.

To start using roles and permissions all what you need is to use the magic annotations in your controllers like so:

```php
<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

/**
 * @Role("superadmin")
 */
class UserController extends Controller
{
    /**
     * @Permission("list-users|create-users") 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response();
    }
}
```

Feel free to use `Role` and `Permission` annotations on the class level and methods level like previous instructions.

#### Scanning New Roles And Permissions
If you have used any role or permission in its annotation and it does not exist in the acl tables and you need to add them, no need to use any ui or tinker or any type code.
Just use our artisan `scan:acl` Command and it will identify automatically what is new in your roles and permissions and detects its type and create new instance for it.
To start scan fire your command like following:

```bash
  $ php artisan scan:acl
```

and voila!, We finished :)

If you refreshed any route to a controller contains non-existing role or permission, you will find that it is created and applied.

<a name="future_features"></a>
## Future Features
- Implement middleware options.
- Implement Laratrust Teams Feature Annotation.
- Adding more built-in laravel middlewares.
- Implementing magic middleware features for developers to use.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email 3assy@diva-lab.com instead of using the issue tracker.

## Credits

- [Mohamed Mostafa][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/m3assy/laravelannotation.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/m3assy/laravelannotation.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/m3assy/laravelannotation/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/m3assy/laravelannotation
[link-downloads]: https://packagist.org/packages/m3assy/laravelannotation
[link-travis]: https://travis-ci.org/m3assy/laravelannotation
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/3assy2018
[link-contributors]: ../../contributors
