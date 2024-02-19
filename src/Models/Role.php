<?php

namespace Holoultek\Capabilities\Models;

use App\Models\User;
use Holoultek\Capabilities\Factories\RoleFactory;
use Holoultek\Capabilities\Traits\HasCapabilities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Role extends Model
{
    use HasFactory, HasCapabilities;

    protected $guarded = [];

    protected static function newFactory()
    {
        return new RoleFactory;
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'model', 'roles_able');
    }
}
