<?php

namespace Holoultek\Capabilities\Services;

use Holoultek\Capabilities\Interface\AccessControlledUser;
use Illuminate\Support\Facades\Context;

class UserContextManager
{
    private const CTX_USER_ID = 'user_id';
    private const CTX_ROLES = 'roles';
    private const CTX_CAPABILITIES = 'capabilities';
    private const CTX_IS_CHIEF = 'is_chief';

    private const MANAGED_KEYS = [
        self::CTX_USER_ID,
        self::CTX_ROLES,
        self::CTX_CAPABILITIES,
        self::CTX_IS_CHIEF,
    ];

    /**
     * Initialize all user context for the current request
     */
    public static function initialize(?string $guard = null): void
    {
        $guard = $guard ?? config('auth.defaults.guard');

        if (
            !auth($guard)->check()
            || !method_exists(auth($guard)->user(), 'roles')
            || !method_exists(auth($guard)->user(), 'capabilities')
        ) {
            return;
        }

        $user = auth($guard)->user()->load(['roles.capabilities', 'capabilities']);

        static::setUserContext($user);
        static::setRolesContext($user);
        static::setCapabilitiesContext($user);
    }

    /**
     * Set user basic info in context
     */
    private static function setUserContext(AccessControlledUser $user): void
    {
        Context::add(self::CTX_USER_ID, $user->id);
        Context::add(self::CTX_IS_CHIEF, $user->isChief());
    }

    /**
     * Set user roles in context
     */
    private static function setRolesContext(AccessControlledUser $user): void
    {
        if (!Context::has(self::CTX_ROLES)) {
            $roles = $user->roles->pluck('name')->toArray();
            Context::add(self::CTX_ROLES, $roles);
        }
    }

    /**
     * Set user capabilities in context
     */
    private static function setCapabilitiesContext(AccessControlledUser $user): void
    {
        if (!Context::has(self::CTX_CAPABILITIES)) {
            if ($user->isChief()) {
                Context::add(self::CTX_CAPABILITIES, '*');
            } else {
                $capabilities = $user->capabilities->pluck('name')->toArray();

                foreach ($user->roles as $role) {
                    $roleCaps = $role->capabilities->pluck('name')->toArray();
                    $capabilities = array_merge($capabilities, $roleCaps);
                }

                Context::add(self::CTX_CAPABILITIES, array_values(array_unique($capabilities)));
            }
        }
    }

    /**
     * Check if user has specific role
     */
    public static function hasRole(string|array $roles): bool
    {
        $userRoles = static::getRoles();

        if (is_string($roles)) {
            return in_array($roles, $userRoles);
        }

        return !empty(array_intersect($roles, $userRoles));
    }

    /**
     * Check if user has any of the specified roles
     */
    public static function hasAnyRole(array $roles): bool
    {
        return static::hasRole($roles);
    }

    /**
     * Check if user has all specified roles
     */
    public static function hasAllRoles(array $roles): bool
    {
        $userRoles = static::getRoles();
        return empty(array_diff($roles, $userRoles));
    }

    /**
     * Check if user has specific capability
     */
    public static function hasCapability(string $capability): bool
    {
        $capabilities = static::getCapabilities();

        if ($capabilities === '*') {
            return true;
        }

        return in_array($capability, $capabilities);
    }

    public static function getRoles(): array
    {
        return Context::get(self::CTX_ROLES, []);
    }

    public static function getCapabilities(): array|string
    {
        return Context::get(self::CTX_CAPABILITIES, []);
    }

    /**
     * Check if user is chief
     */
    public static function isChief(): bool
    {
        return Context::get(self::CTX_IS_CHIEF, false);
    }

    /**
     * Get current user ID from context
     */
    public static function getUserId(): ?string
    {
        return Context::get(self::CTX_USER_ID);
    }

    /**
     * Clear all context managed by this class (useful for testing)
     */
    public static function clear(): void
    {
        foreach (self::MANAGED_KEYS as $key) {
            Context::forget($key);
        }
    }

    /**
     * Get all context data managed by this class (useful for debugging)
     */
    public static function dump(): array
    {
        $context = [];

        foreach (self::MANAGED_KEYS as $key) {
            $context[$key] = Context::get($key);
        }

        return $context;
    }

    public static function isInitialized(): bool
    {
        return Context::has(self::CTX_USER_ID);
    }
}
