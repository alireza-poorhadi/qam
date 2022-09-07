<?php

namespace App\Models\contracts;

use App\Utils\Sanitize;
use Medoo\Medoo;

class MysqlBaseModel extends BaseModel
{
    public function __construct($id = null)
    {
        global $settings;
        $this->pageSize = $settings['pageSize'];

        try {
            $this->connection = new Medoo([
                'type' => 'mysql',
                'host' => $_ENV['db_host'],
                'database' => $_ENV['db_name'],
                'username' => $_ENV['db_username'],
                'password' => $_ENV['db_password'],
                'charset' => 'utf8mb4'
            ]);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        if (!is_null($id)) {
            return $this->find($id);
        }
    }

    # ----------- CRUD ------------
    public function create(array $data): int
    {
        $this->connection->insert($this->table, $data);
        return $this->connection->id();
    }

    public function find(int $id): object
    {
        $user = $this->connection->get($this->table, '*', [$this->primaryKey => $id]);
        if (!is_null($user)) {
            foreach ($user as $col => $val) {
                $this->attributes[$col] = $val;
            }
        }
        return $this;
    }

    public function get(array $columns, array $where = []): array
    {
        return $this->connection->select($this->table, $columns, $where);
    }

    public function getAll()
    {
        return $this->connection->select($this->table, '*');
    }

    public function update(array $data, array $where): int
    {
        $result = $this->connection->update($this->table, $data, $where);
        return $result->rowCount();
    }

    public function delete(array $where): int
    {
        $result = $this->connection->delete($this->table, $where);
        return $result->rowCount();
    }

    # -------- Aggregation ---------

    public function count(array $where): int
    {
        return $this->connection->count($this->table, $where);
    }

    # ------------------------------

    public function remove(): int
    {
        $recordID = $this->{$this->primaryKey};
        return $this->delete([$this->primaryKey => $recordID]);
    }

    public function save()
    {
        $recordID = $this->{$this->primaryKey};
        $data = $this->attributes;
        $this->update($data, [$this->primaryKey => $recordID]);
        return $this->find($recordID);
    }

    # ------- Pagination -------------
    public function paginate(array $columns,array $where)
    {
        global $request;
        $page = $request->params()['page'] ?? 1;
        if (!is_numeric($page)) {
            $page = 1;
        }
        $page = Sanitize::clean($page);
        $pageSize = $this->pageSize;
        $allCount = $this->count($where);
        // dd($allCount);
        $pageCount = ceil($allCount / $pageSize);
        $all = $this->get($columns, [
            'LIMIT' => [($page - 1) * $pageSize, $pageSize], 'ORDER' => [
                'created_at' => 'DESC'], 'AND' => $where]);
        return [
            'all' => $all,
            'pageCount' => $pageCount,
            'page' => $page,
            'allCount' => $allCount
        ];
    }
}
