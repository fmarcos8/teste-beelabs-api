<?php


namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class AppRepository implements AppRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $search)
    {
        return $this->model->get();
    }

    public function store(array $data)
    {
        $item = $this->model;
        $item->fill($data);
        $item->save();

        return $item;
    }

    public function show($id, $relationship = "")
    {
        return $this->model
            ->with($relationship)
            ->findOrFail($id);
    }

    public function update(array $data, $id)
    {
        $item = $this->model->findOrFail($id);
        $item->fill($data);
        $item->save();

        return $item;
    }

    public function delete($id)
    {
        $this->model->destroy($id);
    }
}
