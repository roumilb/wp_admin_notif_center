<?php

namespace WANC\Services;

use WANC\Debug;
use WANC\Repositories\NoticeRepository;

class NoticeListingService extends \WP_List_Table
{
    function get_columns()
    {
        $columns = array(
            'cb'            => '',
            'id'          => __('ID', 'wanc'),
            'content'          => __('Content', 'wanc'),
            'user_id'         => __('User ID', 'wanc'),
            'first_seen_date'   => __('First seen date', 'wanc'),
            'last_seen_date'   => __('Last seen date', 'wanc'),
            'number_seen'        => __('Number of time displayed', 'wanc')
        );
        return $columns;
    }

    function prepare_items()
    {

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $primary  = 'content';
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);

        $noticeRepository = new NoticeRepository();

        $perPage = $this->get_items_per_page('elements_per_page', 10);
        $currentPage = $this->get_pagenum();

        $filters = [
            'limit' => $perPage,
            'offset' => ($currentPage - 1)*$perPage
        ];

        $this->getOrderBy($filters);
        $this->getSearch($filters);

        $totalItems = $noticeRepository->getCountAll($filters);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page'    => $perPage,
            'total_pages' => ceil( $totalItems / $perPage )
        ));

        $this->items = $noticeRepository->getAll($filters);
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'content':
                return htmlspecialchars($item[$column_name]);
            case 'user_id':
                return '<a target="_blank" href="/wp-admin/user-edit.php?user_id='.$item[$column_name].'">'.$item[$column_name].'</a>';
            case 'id':
            case 'first_seen_date':
            case 'last_seen_date':
            case 'number_seen':
            default:
                return $item[$column_name];
        }
    }

    protected function get_sortable_columns()
    {
        $sortable_columns = array(
            'id'  => array('id', false),
            'user_id'  => array('user_id', false),
            'first_seen_date' => array('first_seen_date', false),
            'last_seen_date'   => array('last_seen_date', false),
            'number_seen'   => array('number_seen', false)
        );
        return $sortable_columns;
    }

    private function getOrderBy(&$filters) {
        $filters['order_by'] = empty($_GET['orderby']) ? 'id' : sanitize_text_field($_GET['orderby']);
        $filters['order'] = empty($_GET['order']) ? 'DESC' : sanitize_text_field($_GET['order']);
    }

    private function getSearch(&$filters) {
        if (!empty($_POST['s'])) {
            $filters['search'] = sanitize_text_field($_POST['s']);
        }
    }
}