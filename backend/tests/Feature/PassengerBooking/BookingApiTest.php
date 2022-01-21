<?php
namespace Tests\Feature\PassengerBooking;
use Tests\TestCase;
use App\Models\User;
use App\Models\Airline;
use App\Models\PassengerBooking\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Airline $airline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->airline = Airline::factory()->create();
        $this->user = User::factory()->create([
            'airline_id' => $this->airline->id,
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_index_requires_auth(): void
    {
        $this->getJson('/api/bookings')->assertUnauthorized();
    }

    public function test_can_list_records(): void
    {
        Booking::factory()->count(3)->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/bookings')
            ->assertOk()
            ->assertJsonStructure(['data', 'meta']);
    }

    public function test_can_create_record(): void
    {
        $payload = [
            'name' => 'Test Booking',
            'station_code' => 'ADD',
            'region' => 'AFR',
            'priority' => 7,
            'description' => 'Feature test create',
        ];
        $this->actingAs($this->user)
            ->postJson('/api/bookings', $payload)
            ->assertCreated()
            ->assertJsonPath('data.name', 'Test Booking');
    }

    public function test_can_show_record(): void
    {
        $entity = Booking::factory()->create(['airline_id' => $this->airline->id, 'name' => 'Show Me']);
        $this->actingAs($this->user)
            ->getJson('/api/bookings/'.$entity->id)
            ->assertOk()
            ->assertJsonPath('summary.name', 'Show Me');
    }

    public function test_can_update_record(): void
    {
        $entity = Booking::factory()->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->putJson('/api/bookings/'.$entity->id, ['name' => 'Updated Booking', 'priority' => 9])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Booking');
    }

    public function test_can_activate_and_deactivate(): void
    {
        $entity = Booking::factory()->create(['airline_id' => $this->airline->id, 'is_active' => false]);
        $this->actingAs($this->user)->postJson('/api/bookings/'.$entity->id.'/activate')->assertOk();
        $this->actingAs($this->user)->postJson('/api/bookings/'.$entity->id.'/deactivate', ['reason' => 'test'])->assertOk();
    }

    public function test_can_archive_record(): void
    {
        $entity = Booking::factory()->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->postJson('/api/bookings/'.$entity->id.'/archive', ['reason' => 'obsolete'])
            ->assertOk();
    }

    public function test_statistics_endpoint(): void
    {
        Booking::factory()->count(5)->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/bookings/statistics')
            ->assertOk()
            ->assertJsonStructure(['data' => ['total', 'active', 'inactive']]);
    }

    public function test_bulk_activate(): void
    {
        $ids = Booking::factory()->count(3)->create(['airline_id' => $this->airline->id])->pluck('id')->all();
        $this->actingAs($this->user)
            ->postJson('/api/bookings/bulk', ['action' => 'activate', 'ids' => $ids])
            ->assertOk()
            ->assertJsonPath('result.affected', 3);
    }

    public function test_clone_record(): void
    {
        $entity = Booking::factory()->create(['airline_id' => $this->airline->id, 'name' => 'Original']);
        $this->actingAs($this->user)
            ->postJson('/api/bookings/'.$entity->id.'/clone')
            ->assertCreated();
    }

    public function test_export_endpoint(): void
    {
        Booking::factory()->count(2)->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/bookings/export')
            ->assertOk()
            ->assertJsonStructure(['data', 'count']);
    }

    public function test_timeline_endpoint(): void
    {
        $entity = Booking::factory()->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/bookings/'.$entity->id.'/timeline')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }
}
