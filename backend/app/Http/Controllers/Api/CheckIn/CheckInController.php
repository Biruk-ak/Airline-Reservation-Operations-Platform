<?php

namespace App\Http\Controllers\Api\CheckIn;

use App\Http\Controllers\Controller;
use App\Models\CheckIn\CheckIn;
use App\Services\CheckIn\CheckInService;
use App\Http\Requests\CheckIn\StoreCheckInRequest;
use App\Http\Requests\CheckIn\UpdateCheckInRequest;
use App\Http\Requests\CheckIn\SearchCheckInRequest;
use App\Http\Requests\CheckIn\BulkCheckInRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckInController extends Controller
{
    public function __construct(private CheckInService $service)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('airline.scope');
    }

    public function index(SearchCheckInRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $filters['airline_id'] = $request->attributes->get('airline_id');
        $paginator = $this->service->search($filters, (int) $request->get('per_page', 25));

        return response()->json([
            'data' => collect($paginator->items())->map->toOperationalSummary(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function store(StoreCheckInRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $payload['airline_id'] = $request->attributes->get('airline_id');
        $payload['created_by'] = $request->user()->id;
        $payload['updated_by'] = $request->user()->id;

        $entity = $this->service->create($payload);

        return response()->json([
            'message' => 'CheckIn created successfully',
            'data' => $entity->toOperationalSummary(),
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $entity = $this->service->findForAirline($id, $request->attributes->get('airline_id'));
        if (!$entity) {
            return response()->json(['message' => 'CheckIn not found'], 404);
        }

        return response()->json([
            'data' => $entity,
            'summary' => $entity->toOperationalSummary(),
            'constraints' => $entity->validateOperationalConstraints(),
        ]);
    }

    public function update(UpdateCheckInRequest $request, int $id): JsonResponse
    {
        $entity = $this->service->findForAirline($id, $request->attributes->get('airline_id'));
        if (!$entity) {
            return response()->json(['message' => 'CheckIn not found'], 404);
        }

        $payload = $request->validated();
        $payload['updated_by'] = $request->user()->id;
        $updated = $this->service->update($entity, $payload);

        return response()->json([
            'message' => 'CheckIn updated successfully',
            'data' => $updated->toOperationalSummary(),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $entity = $this->service->findForAirline($id, $request->attributes->get('airline_id'));
        if (!$entity) {
            return response()->json(['message' => 'CheckIn not found'], 404);
        }

        $this->service->delete($entity, $request->user()->id);

        return response()->json(['message' => 'CheckIn deleted successfully']);
    }

    public function activate(Request $request, int $id): JsonResponse
    {
        $entity = $this->service->findForAirline($id, $request->attributes->get('airline_id'));
        if (!$entity) {
            return response()->json(['message' => 'CheckIn not found'], 404);
        }
        $entity->updated_by = $request->user()->id;
        $entity->activate();
        return response()->json(['data' => $entity->toOperationalSummary()]);
    }

    public function deactivate(Request $request, int $id): JsonResponse
    {
        $entity = $this->service->findForAirline($id, $request->attributes->get('airline_id'));
        if (!$entity) {
            return response()->json(['message' => 'CheckIn not found'], 404);
        }
        $reason = $request->input('reason');
        $entity->updated_by = $request->user()->id;
        $entity->deactivate($reason);
        return response()->json(['data' => $entity->toOperationalSummary()]);
    }

    public function archive(Request $request, int $id): JsonResponse
    {
        $entity = $this->service->findForAirline($id, $request->attributes->get('airline_id'));
        if (!$entity) {
            return response()->json(['message' => 'CheckIn not found'], 404);
        }
        $entity->updated_by = $request->user()->id;
        $entity->archive($request->input('reason'));
        return response()->json(['data' => $entity->toOperationalSummary()]);
    }

    public function bulk(BulkCheckInRequest $request): JsonResponse
    {
        $airlineId = $request->attributes->get('airline_id');
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        $result = DB::transaction(function () use ($action, $ids, $airlineId, $request) {
            return $this->service->bulkAction($action, $ids, $airlineId, $request->user()->id, $request->input('payload', []));
        });

        return response()->json(['message' => 'Bulk action completed', 'result' => $result]);
    }

    public function syncExternal(Request $request, int $id): JsonResponse
    {
        $entity = $this->service->findForAirline($id, $request->attributes->get('airline_id'));
        if (!$entity) {
            return response()->json(['message' => 'CheckIn not found'], 404);
        }
        $entity->updated_by = $request->user()->id;
        $synced = $entity->syncFromExternal($request->all());
        Log::info('CheckIn external sync', ['id' => $id, 'user' => $request->user()->id]);
        return response()->json(['data' => $synced->toOperationalSummary()]);
    }

    public function export(SearchCheckInRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $filters['airline_id'] = $request->attributes->get('airline_id');
        $rows = $this->service->export($filters);
        return response()->json(['data' => $rows, 'count' => count($rows)]);
    }

    public function statistics(Request $request): JsonResponse
    {
        $airlineId = $request->attributes->get('airline_id');
        return response()->json(['data' => $this->service->statistics($airlineId)]);
    }

    public function timeline(Request $request, int $id): JsonResponse
    {
        $entity = $this->service->findForAirline($id, $request->attributes->get('airline_id'));
        if (!$entity) {
            return response()->json(['message' => 'CheckIn not found'], 404);
        }
        return response()->json(['data' => $this->service->timeline($entity)]);
    }

    public function cloneRecord(Request $request, int $id): JsonResponse
    {
        $entity = $this->service->findForAirline($id, $request->attributes->get('airline_id'));
        if (!$entity) {
            return response()->json(['message' => 'CheckIn not found'], 404);
        }
        $clone = $this->service->cloneRecord($entity, $request->user()->id, $request->input('overrides', []));
        return response()->json(['data' => $clone->toOperationalSummary()], 201);
    }
}
