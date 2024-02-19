<?php

namespace Holoultek\Capabilities\Models;

use App\Models\User;
use Holoultek\Capabilities\Factories\CapabilityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Capability extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'methods' => 'array'
    ];

    protected static function newFactory()
    {
        return new CapabilityFactory;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'model', 'capabilities_able');
    }
}
