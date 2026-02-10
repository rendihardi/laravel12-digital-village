<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\HeadOfFamilyRequest;
use App\Http\Requests\HeadOfFamilyUpdateRequest;
use App\Http\Resources\HeadOfFamilyResource;
use App\Http\Resources\PaginateResource;
use App\Interface\HeadOfFamilyRepositoryInterface;
use Illuminate\Http\Request;

class HeadOfFamilyController extends Controller
{
    private HeadOfFamilyRepositoryInterface $headOfFamilyRepository;

    public function __construct(HeadOfFamilyRepositoryInterface $headOfFamilyRepository)
    {
        $this->headOfFamilyRepository = $headOfFamilyRepository;
    }   
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $headOfFamilies = $this->headOfFamilyRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, 'success', HeadOfFamilyResource::collection($headOfFamilies), 200);
        } catch (\Exception $e) {
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
        try {
            $headOfFamilies = $this->headOfFamilyRepository->getAllPaginate(
                $request['search'] ?? null,
                $request['rowPerPage']
            );

            return ResponseHelper::jsonResponse(true, 'success', PaginateResource::make($headOfFamilies, HeadOfFamilyResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HeadOfFamilyRequest $request)
    {
         $request = $request->validated();
        try {
            $user = $this->headOfFamilyRepository->create($request);
            return ResponseHelper::jsonResponse(true, 'data created', new HeadOfFamilyResource($user), 201);
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
            $headOfFamily = $this->headOfFamilyRepository->getById($id);
            if(!$headOfFamily) return ResponseHelper::jsonResponse(false, 'head of family not found', null, 404);
            return ResponseHelper::jsonResponse(true, 'detail head of family', new HeadOfFamilyResource($headOfFamily), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HeadOfFamilyUpdateRequest $request, string $id)
    {
        // dd($request);
        $request = $request->validated();
        try {
            $headOfFamily = $this->headOfFamilyRepository->getById($id);
            if(!$headOfFamily) return ResponseHelper::jsonResponse(false, 'head of family not found', null, 404);
            $headOfFamily = $this->headOfFamilyRepository->update(
                $id,
                $request
            );
            return ResponseHelper::jsonResponse(true, 'data updated', new HeadOfFamilyResource($headOfFamily), 200);
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
            $headOfFamily = $this->headOfFamilyRepository->getById($id);
            if(!$headOfFamily) return ResponseHelper::jsonResponse(false, 'head of family not found', null, 404);
            $headOfFamily = $this->headOfFamilyRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'data deleted', new HeadOfFamilyResource($headOfFamily), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
