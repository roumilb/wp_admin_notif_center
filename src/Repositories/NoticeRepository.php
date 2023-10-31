<?php

namespace WANC\Repositories;

use WANC\Database;
use WANC\Debug;
use WANC\Entities\Notice;
use WANC\Repository;

class NoticeRepository extends Repository
{
    public function __construct()
    {
        $this->attributes = [
            'content' => [
                'column' => 'content',
                'type' => 'text',
                'null' => false,
            ],
            'userId' => [
                'column' => 'user_id',
                'type' => 'int',
                'null' => false,
            ],
            'lastSeenDate' => [
                'column' => 'last_seen_date',
                'type' => 'datetime',
                'null' => false,
            ],
            'numberSeen' => [
                'column' => 'number_seen',
                'type' => 'int',
                'null' => false,
            ],
            'firstSeenDate' => [
                'column' => 'first_seen_date',
                'type' => 'datetime',
                'null' => false,
            ]
        ];

        $this->entity = Notice::class;

        parent::__construct();
    }

    public function getOneByContentUserId($content, $userId) {
        $query = 'SELECT * FROM '.$this->tableName.' WHERE content = "'.esc_sql($content).'" AND user_id = '.$userId;

        $data = Database::getObject($query);

        return $this->createEntityFromArray($data);
    }

    public function getAll($params = []) {
        $query = 'SELECT * FROM '.$this->tableName;

        if (isset($params['search'])) {
            $query .= ' WHERE content LIKE "%'.esc_sql($params['search']).'%"';
        }

        if (isset($params['order_by']) && isset($params['order'])) {
            $query .= ' ORDER BY '.esc_sql($params['order_by']).' '.esc_sql($params['order']);
        }

        if (isset($params['limit'])) {
            $query .= ' LIMIT '.intval($params['limit']);
        }

        if (isset($params['offset'])) {
            $query .= ' OFFSET '.intval($params['offset']);
        }

        return Database::getObjects($query, 'ARRAY_A');
    }

    public function getCountAll($params = []) {
        $query = 'SELECT count(*) FROM '.$this->tableName;

        if (isset($params['search'])) {
            $query .= ' WHERE content LIKE "%'.esc_sql($params['search']).'%"';
        }

        return Database::getVar($query);
    }
}