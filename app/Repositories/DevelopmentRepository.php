<?php

namespace App\Repositories;

use App\Interfaces\DevelopmentRepositoryInterface;
use App\Models\Development;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\DB;

class DevelopmentRepository implements DevelopmentRepositoryInterface
{
    public function getAll($search = null, $limit = null, $excecute = false, $status = null)
    {
        $query = Development::with('developmentApplicants')->orderBy('created_at', 'desc');;
        
        if ($search) {
            $query->search($search);
        }
        $query->orderBy('created_at', 'desc');
        
        if ($status === 'my-applications') {
    $query->whereHas('developmentApplicants', function ($query) {
        $members = FamilyMember::where(
                'head_of_family_id',
                auth()->user()->headOfFamily->id
            )
            ->pluck('user_id')
            ->toArray();

        $members[] = auth()->user()->id;

        $query->whereIn('user_id', $members);
    });
}
        if ($limit) {
            $query->limit($limit);
        }    
        if ($excecute) {
            return $query->get();
        }
        return $query;
        
    }

    public function getAllPaginate($search = null, $rowPerPage = null, $status = null)
    {
        $query = $this->getAll($search, $rowPerPage, false, $status);
        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = Development::with('developmentApplicants')->where('id', $id);
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $development = new Development;
            $development->thumbnail = $data['thumbnail']->store('assets/developments', 'public');
            $development->name = $data['name'];
            $development->description = $data['description'];
            $development->person_in_charge = $data['person_in_charge'];
            $development->start_date = $data['start_date'];
            $development->end_date = $data['end_date'];
            $development->amount = $data['amount'];
            $development->save();
            DB::commit();
            return $development;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    

    public function update(string $id, array $data)
    {
        DB::beginTransaction();
        try {
            $development = Development::find($id);
             if(isset($data['thumbnail'])) {
            $development->thumbnail = $data['thumbnail']->store('assets/developments', 'public');
          }
            $development->name = $data['name'];
            $development->description = $data['description'];
            $development->person_in_charge = $data['person_in_charge'];
            $development->start_date = $data['start_date'];
            $development->end_date = $data['end_date'];
            $development->amount = $data['amount'];
            $development->save();
            DB::commit();
            return $development;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        try {
            $development = Development::find($id);
            $development->delete();
            DB::commit();
            return $development;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}