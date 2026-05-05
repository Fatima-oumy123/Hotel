<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'rooms.view','rooms.create','rooms.edit','rooms.delete',
            'reservations.view','reservations.create','reservations.edit',
            'reservations.delete','reservations.checkin','reservations.checkout',
            'guests.view','guests.edit',
            'payments.view','payments.create','payments.refund',
            'invoices.view','invoices.create','invoices.download',
            'maintenance.view','maintenance.create','maintenance.edit','maintenance.delete',
            'employees.view','employees.create','employees.edit','employees.delete',
            'customers.view','customers.create','customers.edit','customers.delete',
            'inventory.view','inventory.manage',
            'restaurant_menu.view','restaurant_menu.manage',
            'restaurant_orders.view','restaurant_orders.manage',
            'employee_schedule.view','employee_schedule.manage',
            'restaurant.view','restaurant.manage',
            'expenses.view','expenses.create','expenses.edit','expenses.delete',
            'reports.view','reports.export',
            'users.view','users.create','users.edit','users.delete',
            'audit_logs.view','settings.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Manager — accès total
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions(Permission::all());

        // Réceptionniste
        $receptionist = Role::firstOrCreate(['name' => 'receptionist', 'guard_name' => 'web']);
        $receptionist->syncPermissions([
            'rooms.view',
            'reservations.view','reservations.create','reservations.edit',
            'reservations.checkin','reservations.checkout',
            'guests.view',
            'customers.view','customers.create','customers.edit',
            'payments.view','payments.create',
            'invoices.view','invoices.download',
            'maintenance.view','maintenance.create',
            'inventory.view','inventory.manage',
            'restaurant.view','restaurant.manage',
            'restaurant_menu.view','restaurant_menu.manage',
            'restaurant_orders.view','restaurant_orders.manage',
            'employee_schedule.view',
        ]);

        // RH
        $hr = Role::firstOrCreate(['name' => 'hr', 'guard_name' => 'web']);
        $hr->syncPermissions([
            'employees.view','employees.create','employees.edit',
            'employee_schedule.view','employee_schedule.manage',
            'reports.view',
        ]);

        // Créer admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@hotel.com'],
            [
                'name'      => 'Admin Hotel',
                'password'  => bcrypt('password123'),
                'is_active' => true,
            ]
        );
        $admin->syncRoles(['manager']);

        // Créer réceptionniste demo
        $recep = User::firstOrCreate(
            ['email' => 'reception@hotel.com'],
            [
                'name'      => 'Marie Réceptionniste',
                'password'  => bcrypt('password123'),
                'is_active' => true,
            ]
        );
        $recep->syncRoles(['receptionist']);

        // Créer RH demo
        $rh = User::firstOrCreate(
            ['email' => 'rh@hotel.com'],
            [
                'name'      => 'Jean RH',
                'password'  => bcrypt('password123'),
                'is_active' => true,
            ]
        );
        $rh->syncRoles(['hr']);

        $this->command->info('✅ Rôles, permissions et utilisateurs créés.');
        $this->command->table(
            ['Email', 'Rôle', 'Mot de passe'],
            [
                ['admin@hotel.com',     'Manager',        'password123'],
                ['reception@hotel.com', 'Réceptionniste', 'password123'],
                ['rh@hotel.com',        'RH',             'password123'],
            ]
        );
    }
}
