<?php
namespace Tests\Feature\SeatSelection;
use Tests\TestCase;
use App\Models\User;
use App\Models\Airline;
use App\Models\SeatSelection\SeatMap;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeatMapApiTest extends TestCase
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
        $this->getJson('/api/seats')->assertUnauthorized();
    }

    public function test_can_list_records(): void
    {
        SeatMap::factory()->count(3)->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/seats')
            ->assertOk()
            ->assertJsonStructure(['data', 'meta']);
    }

    public function test_can_create_record(): void
    {
        $payload = [
            'name' => 'Test SeatMap',
            'station_code' => 'ADD',
            'region' => 'AFR',
            'priority' => 7,
            'description' => 'Feature test create',
        ];
        $this->actingAs($this->user)
            ->postJson('/api/seats', $payload)
            ->assertCreated()
            ->assertJsonPath('data.name', 'Test SeatMap');
    }

    public function test_can_show_record(): void
    {
        $entity = SeatMap::factory()->create(['airline_id' => $this->airline->id, 'name' => 'Show Me']);
        $this->actingAs($this->user)
            ->getJson('/api/seats/'.$entity->id)
            ->assertOk()
            ->assertJsonPath('summary.name', 'Show Me');
    }

    public function test_can_update_record(): void
    {
        $entity = SeatMap::factory()->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->putJson('/api/seats/'.$entity->id, ['name' => 'Updated SeatMap', 'priority' => 9])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated SeatMap');
    }

    public function test_can_activate_and_deactivate(): void
    {
        $entity = SeatMap::factory()->create(['airline_id' => $this->airline->id, 'is_active' => false]);
        $this->actingAs($this->user)->postJson('/api/seats/'.$entity->id.'/activate')->assertOk();
        $this->actingAs($this->user)->postJson('/api/seats/'.$entity->id.'/deactivate', ['reason' => 'test'])->assertOk();
    }

    public function test_can_archive_record(): void
    {
        $entity = SeatMap::factory()->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->postJson('/api/seats/'.$entity->id.'/archive', ['reason' => 'obsolete'])
            ->assertOk();
    }

    public function test_statistics_endpoint(): void
    {
        SeatMap::factory()->count(5)->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/seats/statistics')
            ->assertOk()
            ->assertJsonStructure(['data' => ['total', 'active', 'inactive']]);
    }

    public function test_bulk_activate(): void
    {
        $ids = SeatMap::factory()->count(3)->create(['airline_id' => $this->airline->id])->pluck('id')->all();
        $this->actingAs($this->user)
            ->postJson('/api/seats/bulk', ['action' => 'activate', 'ids' => $ids])
            ->assertOk()
            ->assertJsonPath('result.affected', 3);
    }

    public function test_clone_record(): void
    {
        $entity = SeatMap::factory()->create(['airline_id' => $this->airline->id, 'name' => 'Original']);
        $this->actingAs($this->user)
            ->postJson('/api/seats/'.$entity->id.'/clone')
            ->assertCreated();
    }

    public function test_export_endpoint(): void
    {
        SeatMap::factory()->count(2)->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/seats/export')
            ->assertOk()
            ->assertJsonStructure(['data', 'count']);
    }

    public function test_timeline_endpoint(): void
    {
        $entity = SeatMap::factory()->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/seats/'.$entity->id.'/timeline')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }
}
