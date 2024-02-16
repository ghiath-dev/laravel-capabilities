# Laravel Capabilities

A laravel package to add roles and capabilities functionality to your auth models.

## Installation

To use the package:
```
composer require holoultek/laravel-capabilities
```
### Migrate the database

```
php artisan migrate
```

### Then you have to publish the config files for roles and capabilities

```
php artisan vendor:publish --provider="Holoultek\Capabilities\CapabilitiesServiceProvider"
```

This will publish 2 files:
#### 1. capabilities.php
```php
<?php

return [
    'ControllerNameWithoutControllerWord' => [
        'capability name' => ['methods', 'as', 'array'],
    ],
];
```
The capabilities are auto discovered via this config files; The key will be your controller name without 'Controller' word.

And the values will be an indexed array which describe the capability name as index and the methods in your controller as array for the value.

For Example:
```php
'Dashboard' => [
    'show dashboard' => ['show'],
],
```

This describes a "show dashboard" capability which controlled by "DashboardController", the "show" method

#### 2. roles.php
```php
<?php

return [
    'admin' => [
       'show dashboard'
    ],
];
```
You can provide here all the roles for your application as keys and its value will be a list of all capabilities defined in the previous step.


### 'capability' is the middleware.
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

### Fill the tables
After editing the config files you can run these commands in sequence to create all roles and capabilities defined

```
php artisan make:capabilities
php artisan make:roles
```

### Attaching and detaching

#### To attach or detach a capability
```php
$auth->capabilityAttach('Capability Name');

$auth->capabilityDetach('Capability Name');

// or in short

$auth->ca('Capability Name');

$auth->cd('Capability Name');
```

#### To attach or detach a capability
```php
$auth->roleAttach('Role Name');

$auth->roleDetach('Role Name');

// or in short

$auth->ra('Role Name');

$auth->rd('Role Name');
```

#### To check if auth model has a capability or role
```php
$auth->hasCapability('Capability Name');

$auth->hasRole('Role Name');

// or in short

$auth->hc('Capability Name');

$auth->hr('Role Name');
```

## Badges

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## Contact

If you have any questions or concerns, feel free to reach out to me at ghiath.dev@gmail.com
