<?php

namespace App\Repos;

use Illuminate\Database\Eloquent\Model;

abstract class RepoBase
{
    /**
     * Modelo de la base de datos
     *
     * @var [type]
     */
    public $model;

    /**
     * Orden por defecto de búsqueda
     *
     * @var string
     */
    public $order = "ASC";

    /**
     * Paŕametro por defécto de ordenamiento
     *
     * @var string
     */
    public $paramOrder = "name";

    /**
     * Constructor del repositorio
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Función abstracta para la implementación individual de los modles
     *
     * @param [Array] $data Arreglo con los datos de insersion
     * @return Model
     */
    abstract public function create($data);

    /**
     * Función para buscar 
     *
     * @param array $select
     * @param array $filter
     * @return void
     */
    public function find($select = ['*'], $filter = [])
    {
        return $this->model->select($select)->filter($filter)->orderBy($this->getParamOrder(), $this->getOrder())->get();
    }

    /**
     * Retorna el primer valor de una búsqueda en bases de datos
     *
     * @param array $select
     * @param array $filter
     * @param array $relations
     * @return void
     */
    public function findFirst($select = ['*'], $filter = [])
    {
        return $this->model->select($select)->filter($filter)->orderBy($this->getParamOrder(), $this->getOrder())->first();
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     * Retorna un registro determinado por un ID
     *
     * @param array $select
     * @param [type] $id
     * @return void
     */
    public function findById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * Función para actualizar un registro
     *
     * @param [type] $id
     * @param array $data
     * @return void
     */
    public function updateColumn($id, $data = [])
    {
        $item = $this->model->where('id', $id)->first();
        $item->update($data);
        $item->save();
        return $item;
    }

    /**
     * Get the value of order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @return  self
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get the value of paramOrder
     */
    public function getParamOrder()
    {
        return $this->paramOrder;
    }

    /**
     * Set the value of paramOrder
     *
     * @return  self
     */
    public function setParamOrder($paramOrder)
    {
        $this->paramOrder = $paramOrder;

        return $this;
    }
}
