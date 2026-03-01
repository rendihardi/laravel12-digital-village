<?php

namespace App\Repositories;

use App\Interfaces\EventParticipantRepositoryInterFace;
use App\Models\EventParticipant;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventParticipantRepository implements EventParticipantRepositoryInterFace
{
     public function getAll($search = null, $limit = null, $excecute = false, $status = null)
    {
        $query = EventParticipant::query();

        if ($search) {
            $query->search($search);
        }
        $query->orderBy('created_at', 'desc');
        if(auth()->user()->hasRole('head-of-family')) {
            $query->where('head_of_family_id', auth()->user()->headOfFamily->id);
        }
        //  if ($status === 'joined') {
        //   $query->whereHas('eventParticipants', function ($query) {
        //   $query->where('head_of_family_id', auth()->user()->headOfFamily->id);
        //   });
        //     }

        // if ($status === 'joined') {
        //  $query->where('head_of_family_id', auth()->user()->headOfFamily->id);
        // }
        if ($limit) {
            $query->limit($limit);
        }    
        if ($excecute) {
            return $query->get();
        }
        return $query;      
    }

    public function getAllPaginate(?string $search, ?int $rowPerPage, $status = null)
    {
        $query = $this->getAll($search, $rowPerPage, false, $status);
        return $query->paginate($rowPerPage);
    }

    public function create(array $data)
    {
          DB::beginTransaction();
        try{
            $event=Event::find($data['event_id']);
            $eventParticipant = new EventParticipant();
            $eventParticipant->event_id = $data['event_id'];
            $eventParticipant->head_of_family_id = $data['head_of_family_id'];
            $eventParticipant->quantity = $data['quantity'];
            $eventParticipant->total_price = $event->price * $data['quantity'];
            $eventParticipant->save();
            

            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = config('midtrans.serverKey');

            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = config('midtrans.isProduction');

            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');

            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        $params = array(
           'transaction_details' => array(
           'order_id'     => $eventParticipant->id,
           'gross_amount' => $eventParticipant->total_price,
         ),
          'customer_details' => array(
         'first_name' => auth()->user()->name,
         ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $eventParticipant->snap_token = $snapToken;
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
             $event=Event::find($data['event_id']);
            $eventParticipant = EventParticipant::find($id);
            $eventParticipant->event_id = $data['event_id'];
            $eventParticipant->head_of_family_id = $data['head_of_family_id'];
            if(isset($data['quantity'])) {
                 $eventParticipant->quantity = $data['quantity'];
            }else{
                $data['quantity'] = $eventParticipant->quantity;
            }
            $eventParticipant->total_price = $event->price * $data['quantity'];
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
