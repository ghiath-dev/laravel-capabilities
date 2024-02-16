# Laravel Capabilities

A laravel package to add roles and capabilities functionality to your auth models.

## Installation

```
composer require holoultek/laravel-capabilities
```

Then you have to publish the config files for roles and capabities

```
php artisan vendor:publish --provider="Holoultek\Capabilities\CapabilitiesServiceProvider"
```

This will publish 2 files:
### 1. capabilities.php
```php
<?php

return [
    'ControllerNameWithoutControllerWord' => [
        'capability name' => ['methods', 'as', 'array'],
    ],
];
```
The capabilities are auto discovered via this config files; The key will be your controller name with 'Controller' word.

And the values will be an indexed array which descrbe the capability name as index and the methods in your controller as array for the value.

For Example:
```php
    'Dashboard' => [
        'show dashboard' => ['show'],
    ],
```

This describe a "show dashboard" capability which controlled by "DashboardController"", the "show" method

### 2. roles.php
```php
<?php

return [
    'admin' => [
       'show dashboard'
    ],
];
```
You can provide here all the roles for your application as keys and its value will be list of all capabilities defined in the previous step.


### Final Step: 'capability' is the middleware.
As a final step you should add the access controlled middleware to your route

```php
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth', 'capability');
```

or as group
```php
Route::middleware(['auth', 'capability'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'show'])->name('dashboard');
});
```

## Badges

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## Contact

If you have any questions or concerns, feel free to reach out to me at ghiath.dev@gmail.com
