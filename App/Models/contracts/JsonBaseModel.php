<?php

namespace App\Models\contracts;

class JsonBaseModel extends BaseModel
{
    private $baseJsonPath;
    private $jsonFilename;

    public function __construct()
    {
        $this->baseJsonPath = BASEPATH . '/storage/json/';
        $this->jsonFilename = $this->baseJsonPath . $this->table . '.json';
    }

    private function writeJson($data) : void
    {
        $dataJson = json_encode($data);
        file_put_contents($this->jsonFilename, $dataJson);
    }

    private function readJson()
    {
        return json_decode(file_get_contents($this->jsonFilename));
    }

    public function create (array $data) : int
    {
        $tableData = $this->readJson();
        $tableData[] = $data;
        $this->writeJson($tableData);
        return 1;
    }

    public function find(int $id) : object
    {
        $dataJson = $this->readJson();
        foreach ($dataJson as $row) {
            if ($row->{$this->primaryKey} == $id) {
                return $row;
            }
        }
        return (object) [];
    }

    public function get(array $columns, array $where) : array
    {
        return [];
    }

    public function update(array $data, array $where) : int
    {
        return 1;
    }

    public function delete(array $where) : int
    {
        return 1;
    }
}