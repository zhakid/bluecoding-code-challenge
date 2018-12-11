<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Custom_Users_List_Table extends WP_List_Table {

	public function __construct() {
        global $status, $page;

        parent::__construct([
            'singular' => 'user',
            'plural' => 'users',
        ]);
    }

    public function column_default($item, $column_name) {
        return $item->$column_name;
    }

    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item->ID
        );
    }

    public function column_display_name($item)
    {
        $actions = [
            'edit' => sprintf(
            	'<a href="?page=users_form&id=%s">%s</a>',
            	$item->ID,
            	__('Edit', 'wpbccc')
            ),
            'activate' => sprintf(
            	'<a href="?page=%s&action=activate&id=%s">%s</a>',
            	$_REQUEST['page'],
            	$item->ID,
            	__('Activate', 'wpbccc')
            ),
        ];

        return sprintf('%s %s',
            $item->display_name,
            $this->row_actions($actions)
        );
    }

    public function column_role($item) {
    	$user_meta = get_userdata( $item->ID );
    	$user_roles = $user_meta->roles;

        return implode(', ', $user_roles);
    }

    public function column_status($item) {
    	return 'Active';
    }

    public function get_columns() {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'display_name' => __('Name', 'wpbccc'),
            'user_email' => __('E-Mail', 'wpbccc'),
            'role' => __('Role', 'wpbccc'),
            'status' => __('Status', 'wpbccc'),
        ];

        return $columns;
    }

    public function get_sortable_columns()
    {
        $sortable_columns = [
            'display_name' => ['display_name', true],
            'user_email' => ['user_email', false],
        ];

        return $sortable_columns;
    }

    public function get_bulk_actions()
    {
        $actions = [
            'activate' => 'Activate'
        ];

        return $actions;
    }

    public function process_bulk_action()
    {

    }

    public function prepare_items() {


        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [$columns, $hidden, $sortable];

        $this->process_bulk_action();

        $per_page = 10;
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' )  : 1;
        $offset = ( $paged - 1 ) * $per_page;

        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'display_name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $args = [
        	'count_total' => true,
        	'fields' => ['ID', 'display_name', 'user_email'],
        	'orderby' => $orderby,
        	'order' => $order,
        	'number' => $per_page,
        	'offset' => $offset,
        ];

        $users = new WP_User_Query( $args );

        $this->items = $users->get_results();
        $total_items = $users->get_total();

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
}
