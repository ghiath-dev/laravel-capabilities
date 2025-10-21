<?php

namespace Holoultek\Capabilities\Facades;

use Holoultek\Capabilities\Services\UserContextManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void initialize()
 * @method static bool hasRole(string|array $roles)
 * @method static bool hasAnyRole(array $roles)
 * @method static bool hasAllRoles(array $roles)
 * @method static bool hasCapability(string $capability)
 * @method static bool isChief()
 * @method static string|null getUserId()
 * @method static array getRoles()
 * @method static array|string getCapabilities()
 * @method static array dump()
 * @method static void clear()
 * @method static bool isInitialized()
 *
 * @see UserContextManager
 */
class CapabilitiesContext extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UserContextManager::class;
    }
}
