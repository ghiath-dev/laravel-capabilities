<?php

namespace Holoultek\Capabilities\Traits;

use Exception;
use Holoultek\Capabilities\Exceptions\RoleNotExistException;
use Holoultek\Capabilities\Models\Role;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasRoles
{

    public function roles(): MorphToMany
    {
        return $this->morphToMany(Role::class, 'model', 'roles_able');
    }

    /**
     * @throws RoleNotExistException
     */
    public function roleAttachByName(string $roleName): void
    {
        $roleId = Role::where('name', $roleName)->first()?->id;
        if (is_null($roleId)) {
            throw new RoleNotExistException();
        }

        $this->roles()->attach([$roleId]);
    }

    /**
     * @throws RoleNotExistException
     */
    public function oneRoleAttach(int|string $role): void
    {
        if (is_int($role)) {
            $this->roles()->sync([$role]);
            return;
        }

        $this->roleAttachByName($role);
    }

    /**
     * @throws Exception
     */
    public function roleAttach(string|array|int $role): void
    {
        if (is_array($role)) {
            foreach ($role as $_role) {
                $this->oneRoleAttach($_role);
            }
            return;
        }
        $this->oneRoleAttach($role);
    }

    /**
     * @throws Exception
     */
    public function roleDetach(string $role): void
    {
        $roleId = Role::where('name', $role)->first()?->id;
        if (is_null($roleId)) {
            throw new RoleNotExistException();
        }

        $this->roles()->detach([$roleId]);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function ra(string $role): void
    {
        $this->roleAttach($role);
    }

    public function rd(string $role): void
    {
        $this->roleDetach($role);
    }
}
