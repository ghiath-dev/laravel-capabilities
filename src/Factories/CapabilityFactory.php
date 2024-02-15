<?php

namespace Holoultek\Capabilities\Factories;

use Holoultek\Capabilities\Models\Capability;
use Illuminate\Database\Eloquent\Factories\Factory;

class CapabilityFactory extends Factory
{
    protected $model = Capability::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'create users',
            'controller' => 'User',
            'methods' => [
                'index'
            ],
        ];
    }
}
