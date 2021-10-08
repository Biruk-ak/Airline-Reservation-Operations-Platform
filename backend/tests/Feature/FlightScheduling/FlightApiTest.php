<?php
namespace Tests\Feature\FlightScheduling;
use Tests\TestCase;
use App\Models\User;
use App\Models\Airline;
use App\Models\FlightScheduling\Flight;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlightApiTest extends TestCase
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
        $this->getJson('/api/flights')->assertUnauthorized();
    }

    public function test_can_list_records(): void
    {
        Flight::factory()->count(3)->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/flights')
            ->assertOk()
            ->assertJsonStructure(['data', 'meta']);
    }

    public function test_can_create_record(): void
    {
        $payload = [
            'name' => 'Test Flight',
            'station_code' => 'ADD',
            'region' => 'AFR',
            'priority' => 7,
            'description' => 'Feature test create',
        ];
        $this->actingAs($this->user)
            ->postJson('/api/flights', $payload)
            ->assertCreated()
            ->assertJsonPath('data.name', 'Test Flight');
    }

    public function test_can_show_record(): void
    {
        $entity = Flight::factory()->create(['airline_id' => $this->airline->id, 'name' => 'Show Me']);
        $this->actingAs($this->user)
            ->getJson('/api/flights/'.$entity->id)
            ->assertOk()
            ->assertJsonPath('summary.name', 'Show Me');
    }

    public function test_can_update_record(): void
    {
        $entity = Flight::factory()->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->putJson('/api/flights/'.$entity->id, ['name' => 'Updated Flight', 'priority' => 9])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Flight');
    }

    public function test_can_activate_and_deactivate(): void
    {
        $entity = Flight::factory()->create(['airline_id' => $this->airline->id, 'is_active' => false]);
        $this->actingAs($this->user)->postJson('/api/flights/'.$entity->id.'/activate')->assertOk();
        $this->actingAs($this->user)->postJson('/api/flights/'.$entity->id.'/deactivate', ['reason' => 'test'])->assertOk();
    }

    public function test_can_archive_record(): void
    {
        $entity = Flight::factory()->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->postJson('/api/flights/'.$entity->id.'/archive', ['reason' => 'obsolete'])
            ->assertOk();
    }

    public function test_statistics_endpoint(): void
    {
        Flight::factory()->count(5)->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/flights/statistics')
            ->assertOk()
            ->assertJsonStructure(['data' => ['total', 'active', 'inactive']]);
    }

    public function test_bulk_activate(): void
    {
        $ids = Flight::factory()->count(3)->create(['airline_id' => $this->airline->id])->pluck('id')->all();
        $this->actingAs($this->user)
            ->postJson('/api/flights/bulk', ['action' => 'activate', 'ids' => $ids])
            ->assertOk()
            ->assertJsonPath('result.affected', 3);
    }

    public function test_clone_record(): void
    {
        $entity = Flight::factory()->create(['airline_id' => $this->airline->id, 'name' => 'Original']);
        $this->actingAs($this->user)
            ->postJson('/api/flights/'.$entity->id.'/clone')
            ->assertCreated();
    }

    public function test_export_endpoint(): void
    {
        Flight::factory()->count(2)->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/flights/export')
            ->assertOk()
            ->assertJsonStructure(['data', 'count']);
    }

    public function test_timeline_endpoint(): void
    {
        $entity = Flight::factory()->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/flights/'.$entity->id.'/timeline')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }
}
