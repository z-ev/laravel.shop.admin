<?php


namespace App\Rep;


abstract class CoreRep
{
    protected $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());

    }
    abstract protected function getModelClass();
    protected function startConditions() {
        return clone $this->model;
    }

    public function getAllWithPaginate($perPage = null, $columns = [])
    {
        $result = $this
            ->startConditions()
            ->select($columns)
            ->paginate($perPage);
        return $result;
    }


    public function getEditId($id)
    {
        return $this->startConditions()->find($id);
    }


    public function getRequestID($get = true, $id = 'id')
    {
        if ($get){
            $data = $_GET;
        } else {
            $data = $_POST;
        }
        $id = !empty($data[$id]) ? (int)$data[$id] : null;
        //Если $id не получили то выбросим сразу ошибку
        if (!$id){
            throw new \Exception('Проверить Откуда id, если getRequestID(false) == $_POST', 404);
        }
        return $id;
    }


}
