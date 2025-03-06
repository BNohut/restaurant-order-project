<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Create Admin User
    $adminUser = User::firstOrCreate(
      ['email' => 'admin@example.com'],
      [
        'name' => 'Admin User',
        'phone' => '5555555555',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
      ]
    );
    $adminUser->assignRole('admin');
    $this->createAddressForUser($adminUser);

    // Create Manager User
    $managerUser = User::firstOrCreate(
      ['email' => 'manager@example.com'],
      [
        'name' => 'Manager User',
        'phone' => '6666666666',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
      ]
    );
    $managerUser->assignRole('manager');
    $this->createAddressForUser($managerUser);

    // Create Courier User
    $courierUser = User::firstOrCreate(
      ['email' => 'courier@example.com'],
      [
        'name' => 'Courier User',
        'phone' => '7777777777',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
      ]
    );
    $courierUser->assignRole('courier');
    $this->createAddressForUser($courierUser);

    // Create Client User
    $clientUser = User::firstOrCreate(
      ['email' => 'client@example.com'],
      [
        'name' => 'Client User',
        'phone' => '8888888888',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
      ]
    );
    $clientUser->assignRole('client');
    $this->createAddressForUser($clientUser);

    // Create 10 more client users
    for ($i = 1; $i <= 2; $i++) {
      $user = User::firstOrCreate(
        ['email' => "client{$i}@example.com"],
        [
          'name' => "Client User {$i}",
          'phone' => '999999999' . $i,
          'password' => Hash::make('password'),
          'email_verified_at' => now(),
        ]
      );
      $user->assignRole('client');
      $this->createAddressForUser($user);

      // Add a second address for some users (40% chance)
      if (rand(1, 10) <= 4) {
        $this->createAddressForUser($user, false, 'Work');
      }
    }
  }

  /**
   * Create a new address for a user
   */
  private function createAddressForUser(User $user, bool $isDefault = true, string $title = 'Home'): void
  {
    // Cities and districts in Turkey
    $cities = ['Istanbul', 'Ankara', 'Izmir', 'Antalya', 'Bursa'];
    $districts = [
      'Istanbul' => ['Kadikoy', 'Besiktas', 'Sisli', 'Uskudar', 'Fatih'],
      'Ankara' => ['Cankaya', 'Kecioren', 'Yenimahalle', 'Mamak', 'Etimesgut'],
      'Izmir' => ['Konak', 'Karsiyaka', 'Bornova', 'Buca', 'Cigli'],
      'Antalya' => ['Muratpasa', 'Konyaalti', 'Kepez', 'Lara', 'Alanya'],
      'Bursa' => ['Nilufer', 'Osmangazi', 'Yildirim', 'Mudanya', 'Gemlik'],
    ];

    // Pick a random city
    $city = $cities[array_rand($cities)];

    // Pick a random district for the selected city
    $district = $districts[$city][array_rand($districts[$city])];

    // Create street name
    $streets = ['Main', 'Oak', 'Pine', 'Maple', 'Cedar', 'Elm', 'Park', 'Lake', 'River', 'Mountain'];
    $streetName = $streets[array_rand($streets)];

    // Check if user already has an address with this title
    $existingAddress = Address::where('user_uuid', $user->uuid)
      ->where('title', $title)
      ->first();

    if (!$existingAddress) {
      // Create the address only if it doesn't exist
      Address::create([
        'user_uuid' => $user->uuid,
        'title' => $title,
        'address_line1' => rand(1, 100) . ' ' . $streetName . ' Street',
        'address_line2' => rand(0, 1) ? 'Apt ' . rand(1, 50) : null,
        'city' => $city,
        'district' => $district,
        'country' => 'Turkey',
        'postal_code' => rand(10000, 99999),
        'is_default' => $isDefault
      ]);
    }
  }
}
