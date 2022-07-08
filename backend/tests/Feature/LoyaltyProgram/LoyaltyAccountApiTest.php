<?php
namespace Tests\Feature\LoyaltyProgram;
use Tests\TestCase;
use App\Models\User;
use App\Models\Airline;
use App\Models\LoyaltyProgram\LoyaltyAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoyaltyAccountApiTest extends TestCase
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
        $this->getJson('/api/loyalty')->assertUnauthorized();
    }

    public function test_can_list_records(): void
    {
        LoyaltyAccount::factory()->count(3)->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/loyalty')
            ->assertOk()
            ->assertJsonStructure(['data', 'meta']);
    }

    public function test_can_create_record(): void
    {
        $payload = [
            'name' => 'Test LoyaltyAccount',
            'station_code' => 'ADD',
            'region' => 'AFR',
            'priority' => 7,
            'description' => 'Feature test create',
        ];
        $this->actingAs($this->user)
            ->postJson('/api/loyalty', $payload)
            ->assertCreated()
            ->assertJsonPath('data.name', 'Test LoyaltyAccount');
    }

    public function test_can_show_record(): void
    {
        $entity = LoyaltyAccount::factory()->create(['airline_id' => $this->airline->id, 'name' => 'Show Me']);
        $this->actingAs($this->user)
            ->getJson('/api/loyalty/'.$entity->id)
            ->assertOk()
            ->assertJsonPath('summary.name', 'Show Me');
    }

    public function test_can_update_record(): void
    {
        $entity = LoyaltyAccount::factory()->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->putJson('/api/loyalty/'.$entity->id, ['name' => 'Updated LoyaltyAccount', 'priority' => 9])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated LoyaltyAccount');
    }

    public function test_can_activate_and_deactivate(): void
    {
        $entity = LoyaltyAccount::factory()->create(['airline_id' => $this->airline->id, 'is_active' => false]);
        $this->actingAs($this->user)->postJson('/api/loyalty/'.$entity->id.'/activate')->assertOk();
        $this->actingAs($this->user)->postJson('/api/loyalty/'.$entity->id.'/deactivate', ['reason' => 'test'])->assertOk();
    }

    public function test_can_archive_record(): void
    {
        $entity = LoyaltyAccount::factory()->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->postJson('/api/loyalty/'.$entity->id.'/archive', ['reason' => 'obsolete'])
            ->assertOk();
    }

    public function test_statistics_endpoint(): void
    {
        LoyaltyAccount::factory()->count(5)->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/loyalty/statistics')
            ->assertOk()
            ->assertJsonStructure(['data' => ['total', 'active', 'inactive']]);
    }

    public function test_bulk_activate(): void
    {
        $ids = LoyaltyAccount::factory()->count(3)->create(['airline_id' => $this->airline->id])->pluck('id')->all();
        $this->actingAs($this->user)
            ->postJson('/api/loyalty/bulk', ['action' => 'activate', 'ids' => $ids])
            ->assertOk()
            ->assertJsonPath('result.affected', 3);
    }

    public function test_clone_record(): void
    {
        $entity = LoyaltyAccount::factory()->create(['airline_id' => $this->airline->id, 'name' => 'Original']);
        $this->actingAs($this->user)
            ->postJson('/api/loyalty/'.$entity->id.'/clone')
            ->assertCreated();
    }

    public function test_export_endpoint(): void
    {
        LoyaltyAccount::factory()->count(2)->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/loyalty/export')
            ->assertOk()
            ->assertJsonStructure(['data', 'count']);
    }

    public function test_timeline_endpoint(): void
    {
        $entity = LoyaltyAccount::factory()->create(['airline_id' => $this->airline->id]);
        $this->actingAs($this->user)
            ->getJson('/api/loyalty/'.$entity->id.'/timeline')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }
}
