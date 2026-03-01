<?php

namespace App\Interfaces;

interface DevelopmentRepositoryInterface
{
    public function create(array $data);
    public function getAll($search = null, $limit = null,bool $excecute = false, $status = null);
    public function getAllPaginate($search = null, $rowPerPage = null, $status = null);
    public function getById(string $id);
    public function update(string $id, array $data);
    public function delete(string $id);
}