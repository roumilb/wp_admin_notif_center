<?php defined('ABSPATH') || exit; ?>
<div class="wrap">
    <h1><?php esc_html_e('Hide admin notices settings', 'wp-admin-notification-center'); ?></h1>
    <p><?php esc_html_e('By default this plugin moves all of your admin notifications in the notification center.', 'wp-admin-notification-center'); ?></p>
    <p><?php esc_html_e('In this settings page you can change that and force the display of some notifications, like errors, to not miss them!', 'wp-admin-notification-center'); ?></p>
    <form method="post" action="">
        <?php wp_nonce_field('wanc_save_settings', 'wanc_nonce'); ?>
        <table class="form-table" role="presentation" id="wanc_settings">
            <tr>
                <th scope="row">
                    <?php esc_html_e('Notifications to display in the notification center', 'wp-admin-notification-center'); ?>
                </th>
                <td>
                    <ul>
                        <?php foreach ($data['notice_types'] as $type => $moveToCenter) { ?>
                            <li>
                                <label>
                                    <input type="checkbox" name="wanc_display[<?php echo esc_attr($type); ?>]" value="1" <?php checked($moveToCenter, 1); ?>>
                                    <?php echo esc_html(ucfirst($type)); ?>
                                </label>
                            </li>
                        <?php } ?>
                    </ul>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <?php esc_html_e('ACL', 'wp-admin-notification-center'); ?>
                    <span class="wanc_settings_desc">
                        <?php esc_html_e('This option allows you to choose which user roles can see the notifications in the dashboard', 'wp-admin-notification-center'); ?>
                    </span>
                </th>
                <td id="wanc_settings_acl">
                    <?php foreach ($data['roles'] as $role => $roleSettings) { ?>
                        <div class="wanc_settings_acl_one">
                            <label for="wanc_roles_<?php echo esc_attr($role); ?>"><?php echo esc_html($roleSettings['name']); ?></label>
                            <select name="wanc_roles[<?php echo esc_attr($role); ?>]" id="wanc_roles_<?php echo esc_attr($role); ?>">
                                <option value="1" <?php selected($roleSettings['allowed'], 1); ?>><?php esc_html_e('Display notifications', 'wp-admin-notification-center'); ?></option>
                                <option value="0" <?php selected($roleSettings['allowed'], 0); ?>><?php esc_html_e('Do not display notifications', 'wp-admin-notification-center'); ?></option>
                            </select>
                        </div>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wanc_spam_words"><?php esc_html_e('Spam words', 'wp-admin-notification-center'); ?></label>
                    <span class="wanc_settings_desc">
                        <?php esc_html_e('Notifications containing one of these words won\'t be shown at all.', 'wp-admin-notification-center'); ?>
                    </span>
                    <span class="wanc_settings_desc">
                        <?php esc_html_e('Please enter your words separated by commas', 'wp-admin-notification-center'); ?>
                    </span>
                </th>
                <td>
                    <textarea name="wanc_spam_words" id="wanc_spam_words" cols="60" rows="5"><?php echo esc_textarea($data['spam_words']); ?></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wanc_white_list"><?php esc_html_e('White list', 'wp-admin-notification-center'); ?></label>
                    <span class="wanc_settings_desc">
                        <?php esc_html_e('Notifications containing one of these words will show up as usual.', 'wp-admin-notification-center'); ?>
                    </span>
                    <span class="wanc_settings_desc">
                        <?php esc_html_e('Please enter your words separated by commas', 'wp-admin-notification-center'); ?>
                    </span>
                </th>
                <td>
                    <textarea name="wanc_white_list" id="wanc_white_list" cols="60" rows="5"><?php echo esc_textarea($data['white_list']); ?></textarea>
                </td>
            </tr>
        </table>
        <?php submit_button(__('Save settings', 'wp-admin-notification-center')); ?>
    </form>
</div>
