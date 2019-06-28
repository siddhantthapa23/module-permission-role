<?php

namespace App\Repositories;

interface BaseRepository
{
    public function all();

    public function create($data);

    public function find($id);

    public function updateOrCreate($keys, $data);

    public function update($id, $data);

    public function destroy($id);

    public function latest();
}