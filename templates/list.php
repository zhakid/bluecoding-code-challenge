<div class="wrap">
    <h1 class="wp-heading-inline">
    	<?php _e('Custom List Users', 'wpbccc') ?>
	</h1>
    <hr class="wp-header-end">

    <form id="contacts-table" method="POST">
    	<p class="search-box">
			<select id="role" name="role">
				<option value=""><?php _e('All Roles', 'wpbccc'); ?></option>
				<?php wp_dropdown_roles($data->role); ?>
			</select>
			<input type="submit" id="search-submit" class="button" value="<?php _e('Filter by Role', 'wpbccc'); ?>">
		</p>
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php $data->table->display() ?>
    </form>
</div>