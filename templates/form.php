<div class="wrap">
    <h2><?php _e('Edit User', 'wpbccc')?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=custom-list-users');?>">
            <?php _e('back to list', 'wpbccc')?>
        </a>
    </h2>

    <?php if ( !empty($data->notice) ): ?>
        <div id="notice" class="error">
            <p><?php echo $data->notice ?></p>
        </div>
    <?php endif;?>

    <?php if ( !empty($data->message) ): ?>
        <div id="message" class="updated">
            <p><?php echo $data->message ?></p>
        </div>
    <?php endif;?>

    <form id="form" method="POST">
        <?php wp_nonce_field( 'wpbccc_custom_users_form', 'nonce' ); ?>

        <input type="hidden" name="user_id" value="<?php echo $data->user->ID ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    <?php do_meta_boxes('user', 'normal', $data->user); ?>
                    <input type="submit" value="<?php _e('Save', 'wpbc')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>