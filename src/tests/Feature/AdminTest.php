<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->createRoles();
    $this->venue  = $this->createVenueWithSections();
    $this->event  = $this->createEvent($this->venue);
});

// ─── Test 9: Admin Dashboard ───────────────────────────────────────────────────

test('super_admin can access filament admin panel', function () {
    $admin = User::factory()->create()->assignRole('super_admin');

    actingAs($admin)
        ->get('/admin')
        ->assertOk();
});

test('admin role can access filament admin panel', function () {
    $admin = User::factory()->create()->assignRole('admin');

    actingAs($admin)
        ->get('/admin')
        ->assertOk();
});

test('customer cannot access filament admin panel', function () {
    $customer = User::factory()->create()->assignRole('customer');

    actingAs($customer)
        ->get('/admin')
        ->assertForbidden();
});

test('guest is redirected to login when accessing admin panel', function () {
    get('/admin')
        ->assertRedirect(route('filament.admin.auth.login'));
});

test('validator can access admin panel', function () {
    $validator = User::factory()->create()->assignRole('validator');

    actingAs($validator)
        ->get('/admin')
        ->assertOk();
});

test('admin can view event resource', function () {
    $admin = User::factory()->create()->assignRole('super_admin');
    \Spatie\Permission\Models\Permission::create(['name' => 'view_any_event', 'guard_name' => 'web']);
    $admin->syncPermissions(\Spatie\Permission\Models\Permission::all());

    actingAs($admin)
        ->get('/admin/events')
        ->assertOk();
});

test('admin can view order resource', function () {
    $admin = User::factory()->create()->assignRole('super_admin');
    \Spatie\Permission\Models\Permission::create(['name' => 'view_any_order', 'guard_name' => 'web']);
    $admin->syncPermissions(\Spatie\Permission\Models\Permission::all());

    actingAs($admin)
        ->get('/admin/orders')
        ->assertOk();
});

test('admin can view ticket resource', function () {
    $admin = User::factory()->create()->assignRole('super_admin');
    \Spatie\Permission\Models\Permission::create(['name' => 'view_any_ticket', 'guard_name' => 'web']);
    $admin->syncPermissions(\Spatie\Permission\Models\Permission::all());

    actingAs($admin)
        ->get('/admin/tickets')
        ->assertOk();
});

test('admin can view venue resource', function () {
    $admin = User::factory()->create()->assignRole('super_admin');
    \Spatie\Permission\Models\Permission::create(['name' => 'view_any_venue', 'guard_name' => 'web']);
    $admin->syncPermissions(\Spatie\Permission\Models\Permission::all());

    actingAs($admin)
        ->get('/admin/venues')
        ->assertOk();
});
