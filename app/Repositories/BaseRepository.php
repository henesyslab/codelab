<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * Instância da model.
     *
     * @var Model
     */
    protected $model;

    /**
     * Retorna a uma instância da model.
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Define a instância da model.
     *
     * @param Model
     * @return void
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Retorna todos os registros cadastrados.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetchAll($columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * Execute the query and get the first result.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|object|static|null
     */
    public function first($columns = ['*'])
    {
        return $this->model->first($columns);
    }

    /**
     * Execute uma query para um único registro filtrado por sua ID.
     *
     * @param  int    $id
     * @param  array  $columns
     * @return mixed|static
     */
    public function fetchById(int $id, array $columns = ['*'])
    {
        if ($this->model->exists) {
            return $this->model;
        }

        return $this->model = $this->model->find($id, $columns);
    }

    /**
     * Cria um novo registro com os dados recebidos.
     *
     * @param  array  $attributes
     * @return Model
     */
    public function create(array $attributes)
    {
        $this->model->fill($attributes);
        $this->model->save();

        return $this->model;
    }

    /**
     * Atualiza um determinado registro com os dados recebidos.
     *
     * @param  int  $id
     * @param  array  $attributes
     * @return Model
     */
    public function update(int $id, array $attributes)
    {
        $this->fetchById($id);
        $this->model->fill($attributes);
        $this->model->save();

        return $this->model;
    }

    /**
     * Remove os registros com os IDs informados.
     *
     * @param  array|int  $ids
     * @return int
     */
    public function destroy($ids)
    {
        return $this->model->destroy($ids);
    }
}
