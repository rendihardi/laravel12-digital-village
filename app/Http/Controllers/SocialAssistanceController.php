<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SocialAssistanceRequest;
use App\Http\Requests\SocialAssistanceUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SocialAssistanceResource;
use App\Interfaces\SocialAssistanceRepositoryInterface;
use Illuminate\Http\Request;

class SocialAssistanceController extends Controller
{

    private SocialAssistanceRepositoryInterface $SocialAssistanceRepository;
    public function __construct(SocialAssistanceRepositoryInterface $SocialAssistanceRepository) 
    {
        $this->SocialAssistanceRepository = $SocialAssistanceRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        try {
            $SocialAssistance = $this->SocialAssistanceRepository->getAll(
                $request->search,
                $request->limit,
                true,
            );
            return ResponseHelper::jsonResponse(true, 'success', SocialAssistanceResource::collection($SocialAssistance), 200);
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
            $SocialAssistance = $this->SocialAssistanceRepository->getAllPaginate(
                $request['search'] ?? null,
                $request['rowPerPage']
            );
            return ResponseHelper::jsonResponse(true, 'success', PaginateResource::make($SocialAssistance, SocialAssistanceResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
            
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SocialAssistanceRequest $request)
    {
        $request = $request->validated();
        try{
            $SocialAssistance = $this->SocialAssistanceRepository->create($request);
            return ResponseHelper::jsonResponse(true, 'data created', new SocialAssistanceResource($SocialAssistance), 201);
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $SocialAssistance = $this->SocialAssistanceRepository->getById($id);
            if(!$SocialAssistance) return ResponseHelper::jsonResponse(false, 'SocialAssistance not found', null, 404);
            return ResponseHelper::jsonResponse(true, 'detail SocialAssistance', new SocialAssistanceResource($SocialAssistance), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SocialAssistanceUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $SocialAssistance = $this->SocialAssistanceRepository->getById($id);
            if(!$SocialAssistance) return ResponseHelper::jsonResponse(false, 'SocialAssistance not found', null, 404);
            $SocialAssistance = $this->SocialAssistanceRepository->update(
                $id,
                $request
            );
            return ResponseHelper::jsonResponse(true, 'data updated', new SocialAssistanceResource($SocialAssistance), 200);
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
            $SocialAssistance = $this->SocialAssistanceRepository->getById($id);
            if(!$SocialAssistance) return ResponseHelper::jsonResponse(false, 'SocialAssistance not found', null, 404);
            $SocialAssistance = $this->SocialAssistanceRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'data deleted', new SocialAssistanceResource($SocialAssistance), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
