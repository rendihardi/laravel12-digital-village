<?php

namespace App\Repositories;
use App\Models\FamilyMember;
use App\Interface\FamilyMemberRepositoryInterface;
use App\Models\HeadOfFamily;
use Illuminate\Support\Facades\DB;

class FamilyMemberRepository implements FamilyMemberRepositoryInterface
{
    public function getAll($search = null, $limit = null, $excecute = false)
    {
        $query = FamilyMember::query();

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

    public function create (array $data)
    {
         DB::beginTransaction();
        try{
           $userRepository = new UserRepository;
           $user = $userRepository->create([
               'name' => $data['name'],
               'email' => $data['email'],
               'password' => $data['password'],
           ]);
           $familyMember = new FamilyMember;
           $familyMember->user_id = $user->id;
           $familyMember->head_of_family_id = $data['head_of_family_id'];
           $familyMember->profile_picture = $data['profile_picture']->store('assets/family-members','public');
           $familyMember->identity_number = $data['identity_number'];
           $familyMember->gender = $data['gender'];
           $familyMember->date_of_birth = $data['date_of_birth'];
           $familyMember->phone_number = $data['phone_number'];
           $familyMember->occupation = $data['occupation'];
           $familyMember->marital_status = $data['marital_status'];
           $familyMember->relation = $data['relation'];
           $familyMember->save();
           DB::commit();
           return $familyMember;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function getById(string $id)
    {
        $query = FamilyMember::where('id', $id);
        return $query->first();
    }

    public function update(string $id, array $data){
    try{
        DB::beginTransaction();
        $familyMember = FamilyMember::find($id);
        if(isset($data['profile_picture'])) {
            $familyMember->profile_picture = $data['profile_picture']->store('assets/family-members','public');
        }
        $familyMember->identity_number = $data['identity_number'];
        $familyMember->gender = $data['gender'];
        $familyMember->date_of_birth = $data['date_of_birth'];
        $familyMember->phone_number = $data['phone_number'];
        $familyMember->occupation = $data['occupation'];
        $familyMember->marital_status = $data['marital_status'];
        $familyMember->relation = $data['relation'];
        $familyMember->save();
        DB::commit();
        return $familyMember;
    }catch(\Exception $e){
        DB::rollBack();
        throw $e;
    }
    }

    public function delete(string $id){
        DB::beginTransaction();
        try{
            $familyMember = FamilyMember::find($id);
            $familyMember->delete();
            DB::commit();
            return $familyMember;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}