<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // seed 10 admin users, with first one having admin email and admin password
        $this->seedAdmins();
        // end of seeding 10 admin users

        // seed 10 store users, with first one having store email and store password
        $this->seedStores();
        // end of seeding 10 store users

        // seed 10 users, with first one having 963 dial_code and 0968259851 number
        $this->seedUsers();
        // end of seeding 10 users

        // seed 10 drivers, with first having name and userName driver and password driver
        $this->seedDrivers();
        // end of seeding 10 drivers

    }

    public function seedAdmins(): void
    {
        User::factory()
            ->adminWithAdminCredentials()
            ->create();

        User::factory()
            ->count(9)
            ->admin()
            ->create();
    }

    public function seedUsers(): void
    {
        user::factory()
            ->userWithUserCredentials()
            ->has(Coupon::factory()->count(2)->testCoupon())
            ->has(Product::factory()->count(2), 'favouriteProducts')
            ->create();

        User::factory()
            ->count(9)
            ->user()
            ->has(Coupon::factory()->count(2))
            ->has(Product::factory()->count(2), 'favouriteProducts')
            ->create();
    }

    public function seedStores(): void
    {
        User::factory()
            ->storeUser()
            ->create();

        User::factory()
            ->count(9)
            ->storeUserWithStoreCredentials()
            ->create();
    }

    public function seedDrivers(): void
    {
        user::factory()
            ->driverWithDriverCredentials()
            ->create();

        User::factory()
            ->count(9)
            ->driver()
            ->create();
    }
}
