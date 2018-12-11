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

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

function wpbccc_plugin_load_textdomain() {
	load_plugin_textdomain( 'wpbccc', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'wpbccc_plugin_load_textdomain' );

function wpbccc_admin_menu() {
    add_menu_page(
    	__('Custom List Users', 'wpbccc'),
    	__('Custom List Users', 'wpbccc'),
    	'list_users',
    	'custom-list-users',
    	'wpbccc_users_page_handler',
    	'dashicons-groups'
    );
}

add_action('admin_menu', 'wpbccc_admin_menu');

function wpbccc_users_page_handler() {

}