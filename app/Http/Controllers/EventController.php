<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventRepositoryInterface;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;
use Spatie\Permission\Middleware\PermissionMiddleware;

use function Illuminate\Log\log;

class EventController extends Controller implements HasMiddleware
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['event-menu|event-list|event-create|event-edit|event-delete']),only: ['index','getAllPaginated' ,'store', 'show', 'update', 'destroy']),
            new Middleware(PermissionMiddleware::using(['event-create']),only: ['store']),
            new Middleware(PermissionMiddleware::using(['event-edit']),only: ['update']),
            new Middleware(PermissionMiddleware::using(['event-delete']),only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        try{
            $Events = $this->eventRepository->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'success', EventResource::collection($Events), 200);
        
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate(
            [
                'search' => 'nullable|string',
                'rowPerPage' => 'required|integer'
            ]
        );
        try{
            $Events = $this->eventRepository->getAllPaginate(
                $request['search'] ?? null,
                $request['rowPerPage']
            );
            return ResponseHelper::jsonResponse(true, 'success', PaginateResource::make($Events, EventResource::class), 200);
        
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $event = $this->eventRepository->create($request->all());
            DB::commit();
            return ResponseHelper::jsonResponse(true, 'success', EventResource::make($event), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $event = $this->eventRepository->getById($id);
            return ResponseHelper::jsonResponse(true, 'success', EventResource::make($event), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $Events=$this->eventRepository->getById($id);
            FacadesLog::info($Events);
            if(!$Events) return ResponseHelper::jsonResponse(false, 'event not found', null, 404);
            $event = $this->eventRepository->update($id, $request);
            return ResponseHelper::jsonResponse(true, 'success', EventResource::make($event), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
           $events = $this->eventRepository->getById($id);
           if(!$events) return ResponseHelper::jsonResponse(false, 'event not found', null, 404);
            $this->eventRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'success', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
