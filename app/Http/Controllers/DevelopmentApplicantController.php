<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DevelopmentApplicantStoreRequest;
use App\Http\Requests\DevelopmentApplicantUpdateRequest;
use App\Http\Resources\DevelopmentApplicantResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\DevelopmentApplicantRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DevelopmentApplicantController extends Controller implements HasMiddleware
{

    private DevelopmentApplicantRepositoryInterface $developmentApplicantRepository;

    public function __construct(DevelopmentApplicantRepositoryInterface $developmentApplicantRepository)
    {
        $this->developmentApplicantRepository = $developmentApplicantRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public static function middleware()
    {
       return [
           new Middleware(PermissionMiddleware::using(['development-applicant-menu|development-applicant-list|development-applicant-create|development-applicant-edit|development-applicant-delete']),only: ['index','getAllPaginated' ,'store', 'show', 'update', 'destroy']),
           new Middleware(PermissionMiddleware::using(['development-applicant-create']),only: ['store']),
           new Middleware(PermissionMiddleware::using(['development-applicant-edit']),only: ['update']),
           new Middleware(PermissionMiddleware::using(['development-applicant-delete']),only: ['destroy']),
       ];
    }

    public function index(Request $request)
    {
         try {
            $developmentsApplicant = $this->developmentApplicantRepository->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'success', DevelopmentApplicantResource::collection($developmentsApplicant), 200);
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
        try {
            $developmentsApplicant = $this->developmentApplicantRepository->getAllPaginate(
                $request['search'] ?? null,
                $request['rowPerPage']
            );
            return ResponseHelper::jsonResponse(true, 'success', PaginateResource::make($developmentsApplicant, DevelopmentApplicantResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DevelopmentApplicantStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $developmentApplicant = $this->developmentApplicantRepository->create($request);
            return ResponseHelper::jsonResponse(true, 'data created', DevelopmentApplicantResource::make($developmentApplicant), 201);
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
            $developmentApplicant = $this->developmentApplicantRepository->getById($id);
            if(!$developmentApplicant) return ResponseHelper::jsonResponse(false, 'developmentApplicant not found', null, 404);
            return ResponseHelper::jsonResponse(true, 'success', DevelopmentApplicantResource::make($developmentApplicant), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
       }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DevelopmentApplicantUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $developmentApplicant = $this->developmentApplicantRepository->getById($id);
            if(!$developmentApplicant) return ResponseHelper::jsonResponse(false, 'developmentApplicant not found', null, 404);
            $developmentApplicant = $this->developmentApplicantRepository->update($id, $request);
            return ResponseHelper::jsonResponse(true, 'data updated', DevelopmentApplicantResource::make($developmentApplicant), 200);
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
            $developmentApplicant = $this->developmentApplicantRepository->getById($id);
            if(!$developmentApplicant) return ResponseHelper::jsonResponse(false, 'developmentApplicant not found', null, 404);
            $this->developmentApplicantRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'success', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
