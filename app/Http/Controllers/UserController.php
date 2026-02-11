<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;


class UserController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        try {
            $users = $this->userRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, 'success', UserResource::collection($users), 200);
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
            $users = $this->userRepository->getAllPaginate(
                $request['search'] ?? null,
                $request['rowPerPage']


            );

            return ResponseHelper::jsonResponse(true, 'success', PaginateResource::make($users, UserResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function store(UserStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $user = $this->userRepository->create($request);
            return ResponseHelper::jsonResponse(true, 'data created', new UserResource($user), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
    public function show($id)
    {
        try {
            $user = $this->userRepository->getById($id);
            if(!$user) return ResponseHelper::jsonResponse(false, 'user not found', null, 404);
            return ResponseHelper::jsonResponse(true, 'detail user', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $request = $request->validated();
       try {
            $user = $this->userRepository->getById($id);
            if(!$user) return ResponseHelper::jsonResponse(false, 'user not found', null, 404);
            $user = $this->userRepository->update(
                $id,
                $request
            );
            return ResponseHelper::jsonResponse(true, 'data updated', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = $this->userRepository->getById($id);
            if(!$user) return ResponseHelper::jsonResponse(false, 'user not found', null, 404);
            $this->userRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'data deleted', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

}

