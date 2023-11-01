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
        $columns = [
            '`id` INT NOT NULL AUTO_INCREMENT'
        ];
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
        $columns[] = 'PRIMARY KEY (`id`)';
        $query .= implode(',', $columns);
        $query .= ');';

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($query);
    }

    protected function createEntityFromArray($array, $entityClass = null) {
        if (empty($entityClass)) {
            $entityClass = $this->entity;
        }

        if (!class_exists($entityClass) || empty($array)) {
            return null;
        }

        $entity = new $entityClass();

        $attributeKeys = array_keys($this->attributes);

        foreach ($array as $key => $value) {
            if ($key === 'id') {
                $setFunctionName = 'set'.ucfirst($key);
            } else {
                $newKey = array_search($key, array_column($this->attributes, 'column'));

                if (empty($attributeKeys[$newKey])) {
                    continue;
                }

                $setFunctionName = 'set'.ucfirst($attributeKeys[$newKey]);
            }

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

            $values[$attribute['column']] = $entity->$getFunctionName();
        }

        if (empty($entity->getId())) {
            return Database::insert($this->tableName, $values);
        } else {
            return Database::update($entity->getId(), $this->tableName, $values);
        }
    }
}