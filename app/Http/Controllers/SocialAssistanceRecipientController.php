<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SocialAssistanceRecipientStoreRequest;
use App\Http\Requests\SocialAssistanceRecipientUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SocialAssistanceRecipientResource;
use App\Interfaces\SocialAssistanceRecipientRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class SocialAssistanceRecipientController extends Controller implements HasMiddleware
{
    private SocialAssistanceRecipientRepositoryInterface $SocialAssistanceRecipientRepository;

    public function __construct(SocialAssistanceRecipientRepositoryInterface $SocialAssistanceRecipientRepository)
    {
        $this->SocialAssistanceRecipientRepository= $SocialAssistanceRecipientRepository;
    }

    public static function middleware()
    {
       return [
           new Middleware(PermissionMiddleware::using(['social-assistance-recipient-menu|social-assistance-recipient-list|social-assistance-recipient-create|social-assistance-recipient-edit|social-assistance-recipient-delete']),only: ['index','getAllPaginated' ,'store', 'show', 'update', 'destroy']),
           new Middleware(PermissionMiddleware::using(['social-assistance-recipient-create']),only: ['store']),
           new Middleware(PermissionMiddleware::using(['social-assistance-recipient-edit']),only: ['update']),
           new Middleware(PermissionMiddleware::using(['social-assistance-recipient-delete']),only: ['destroy']),
       ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $SocialAssistance = $this->SocialAssistanceRecipientRepository->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'success', SocialAssistanceRecipientResource::collection($SocialAssistance), 200);
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            )
            ;
        }
    }

    public function getAllPaginated(Request $request){
        try {
            $request = $request->validate(
                [
                    'search' => 'nullable|string',
                    'rowPerPage' => 'required|integer'
                ]
            );
            $SocialAssistance = $this->SocialAssistanceRecipientRepository->getAllPaginate(
                $request['search'] ?? null,
                $request['rowPerPage']
            );
            return ResponseHelper::jsonResponse(true, 'success', PaginateResource::make($SocialAssistance, SocialAssistanceRecipientResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SocialAssistanceRecipientStoreRequest $request)
    {
           $request= $request->validated();
        try{
            $SocialAssistance = $this->SocialAssistanceRecipientRepository->create($request);
            return ResponseHelper::jsonResponse(true, 'data created', new SocialAssistanceRecipientResource($SocialAssistance), 201);
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            )
            ;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $SocialAssistance = $this->SocialAssistanceRecipientRepository->getById($id);
            if(!$SocialAssistance) {
            return ResponseHelper::jsonResponse(false, 'SocialAssistanceRecipient not found', null, 404);
            }
            return ResponseHelper::jsonResponse(true, 'success', new SocialAssistanceRecipientResource($SocialAssistance), 200);
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            )
            ;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SocialAssistanceRecipientUpdateRequest $request, string $id)
    {
        $request= $request->validated();
        try{
            $socialAssistanceRecipient = $this->SocialAssistanceRecipientRepository->getById($id);
            if(!$socialAssistanceRecipient) {
            return ResponseHelper::jsonResponse(false, 'SocialAssistanceRecipient not found', null, 404);
            }
            $socialAssistanceRecipient = $this->SocialAssistanceRecipientRepository->update($id, $request);
            return ResponseHelper::jsonResponse(true, 'data updated', new SocialAssistanceRecipientResource($socialAssistanceRecipient), 200);
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            )
            ;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $this->SocialAssistanceRecipientRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'data deleted', null, 200);
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            )
            ;
        }
    }
}
