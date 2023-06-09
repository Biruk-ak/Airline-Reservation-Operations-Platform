<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Airline;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $airline = Airline::create([
            'name' => 'Demo Air',
            'iata_code' => 'DA',
            'icao_code' => 'DEM',
            'country' => 'ET',
            'hq_airport' => 'ADD',
            'timezone' => 'Africa/Addis_Ababa',
            'is_active' => true,
            'loyalty_program_name' => 'DemoMiles',
            'branding' => ['primary' => '#0B3D91', 'accent' => '#C8102E'],
            'settings' => ['default_currency' => 'USD', 'checkin_window_hours' => 24],
        ]);

        User::create([
            'name' => 'Biruk Ak',
            'email' => 'birukaklilu0110@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'airline_id' => $airline->id,
            'station_code' => 'ADD',
            'employee_number' => 'EMP-0001',
            'department' => 'Technology',
            'is_active' => true,
        ]);

        $this->call([
            DemoOperationsSeeder::class,
        ]);
    }
}
