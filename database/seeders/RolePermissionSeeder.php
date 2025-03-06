<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Create or update permissions
    $permissions = [
      // System permissions
      'project.systems.index',
      'project.systems.roles.index',
      'project.systems.roles.add',
      'project.systems.roles.edit',
      'project.systems.roles.remove',
      'project.systems.users.index',
      'project.systems.users.add',
      'project.systems.users.edit',
      'project.systems.users.remove',
      'project.systems.statuses.index',
      'project.systems.statuses.add',
      'project.systems.statuses.edit',
      'project.systems.statuses.remove',
      'project.systems.attachment',

      // Platform permissions
      'project.platform.products.index',
      'project.platform.products.add',
      'project.platform.products.edit',
      'project.platform.products.remove',
      'project.platform.orders.index',
      'project.platform.orders.add',
      'project.platform.orders.edit',
      'project.platform.orders.remove',
      'project.platform.orders.approve',
      'project.platform.orders.change-status',
    ];

    // Create permissions if they don't exist
    foreach ($permissions as $permission) {
      Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
    }

    // Define roles with their permissions
    $roles = [
      'admin' => $permissions, // Admin has all permissions

      'manager' => [
        // System permissions for manager
        'project.systems.attachment',

        // Platform permissions for manager
        'project.platform.products.index',
        'project.platform.products.add',
        'project.platform.products.edit',
        'project.platform.orders.index',
        'project.platform.orders.add',
        'project.platform.orders.edit',
        'project.platform.orders.approve',
        'project.platform.orders.change-status',
      ],

      'courier' => [
        // Limited permissions for courier
        'project.platform.orders.index',
        'project.platform.orders.change-status',
      ],

      'client' => [
        // Client permissions
        'project.platform.products.index',
        'project.platform.orders.index',
        'project.platform.orders.add',
      ],
    ];

    // Create or update roles and assign permissions
    foreach ($roles as $roleName => $rolePermissions) {
      // Find or create the role with the correct guard
      $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

      // Use Spatie's syncPermissions method which handles UUIDs correctly
      $role->syncPermissions($rolePermissions);
    }
  }
}
