<?php

namespace Holoultek\Capabilities\Traits;

use Exception;
use Holoultek\Capabilities\Exceptions\CapabilityNotExistException;
use Holoultek\Capabilities\Models\Capability;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasCapabilities
{

    public function capabilities(): MorphToMany
    {
        return $this->morphToMany(Capability::class, 'model', 'capabilities_able');
    }

    /**
     * @throws Exception
     */
    public function capabilityAttach(string $capability): void
    {
        $capabilityId = Capability::where('name', $capability)->first()?->id;
        if (is_null($capabilityId)) {
            throw new CapabilityNotExistException($capability);
        }

        $this->capabilities()->attach([$capabilityId]);
    }

    /**
     * @throws Exception
     */
    public function capabilityDetach(string $capability): void
    {
        $capabilityId = Capability::where('name', $capability)->first()?->id;
        if (is_null($capabilityId)) {
            throw new CapabilityNotExistException($capability);
        }

        $this->capabilities()->detach($capabilityId);
    }

    public function hasCapability(string $capability): bool
    {
        if (method_exists($this, 'isChief') && $this->isChief()) {
            return true;
        }

        if ($this->capabilities()->where('name', $capability)->exists()) {
            return true;
        }

        foreach ($this->roles ?? [] as $role) {
            if ($role->hasCapability($capability)) {
                return true;
            }
        }

        return false;
    }

    public function hasAbility(string $controller, string $method): bool
    {
        if ($this->isChief()) {
            return true;
        }

        if (!isset(config('capabilities')[$controller])) {
            return false;
        }

        foreach (config('capabilities')[$controller] as $capability_name => $methods) {
            if (in_array($method, $methods)) {
                break;
            }
        }

        return $this->hasCapability($capability_name);
    }

    // Shortcuts
    public function hc(string $capability)
    {
        return $this->hasCapability($capability);
    }

    public function ca(string $capability)
    {
        $this->capabilityAttach($capability);
    }

    public function cd(string $capability)
    {
        $this->capabilityDetach($capability);
    }

    public function scopeHasCapabilities(Builder $query, array $capabilities)
    {
        $query->whereHas('capabilities', function ($q) use ($capabilities) {
            $q->whereIn('name', $capabilities);
        })->orWhereHas('roles', function ($q) use ($capabilities) {
            $q->whereHas('capabilities', function ($q) use ($capabilities) {
                $q->whereIn('name', $capabilities);
            });
        });
    }

}
