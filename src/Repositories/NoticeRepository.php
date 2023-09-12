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

    public function getOneByContent($content) {
        $query = 'SELECT * FROM '.$this->tableName.' WHERE content = "'.esc_sql($content).'"';

        $data = Database::getObject($query);

        return $this->createEntityFromArray($data);
    }
}