<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Custom_Users_List_Table extends WP_List_Table
{

	public function __construct()
    {
        global $status, $page;

        parent::__construct([
            'singular' => 'user',
            'plural' => 'users',
        ]);
    }

    public function column_default($user, $column_name)
    {
        return $user->$column_name;
    }

    public function column_cb($user)
    {
        return sprintf(
            '<input type="checkbox" name="user_id[]" value="%s" />',
            $user->ID
        );
    }

    public function column_display_name($user)
    {
        $actions = [
            'edit' => sprintf(
            	'<a href="?page=wpbccc_custom_users_edit&user_id=%s">%s</a>',
            	$user->ID,
            	__('Edit', 'wpbccc')
            ),
            'change-status' => sprintf(
            	'<a href="?page=%s&action=change-status&user_id=%s">%s</a>',
            	$_REQUEST['page'],
            	$user->ID,
            	__('Change Status', 'wpbccc')
            ),
        ];

        return sprintf('%s %s',
            $user->display_name,
            $this->row_actions($actions)
        );
    }

    public function column_role($user)
    {
    	$user_meta = get_userdata( $user->ID );
    	$user_roles = $user_meta->roles;

        return implode(', ', $user_roles);
    }

    public function column_status($user)
    {
    	return ($user->user_status) ? __('Active', 'wpbccc') : __('Inactive', 'wpbccc');
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
            'change-status' => __('Change Status', 'wpbccc')
        ];

        return $actions;
    }

    public function process_bulk_action()
    {
        if ('change-status' === $this->current_action()) {
            $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : [];

            if ( is_array($user_id) ) {
                foreach ($user_id as $id) {
                    $this->update_user_status($id);
                }
            } else {
                $this->update_user_status($user_id);
            }
        }
    }

    private function update_user_status($user_id)
    {
        global $wpdb;

        $user = get_user_by('ID', $user_id);

        $result = $wpdb->update(
            $wpdb->users,
            [
                'user_status' => ($user->user_status) ? 0 : 1,
            ],
            [ 'ID' => $user->ID ]
        );

        return $result;
    }

    public function prepare_items()
    {
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
        $role = isset($_REQUEST['role']) ? $_REQUEST['role'] : '';

        $args = [
            'role' => $role,
            'count_total' => true,
            'fields' => ['ID', 'display_name', 'user_email', 'user_status'],
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
