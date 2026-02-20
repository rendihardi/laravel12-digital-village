<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DevelopmentSotreRequest;
use App\Http\Requests\DevelopmentUpdateRequest;
use App\Http\Resources\DevelopmentResurce;
use App\Http\Resources\PaginateResource;
use App\Interfaces\DevelopmentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DevelopmentController extends Controller implements HasMiddleware
{
    private DevelopmentRepositoryInterface $developmentRepository;

    public function __construct(DevelopmentRepositoryInterface $developmentRepository)
    {
        $this->developmentRepository = $developmentRepository;  
    }

    public static function middleware()
    {
       return [
           new Middleware(PermissionMiddleware::using(['development-menu|development-list|development-create|development-edit|development-delete']),only: ['index','getAllPaginated' ,'store', 'show', 'update', 'destroy']),
           new Middleware(PermissionMiddleware::using(['development-create']),only: ['store']),
           new Middleware(PermissionMiddleware::using(['development-edit']),only: ['update']),
           new Middleware(PermissionMiddleware::using(['development-delete']),only: ['destroy']),
       ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $developments = $this->developmentRepository->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'success', DevelopmentResurce::collection($developments), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getAllPaginated(Request $request){
        $request = $request->validate(
            [
                'search' => 'nullable|string',
                'rowPerPage' => 'required|integer'
            ]
        );
        try{
            $developments = $this->developmentRepository->getAllPaginate(
                $request['search'] ?? null,
                $request['rowPerPage']
            );
            return ResponseHelper::jsonResponse(true, 'success', PaginateResource::make($developments, DevelopmentResurce::class), 200);
        
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(DevelopmentSotreRequest $request)
    {
        $request = $request->validated();
        try {
            $development = $this->developmentRepository->create($request);
            return ResponseHelper::jsonResponse(true, 'data created', DevelopmentResurce::make($development), 201);
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
            $development = $this->developmentRepository->getById($id);
            return ResponseHelper::jsonResponse(true, 'success', DevelopmentResurce::make($development), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DevelopmentUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $development = $this->developmentRepository->getById($id);
            if (!$development) {
                return ResponseHelper::jsonResponse(false, 'development not found', null, 404);
            }
            $development = $this->developmentRepository->update($id, $request);
            return ResponseHelper::jsonResponse(true, 'data updated', DevelopmentResurce::make($development), 200);
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
            $this->developmentRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'data deleted', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
