<?php

namespace App\Interfaces;

interface DevelopmentApplicantRepositoryInterface
{
    public function create(array $data);
    public function getAll($search = null, $limit = null, $excecute = false);
    public function getAllPaginate($search = null, $rowPerPage = null);
    public function getById(string $id);
    public function update(string $id, array $data);
    public function delete(string $id);
}