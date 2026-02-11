<?php

namespace App\Repositories;

use App\Interfaces\SocialAssistanceRecipientRepositoryInterface;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Support\Facades\DB;

class SocialAssistanceRecipientRepository implements SocialAssistanceRecipientRepositoryInterface
{

    public function getAll($search = null, $limit = null, $excecute = false)
    {
        $query = SocialAssistanceRecipient::query();

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
        try{
            $socialAssistanceRecipient = new SocialAssistanceRecipient();
            $socialAssistanceRecipient->social_assistance_id = $data['social_assistance_id'];
            $socialAssistanceRecipient->head_of_family_id = $data['head_of_family_id'];
            $socialAssistanceRecipient->bank = $data['bank'];
            $socialAssistanceRecipient->amount = $data['amount'];
            $socialAssistanceRecipient->reason = $data['reason'];
            $socialAssistanceRecipient->account_number = $data['account_number'];
            $socialAssistanceRecipient->proof = $data['proof']->store('assets/social-assistance-recipients','public');
           if(isset($data['status'])){
                $socialAssistanceRecipient->status = $data['status'];
           }
            $socialAssistanceRecipient->save();  
            DB::commit();
            return $socialAssistanceRecipient;

        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

      public function getById(string $id)
    {
       try {
        $query = SocialAssistanceRecipient::where('id', $id);
        return $query->first();
       } catch (\Exception $e) {
           throw $e;
       }
    }

    public function update ($id, array $data){
        DB::beginTransaction();
        try{
            $socialAssistanceRecipient = SocialAssistanceRecipient::find($id);
            $socialAssistanceRecipient->social_assistance_id = $data['social_assistance_id'];
            $socialAssistanceRecipient->head_of_family_id = $data['head_of_family_id'];
            $socialAssistanceRecipient->bank = $data['bank'];
            $socialAssistanceRecipient->amount = $data['amount'];
            $socialAssistanceRecipient->reason = $data['reason'];
            $socialAssistanceRecipient->account_number = $data['account_number'];
            if(isset($data['proof'])){
                $socialAssistanceRecipient->proof = $data['proof']->store('assets/social-assistance-recipients','public');
            }
           if(isset($data['status'])){
                $socialAssistanceRecipient->status = $data['status'];
           }
            $socialAssistanceRecipient->save();  
            DB::commit();
            return $socialAssistanceRecipient;

        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            $socialAssistanceRecipient = SocialAssistanceRecipient::find($id);
            $socialAssistanceRecipient->delete();
            DB::commit();
            return $socialAssistanceRecipient;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
    
}