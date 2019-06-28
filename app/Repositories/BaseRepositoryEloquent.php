<?php

namespace App\Repositories;

class BaseRepositoryEloquent implements BaseRepository
{
    protected $model;

    public function all()
    {
        return $this->model->all();
    }

    public function create($data)
    {
        $data['created_by'] = auth()->id();
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function updateOrCreate($keys, $data)
    {
        return $this->model->updateOrCreate($keys, $data);
    }

    public function update($id, $data)
    {
        $record = $this->model->findOrFail($id);
        $data['updated_by'] = auth()->id();
        $record->fill($data)->save();
        return $record;
    }

    public function destroy($id)
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function latest()
    {
        return $this->model->latest()->get();
    }
}