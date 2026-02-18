<?php

namespace App\Repositories;

use App\Interfaces\SocialAssistanceRepositoryInterface;
use App\Models\SocialAssistance;
use Illuminate\Support\Facades\DB;

class SocialAssistanceRepository implements SocialAssistanceRepositoryInterface {

    public function getAll($search = null, $limit = null, $excecute = false)
      {
        $query = SocialAssistance::with('socialAssistanceRecipients');

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
       try{
        DB::beginTransaction();
        $socialAssistance = new SocialAssistance();
        $socialAssistance->name = $data['name'];
        $socialAssistance->thumbnail = $data['thumbnail']->store('assets/social-assistances', 'public');
        $socialAssistance->category = $data['category'];
        $socialAssistance->description = $data['description'];
        $socialAssistance->provider = $data['provider'];
        $socialAssistance->amount = $data['amount'];
        $socialAssistance->is_available = $data['is_available'];
        $socialAssistance->save();
        DB::commit();
        return $socialAssistance;

       }catch (\Exception $e) {
        DB::rollBack();
        throw $e;
       }
    }

    public function getById(string $id)
    {
       try{
        $query = SocialAssistance::with('socialAssistanceRecipients')->where('id', $id);
        return $query->first();
       }catch (\Exception $e) {
        throw $e;
       }
    }

    public function update(string $id, array $data)
    {
        try{
            DB::beginTransaction();
            $socialAssistance = SocialAssistance::find($id);
            $socialAssistance->name = $data['name'];
            if (isset($data['thumbnail'])) {
                $socialAssistance->thumbnail = $data['thumbnail']->store('assets/social-assistances', 'public');
            }
            $socialAssistance->category = $data['category'];
            $socialAssistance->description = $data['description'];
            $socialAssistance->provider = $data['provider'];
            $socialAssistance->amount = $data['amount'];
            $socialAssistance->is_available = $data['is_available'];
            $socialAssistance->save();
            DB::commit();
            return $socialAssistance;
        }catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(string $id)
    {
        try{
            DB::beginTransaction();
            $socialAssistance = SocialAssistance::find($id);
            $socialAssistance->delete();
            DB::commit();
            return $socialAssistance;
        }catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}