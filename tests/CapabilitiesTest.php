<?php

use App\Models\User;
use Holoultek\Capabilities\Exceptions\CapabilityNotExistException;
use Holoultek\Capabilities\Models\Capability;
use Holoultek\Capabilities\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CapabilitiesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_capability_cannot_be_assigned_to_user_until_it_is_created()
    {
        $user = User::factory()->test()->create();

        $this->expectException(CapabilityNotExistException::class);
        $user->capabilityAttach('create users');
    }

    /** @test */
    public function a_capability_can_be_assigned_and_detached_to_user_directly()
    {
        $user = User::factory()->create();
        Capability::factory()->create();

        $user->capabilityAttach('create users');
        $this->assertTrue($user->hasCapability('create users'));

        $user->capabilityDetach('create users');
        $this->assertFalse($user->hasCapability('create users'));
    }

    /** @test */
    public function adding_capability_to_role()
    {
        $role = Role::factory()->admin()->create();
        Capability::factory()->create();

        $role->capabilityAttach('create users');
        $this->assertTrue($role->hasCapability('create users'));
    }

    /** @test */
    public function removing_capability_to_role()
    {
        $role = Role::factory()->admin()->create();
        Capability::factory()->create();

        $role->capabilityAttach('create users');
        $this->assertTrue($role->hasCapability('create users'));

        $role->capabilityDetach('create users');
        $this->assertFalse($role->hasCapability('create users'));
    }

}
