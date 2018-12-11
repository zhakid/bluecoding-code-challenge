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
}

add_action('admin_menu', 'wpbccc_admin_menu');

function wpbccc_users_page_handler() {
	global $wpdb;

    $table = new Custom_Users_List_Table();
    $table->prepare_items();
     ?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Custom List Users', 'wpbccc') ?></h1>
    <hr class="wp-header-end">

    <form id="contacts-table" method="POST">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php $table->display() ?>
    </form>

</div>
<?php
}
