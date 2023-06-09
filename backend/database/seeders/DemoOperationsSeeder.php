<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Airline;
use Illuminate\Support\Str;

class DemoOperationsSeeder extends Seeder
{
    public function run(): void
    {
        $airline = Airline::first();
        if (!$airline) return;

        $modules = [
            \App\Models\FlightScheduling\Flight::class,
            \App\Models\AircraftManagement\Aircraft::class,
            \App\Models\CrewScheduling\CrewMember::class,
            \App\Models\PassengerBooking\Booking::class,
            \App\Models\GateManagement\Gate::class,
            \App\Models\BaggageTracking\BaggageItem::class,
            \App\Models\Cargo\CargoShipment::class,
            \App\Models\Maintenance\MaintenanceWorkOrder::class,
        ];

        $stations = ['ADD', 'DXB', 'LHR', 'CDG', 'JFK', 'NBO', 'FRA'];
        $statuses = ['draft', 'active', 'scheduled', 'in_progress', 'pending_review', 'completed'];

        foreach ($modules as $model) {
            for ($i = 1; $i <= 20; $i++) {
                $model::create([
                    'airline_id' => $airline->id,
                    'status' => $statuses[$i % count($statuses)],
                    'code' => strtoupper(substr(class_basename($model), 0, 3)).'-'.str_pad((string)$i, 4, '0', STR_PAD_LEFT),
                    'name' => class_basename($model).' Demo '.$i,
                    'description' => 'Seeded demo record for operations platform',
                    'metadata' => ['seed' => true, 'batch' => 'demo'],
                    'created_by' => 1,
                    'updated_by' => 1,
                    'external_ref' => (string) Str::uuid(),
                    'effective_from' => now()->subDays($i),
                    'is_active' => $i % 2 === 0,
                    'priority' => ($i % 10) + 1,
                    'region' => ['AFR', 'EUR', 'MEA', 'NAM'][$i % 4],
                    'station_code' => $stations[$i % count($stations)],
                    'version' => 1,
                ]);
            }
        }
    }
}
