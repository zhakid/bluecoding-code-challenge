<?php
/*
* Plugin Name: WP Bluecoding Code Challenge - Custom List Users
* Description: Custom list for wordpress users.
* Version:     1.0.0
* Author:      José Ayram
* Author URI:  https://joseayram.me/
* License:     GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: wpbccc
* Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WP_BC_CC_DIR', plugin_dir_path( __FILE__ ) );
require_once WP_BC_CC_DIR . '/includes/class-custom-list-users-table.php';
require_once WP_BC_CC_DIR . '/includes/class-wpbccc-template-loader.php';

function wpbccc_plugin_load_textdomain() {
	load_plugin_textdomain( 'wpbccc', false, WP_BC_CC_DIR . '/languages' );
}

add_action( 'plugins_loaded', 'wpbccc_plugin_load_textdomain' );

function wpbccc_admin_menu() {
    add_menu_page(
    	__('Custom List Users', 'wpbccc'),
    	__('Custom List Users', 'wpbccc'),
    	'edit_users',
    	'custom-list-users',
    	'wpbccc_users_page_handler',
    	'dashicons-groups'
    );

    add_submenu_page(
        null,
        __('Edit User', 'wpbccc'),
        __('Edit User', 'wpbccc'),
        'edit_users',
        'wpbccc_custom_users_edit',
        'wpbccc_users_form_page_handler'
    );
}

add_action('admin_menu', 'wpbccc_admin_menu');

function wpbccc_users_page_handler() {
	global $wpdb;

    $table = new Custom_Users_List_Table();
    $table->prepare_items();

    $data = [
        'role' => isset($_REQUEST['role']) ? $_REQUEST['role'] : '',
        'table' => $table,
    ];

    $template_loader = new Custom_List_Users_Template_Loader;
    $template_loader->set_template_data( $data )->get_template_part('list');
}

function wpbccc_users_form_page_handler() {
    global $wpdb;

    $message = '';
    $notice = '';

    $user_id = $_REQUEST['user_id'] ?? 0;
    $user = false;

    if ( isset($_REQUEST['nonce']) && check_admin_referer( 'wpbccc_custom_users_form', 'nonce' )) {
        $user = $_REQUEST;
        $user_valid = wpbccc_validate_user($user);


        if (true === $user_valid) {
            if ($user['user_id'] > 0) {
                $update = $wpdb->update(
                    $wpdb->users,
                    [
                        'display_name' => $user['display_name'],
                        'user_status' => $user['user_status'],
                    ],
                    [ 'ID' => $user['user_id'] ]
                );

                if ( $update ) {
                    $message = __('User was successfully updated', 'wpbccc');
                } else {
                    $notice = __('There was an error while updating user', 'wpbccc');
                }

                $user = get_user_by('ID', $user['user_id']);
            }
        } else {
            $notice = $user_valid;
        }

    } else {
        if ($user_id > 0) {
            $user = get_user_by('ID', $user_id);

            if (false === $user) {
                $notice = __('User not found', 'wpbccc');
            }
        }
    }

    add_meta_box(
        'custom_user_form_meta_box',
        __('Edit User', 'wpbccc'),
        'wpbccc_custom_user_form_meta_box_handler',
        'user',
        'normal',
        'default'
    );

    $data = [
        'message' => $message,
        'notice' => $notice,
        'user' => $user,
    ];

    $template_loader = new Custom_List_Users_Template_Loader;
    $template_loader->set_template_data( $data )->get_template_part('form');
}

function wpbccc_custom_user_form_meta_box_handler($user) {
    $data = [
        'user' => $user,
    ];

    $template_loader = new Custom_List_Users_Template_Loader;
    $template_loader->set_template_data( $data )->get_template_part('form_meta_box');
}

function wpbccc_validate_user($user) {
    $messages = [];

    if ('' === $user['display_name']) $messages[] = __('Name is required', 'wpbccc');

    if (empty($messages)) return true;

    return implode('<br />', $messages);
}
