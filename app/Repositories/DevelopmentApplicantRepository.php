<?php

namespace App\Repositories;

use App\Interfaces\DevelopmentApplicantRepositoryInterface;
use App\Models\Development;
use App\Models\DevelopmentApplicant;
use Illuminate\Support\Facades\DB;

class DevelopmentApplicantRepository implements DevelopmentApplicantRepositoryInterface
{
   public function getAll($search = null, $limit = null, $excecute = false)
    {
        $query = Development::query();
        
        if ($search) {
            $query->search($search);
        }
        $query->orderBy('created_at', 'desc');
//         $user = auth()->user();

// if ($user->hasRole('head-of-family')) {
//     $query->where('user_id', $user->id);
// }
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

    public function getById(string $id)
    {
        $query = Development::where('id', $id);
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $developmentApplicant = new DevelopmentApplicant();
            $developmentApplicant->development_id = $data['development_id'];
            $developmentApplicant->user_id = $data['user_id'];
            $developmentApplicant->save();
            DB::commit();
            return $developmentApplicant;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();
        try {
            $developmentApplicant = DevelopmentApplicant::find($id);
            $developmentApplicant->development_id = $data['development_id'];
            $developmentApplicant->user_id = $data['user_id'];
            if(isset($data['status'])) {
                $developmentApplicant->status = $data['status'];
            }
            $developmentApplicant->save();
            DB::commit();
            return $developmentApplicant;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(string $id)
    {
        try{
            DB::beginTransaction();
            $developmentApplicant = DevelopmentApplicant::find($id);
            $developmentApplicant->delete();
            DB::commit();
            return $developmentApplicant;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}