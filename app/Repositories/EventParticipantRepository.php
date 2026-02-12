<?php

namespace App\Repositories;

use App\Interfaces\EventParticipantRepositoryInterFace;
use App\Models\EventParticipant;
use App\Models\Events;
use Illuminate\Support\Facades\DB;

class EventParticipantRepository implements EventParticipantRepositoryInterFace
{
     public function getAll($search = null, $limit = null, $excecute = false)
    {
        $query = EventParticipant::query();

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

    public function getAllPaginate(?string $search, ?int $rowPerPage)
    {
        $query = $this->getAll($search, $rowPerPage, false);
        return $query->paginate($rowPerPage);
    }

    public function create(array $data)
    {
          DB::beginTransaction();
        try{
            $event=Events::find($data['event_id']);
            $eventParticipant = new EventParticipant();
            $eventParticipant->event_id = $data['event_id'];
            $eventParticipant->head_of_family_id = $data['head_of_family_id'];
            $eventParticipant->quantity = $data['quantity'];
            $eventParticipant->total_price = $event->price * $data['quantity'];
            $eventParticipant->save();
            DB::commit();
            return $eventParticipant;
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function getById(string $id)
    {
        $query = EventParticipant::where('id', $id);
        return $query->first();
    }

    public function update(string $id, array $data)
    {
        try{
            DB::beginTransaction();
             $event=Events::find($data['event_id']);
            $eventParticipant = EventParticipant::find($id);
            $eventParticipant->event_id = $data['event_id'];
            $eventParticipant->head_of_family_id = $data['head_of_family_id'];
            if(isset($data['quantity'])) {
                $eventParticipant->total_price = $event->price * $data['quantity'];
            }else{
                 $eventParticipant->quantity = $data['quantity'];
            }
            if(isset($data['payament_status'])) {
                $eventParticipant->thumbnail = $data['payament_status'];
            }
            $eventParticipant->save();
            DB::commit();
            return $eventParticipant;
        }catch(\Exception $e){
            throw $e;
        }
    }   

    public function delete(string $id)
    {
        try{
            DB::beginTransaction();
            $eventParticipant = EventParticipant::find($id);
            $eventParticipant->delete();
            DB::commit();
            return $eventParticipant;
        }catch(\Exception $e){
            throw $e;
        }
    }

}
