<?php

namespace Holoultek\Capabilities\Facades;

use Holoultek\Capabilities\Services\UserContextManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool hasCapability(string $capability)
 * @method static bool hasRole(string|array $roles)
 * @method static bool hasAnyRole(array $roles)
 * @method static bool hasAllRoles(array $roles)
 * @method static bool isChief()
 * @method static string|null getUserId()
 * @method static void initialize()
 *
 * @see UserContextManager
 */
class UserContext extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UserContextManager::class;
    }
}
