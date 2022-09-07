<?php

namespace App\Models\contracts;

interface CrudInterface {
    # create
    public function create (array $data) : int;

    # read 
    public function find(int $id) : object; #single
    public function get(array $columns, array $where) : array; #multiple

    # update
    public function update(array $data, array $where) : int;

    # delete
    public function delete(array $where) : int;
}