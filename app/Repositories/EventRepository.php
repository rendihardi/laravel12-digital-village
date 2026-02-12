<?php

namespace App\Repositories;

use App\Interfaces\EventRepositoryInterface;
use App\Models\Events;
use Illuminate\Support\Facades\DB;

use function Illuminate\Log\log;

class EventRepository implements EventRepositoryInterface
{
      public function getAll($search = null, $limit = null, $excecute = false)
    {
        $query = Events::query();

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
            $event = new Events;
            $event->thumbnail=$data['thumbnail']->store('assets/events', 'public');
            $event->name = $data['name'];
            $event->description = $data['description'];
            $event->price = $data['price'];
            $event->date = $data['date'];
            $event->time = $data['time'];
            $event->is_active = $data['is_active'];
            $event->save();  
            DB::commit();
            return $event;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

  public function update (string $id, array $data)
    {
        DB::beginTransaction();
        try {
            $event = Events::find($id);
            if(isset($data['thumbnail'])) {
                $event->thumbnail=$data['thumbnail']->store('assets/events', 'public');
            }
            $event->name = $data['name'];
            $event->description = $data['description'];
            $event->price = $data['price'];
            $event->date = $data['date'];
            $event->time = $data['time'];
            $event->is_active = $data['is_active'];
            $event->save();  
            DB::commit();
            return $event;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
  }

    public function getById(string $id)
    {
        $query = Events::where('id', $id);
        return $query->first();
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        try {
            $event = Events::find($id);
            $event->delete();
            DB::commit();
            return $event;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}