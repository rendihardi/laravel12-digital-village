<?php

namespace App\Interfaces;

interface EventParticipantRepositoryInterFace
{
    public function create(array $data);
    public function getAll( 
        ?string $search,
        ?int $limit,
        bool $excecute);
    public function getAllPaginate(
          ?string $search,
        ?int $rowPerPage);
    public function getById(string $id);
    public function update(string $id, array $data);
    public function delete(string $id);
}