<?php

namespace App\Repositories;

use App\Interfaces\HeadOfFamilyRepositoryInterface;
use App\Models\HeadOfFamily;
use Illuminate\Support\Facades\DB;

class HeadOfFamilyRepository implements HeadOfFamilyRepositoryInterface
{
    public function getAll($search = null, $limit = null, $excecute = false)
    {
        $query = HeadOfFamily::query();

        if ($search) {
            $query->search($search);
        }
        $query->orderBy('created_at', 'desc');
        if ($limit) {
            $query->limit($limit);
        }    
        if ($excecute) {
            return $query->get();
        }
        return $query;      
    }

    public function getAllPaginate($search = null, $rowPerPage = null)
    {
        $query = $this->getAll($search, $rowPerPage, false);
        return $query->paginate($rowPerPage);
    }

    public function create(array $data)
    {
       DB::beginTransaction();
       try {
            $userRepository = new UserRepository;
            $user = $userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
           $headOfFamily = new HeadOfFamily;
           $headOfFamily->user_id = $user->id;
           $headOfFamily->profile_picture = $data['profile_picture']->store('assets/head-of-families','public');
           $headOfFamily->identity_number = $data['identity_number'];
           $headOfFamily->gender = $data['gender'];
           $headOfFamily->date_of_birth = $data['date_of_birth'];
           $headOfFamily->phone_number = $data['phone_number'];
           $headOfFamily->occupation = $data['occupation'];
           $headOfFamily->marital_status = $data['marital_status'];
           $headOfFamily->save();
           DB::commit();
           return $headOfFamily;
           
       }catch (\Exception $e) {
           DB::rollBack();
           throw $e;
       }
    }

       public function getById(string $id)
    {
       try {
        $query = HeadOfFamily::where('id', $id);
        return $query->first();
       } catch (\Exception $e) {
           throw $e;
       }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();
        try {
            $headOfFamily = HeadOfFamily::find($id);
            if(isset($data['profile_picture'])) {
                $headOfFamily->profile_picture = $data['profile_picture']->store('assets/head-of-families','public');
            }
            $headOfFamily->identity_number = $data['identity_number'];
            $headOfFamily->gender = $data['gender'];
            $headOfFamily->date_of_birth = $data['date_of_birth'];
            $headOfFamily->phone_number = $data['phone_number'];
            $headOfFamily->occupation = $data['occupation'];
            $headOfFamily->marital_status = $data['marital_status'];
            $headOfFamily->save();

            $userRepository = new UserRepository;
            $userRepository->update($headOfFamily->user_id, [
                'name' => $data['name'],
                'email' => (isset($data['email'])) ? $data['email'] : $headOfFamily->user->email,
                'password' => (isset($data['password'])) ? bcrypt($data['password']) :$headOfFamily->user->password,
            ]);
            DB::commit();
            return $headOfFamily;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        try {
            $headOfFamily = HeadOfFamily::find($id);
            // $userRepository = new UserRepository;
            // $userRepository->delete($headOfFamily->user_id);
            $headOfFamily->delete();
            DB::commit();
            return $headOfFamily;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}