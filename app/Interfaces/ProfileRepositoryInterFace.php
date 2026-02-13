<?php

namespace App\Interfaces;

interface ProfileRepositoryInterFace
{
    public function getProfile();

    public function create(array $data);

    public function update(array $data);
}