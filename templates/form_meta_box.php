<div class="formdata">
    <p>
        <label for="name">
            <?php _e('Name:', 'wpbccc')?>
        </label>
        <br>
        <input type="text" id="display_name" name="display_name" required
            value="<?php echo esc_attr($data->user->display_name)?>">
    </p>
    <p>
        <label for="email">
            <?php _e('E-Mail:', 'wpbccc')?>
        </label>
        <br>
        <input type="email" id="email" name="email" required readonly="readonly"
            value="<?php echo esc_attr($data->user->user_email)?>">
    </p>

    <p>
        <label for="email"><?php _e('Roles:', 'wpbccc')?></label>
        <br>
        <input type="email" id="roles" name="roles" required readonly="readonly"
            value="<?php echo esc_attr(implode(',', $data->user->roles))?>">
    </p>

    <p>
        <label><?php _e('Status:', 'wpbccc')?></label>
        <br>
        <label for="status-active">
            <input type="radio" name="user_status" id="status-active"
                value="1" <?php echo ($data->user->user_status) ? 'checked':''?>>
            <?php _e('Active', 'wpbccc'); ?>
        </label>
        <label for="status-inactive">
            <input type="radio" name="user_status" id="status-inactive"
                value="0" <?php echo (!$data->user->user_status) ? 'checked':''?>>
            <?php _e('Inactive', 'wpbccc'); ?>
        </label>
    </p>
</div>
