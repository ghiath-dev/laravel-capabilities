<?php


use App\Models\User;
use Holoultek\Capabilities\Exceptions\RoleNotExistException;
use Holoultek\Capabilities\Models\Capability;
use Holoultek\Capabilities\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_role_cannot_be_assigned_to_user_until_it_is_created()
    {
        $user = User::factory()->test()->create();

        $this->expectException(RoleNotExistException::class);
        $this->expectExceptionMessage('Role is not exist');
        $user->roleAttach('admin');
    }

    /** @test */
    public function a_role_can_be_assigned_to_user()
    {
        $user = User::factory()->test()->create();
        Role::factory()->admin()->create();

        $user->roleAttach('admin');
        $this->assertTrue($user->hasRole('admin'));
    }

    /** @test */
    public function a_role_with_whitespaces_can_be_assigned_to_user()
    {
        $user = User::factory()->test()->create();
        Role::factory()->admin()->create([
            'name' => 'admin user'
        ]);

        $user->roleAttach('admin user');
        $this->assertTrue($user->hasRole('admin user'));
    }

    /** @test */
    public function removing_role_from_user()
    {
        $user = User::factory()->test()->create();
        Role::factory()->admin()->create();

        $user->roleAttach('admin');
        $this->assertTrue($user->hasRole('admin'));

        $user->roleDetach('admin');
        $this->assertFalse($user->hasRole('admin'));
    }

    /** @test */
    public function a_capability_can_be_attached_to_role()
    {
        $role = Role::factory()->admin()->create();
        Capability::factory()->create();

        $role->capabilityAttach('create users');
        $this->assertTrue($role->hasCapability('create users'));

        $role->capabilityDetach('create users');
        $this->assertFalse($role->hasCapability('create users'));
    }
}
