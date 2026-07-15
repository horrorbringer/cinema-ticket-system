<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        $admin = User::where('email', 'admin@cinema.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }

        $customer = User::where('email', 'customer@test.com')->first();
        if ($customer) {
            $customer->assignRole('customer');
        }

        $this->call(SampleDataSeeder::class);
    }
}
