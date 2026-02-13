<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ProfileStoreRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Interfaces\ProfileRepositoryInterFace;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    private ProfileRepositoryInterFace $profileRepository;

        public function __construct(ProfileRepositoryInterFace $profileRepository)
        {
            $this->profileRepository = $profileRepository;
        }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $profile = $this->profileRepository->getProfile();
            if(!$profile){
                return ResponseHelper::jsonResponse(false, 'Profile not found', null, 404);
            }
            return ResponseHelper::jsonResponse(true, 'success', new ProfileResource($profile), 200);
        
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfileStoreRequest $request)
    {
        $request=$request->validated();
        try{
            $this->profileRepository->create($request);
            return ResponseHelper::jsonResponse(true, 'success', null, 200);
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request, string $id)
    {
        $request=$request->validated();
        try{
            if(!$this->profileRepository->getProfile()){
                return ResponseHelper::jsonResponse(false, 'Profile not found', null, 404);
            }
            $this->profileRepository->update($request);
            return ResponseHelper::jsonResponse(true, 'success', null, 200);
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
