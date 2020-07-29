<?php


namespace App\Repositories;


interface AppRepositoryInterface
{
    public function all(array $search);

    public function store(array $data);

    public function show($id, $relationship);

    public function update(array $data, $id);

    public function delete($id);

}
