<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/');
});



// You might also want to test with an admin user if they have different dashboard access
test('authenticated admin users can visit the dashboard', function () {
    Role::firstOrCreate(['name' => 'admin']); // Ensure the 'admin' role exists

    $adminUser = User::factory()->create();
    $adminUser->assignRole('admin'); // Assign the 'admin' role

    $this->actingAs($adminUser)
        ->get('/dashboard')
        ->assertStatus(200);
});
