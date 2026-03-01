<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventParticipantStoreRequest;
use App\Http\Requests\EventParticipantUpdateRequest;
use App\Http\Resources\EventParticipantResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventParticipantRepositoryInterFace;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventParticipantController extends Controller implements HasMiddleware
{
    private EventParticipantRepositoryInterFace $eventParticipantRepository;

    public function __construct(EventParticipantRepositoryInterFace $eventParticipantRepository)
    {
        $this->eventParticipantRepository = $eventParticipantRepository;
    }

    public static function middleware()
    {
       return [
           new Middleware(PermissionMiddleware::using(['event-participant-menu|event-participant-list|event-participant-create|event-participant-edit|event-participant-delete']),only: ['index','getAllPaginated' ,'store', 'show', 'update', 'destroy']),
           new Middleware(PermissionMiddleware::using(['event-participant-create']),only: ['store']),
           new Middleware(PermissionMiddleware::using(['event-participant-edit']),only: ['update']),
           new Middleware(PermissionMiddleware::using(['event-participant-delete']),only: ['destroy']),
       ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $eventParticipants = $this->eventParticipantRepository->getAll(
                $request->search,
                $request->limit,
                true,
                $request->status,);
            return ResponseHelper::jsonResponse(true, 'success', EventParticipantResource::collection($eventParticipants), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
    
    public function getAllPaginated(Request $request){
        $request = $request->validate(
            [
                'search' => 'nullable|string',
                'rowPerPage' => 'required|integer',
                'status' => 'nullable|string'
            ]
        );
        try{
            $eventParticipants = $this->eventParticipantRepository->getAllPaginate(
                $request['search'] ?? null,
                $request['rowPerPage'],
                $request['status'] ?? null
            );
            return ResponseHelper::jsonResponse(true, 'success', PaginateResource::make($eventParticipants, EventParticipantResource::class), 200);
        
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }           
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(EventParticipantStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $eventParticipant = $this->eventParticipantRepository->create($request);
            return ResponseHelper::jsonResponse(true, 'data created', EventParticipantResource::make($eventParticipant), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $eventParticipant = $this->eventParticipantRepository->getById($id);
            if(!$eventParticipant) return ResponseHelper::jsonResponse(false, 'eventParticipant not found', null, 404);
            return ResponseHelper::jsonResponse(true, 'success', EventParticipantResource::make($eventParticipant), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventParticipantUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $eventParticipant = $this->eventParticipantRepository->getById($id);
            if(!$eventParticipant) return ResponseHelper::jsonResponse(false, 'eventParticipant not found', null, 404);
            $eventParticipant = $this->eventParticipantRepository->update($id, $request);
            return ResponseHelper::jsonResponse(true, 'data updated', EventParticipantResource::make($eventParticipant), 200);
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
            $eventParticipant = $this->eventParticipantRepository->getById($id);
            if(!$eventParticipant) return ResponseHelper::jsonResponse(false, 'eventParticipant not found', null, 404);
            $this->eventParticipantRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'success', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
