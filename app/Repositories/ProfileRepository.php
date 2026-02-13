<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoryInterFace;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class ProfileRepository implements ProfileRepositoryInterFace
{
    public function getProfile()
    {
        return  Profile::first();  
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try{
            $profile = new profile();
            $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agricultural_area = $data['agricultural_area'];
            $profile->total_area = $data['total_area'];
            $profile->save();

            if(array_key_exists('images', $data)){
             foreach ($data['images'] as $image){
                $profile->profileImages()->create(['image' => $image->store('assets/profiles', 'public')]);
             }
            }
            DB::commit();
            return $profile;
        }
        catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function update(array $data)
    {
         DB::beginTransaction();
        try{
            $profile = Profile::first();
            if(isset($data['thumbnail'])){
                $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            }
            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headmand = $data['headman'];
            $profile->people = $data['people'];
            $profile->agricultural_area = $data['agricultural_area'];
            $profile->total_area = $data['total_area'];
            $profile->save();
            
            if(array_key_exists('images', $data)){
             foreach ($data['images'] as $image){
                $profile->profileImages()->create(['image' => $image->store('assets/profiles', 'public')]);
             }
            }
            DB::commit();
            return $profile;
        }catch (\Exception $e) {
           DB::rollBack();
           throw $e;
       }
    }
}