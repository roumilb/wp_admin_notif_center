<?php

namespace WANC;

class Repository
{
    protected $attributes;
    protected $tableName;
    protected $entity;

    public function __construct()
    {
        $entityPath = explode('\\', $this->entity);
        $this->tableName = Database::getPrefix().'wanc_'.strtolower(array_pop($entityPath));
    }

    public function createDatabaseTable() {
        $query = 'CREATE TABLE '.$this->tableName.'(';
        $columns = [];
        foreach ($this->attributes as $attribute) {
            switch ($attribute['type']) {
                case 'int':
                    $column = $attribute['column'].' INT';
                    break;
                case 'string':
                    $column = $attribute['column'].' VARCHAR(255)';
                    break;
                case 'text':
                    $column = $attribute['column'].' LONGTEXT';
                    break;
                case 'datetime':
                    $column = $attribute['column'].' DATETIME';
                    break;
            }

            if (isset($attribute['null']) && $attribute['null'] === false) {
                $column .= ' NOT NULL';
            }

            $columns[] = $column;
        }
        $query .= implode(',', $columns);
        $query .= ');';

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($query);
    }

    protected function createEntityFromArray($array, $entityClass = null) {
        if (empty($entityClass)) {
            $entityClass = $this->entity;
        }

        if (!class_exists($entityClass) || empty($data)) {
            return null;
        }

        $entity = new $entityClass();

        foreach ($array as $key => $value) {
            $setFunctionName = 'set'.ucfirst($key);
            if (method_exists($entityClass, $setFunctionName)) {
                $entity->$setFunctionName($value);
            }
        }

        return $entity;
    }

    public function save($entity) {
        if (empty($entity)) {
            return false;
        }

        $values = [];
        foreach ($this->attributes as $key => $attribute) {
            $getFunctionName = 'get'.ucfirst($key);
            if (!method_exists(get_class($entity), $getFunctionName)) {
                continue;
            }

            $value = $entity->$getFunctionName();

            switch ($attribute['type']) {
                case 'text':
                case 'datetime':
                    $value = '"'.esc_sql($value).'"';
                    break;
                case 'int':
                    $value = intval($value);
                    break;
            }

            $values[$attribute['column']] = $value;
        }

        $query = 'INSERT INTO '.$this->tableName.' ('.implode(',', array_keys($values)).') VALUES('.implode(',', $values).')';

        $
    }
}