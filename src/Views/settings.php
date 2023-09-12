<h2><?php echo __('WordPress Admin Notification Center WancSettings', 'wanc') ?></h2>
<p><?php echo __('By default this plugin will move all of your admin notification in the notification center', 'wanc'); ?></p>
<p><?php echo __('In this settings page you can change that and force the display of some notifications, like errors to not miss them!', 'wanc'); ?></p>
<form method="post" action="">
    <input name="wanc_nonce" type="hidden" value="<?=wp_create_nonce('wanc_nonce')?>" />
    <table class="form-table" role="presentation" id="wanc_settings">
        <tr>
            <th scope="row">
                <label>
                    <?php echo __('Notification to display in the notification center:', 'wanc'); ?>
                </label>
            </th>
            <td>
                <ul style="list-style: none">
                    <?php foreach ($data['display_settings'] as $notice => $displayNotice) { ?>
                        <li>
                            <label>
                                <input type="checkbox" <?php echo empty(esc_html($displayNotice)) ? '' : 'checked'; ?> name="wanc_display[<?php echo esc_html($notice); ?>]">
                                <?php echo ucfirst(esc_html($notice)); ?> notices
                            </label>
                        </li>
                    <?php } ?>
                </ul>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label>
                    <?php echo __('ACL', 'wanc'); ?>
                </label>
                <span class="wanc_settings_desc">
                    <?php echo __('This option allows you to choose which user group can see the notifications in the dashboard', 'wanc'); ?>
                </span>
            </th>
            <td id="wanc_settings_acl">
                <?php
                foreach ($data['display_settings_roles'] as $key => $value) {
                    ?>
                    <div class="wanc_settings_acl_one">
                        <label for="wanc_roles_<?php echo $key; ?>"><?php echo $key; ?></label>
                        <select name="wanc_roles[<?php echo $key; ?>]" id="wanc_roles_<?php echo $key; ?>">
                            <option value="1" <?php echo $value ? 'selected' : ''; ?>><?php echo __('Display notifications', 'wanc') ?></option>
                            <option value="0" <?php echo $value ? '' : 'selected'; ?>><?php echo __('Do not display notifications', 'wanc') ?></option>
                        </select>
                    </div>
                    <?php
                }
                ?>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label>
                    <?php echo __('Spam words', 'wanc') ?>
                </label>
                <span class="wanc_settings_desc">
                    <?php echo __('This option allows you to set several words considered as spam, this way notifications with those words won\'t be shown at all.', 'wanc'); ?>
                </span>
                <span class="wanc_settings_desc">
                    <?php echo __('Please enter your words separated by comas', 'wanc'); ?>
                </span>
            </th>
            <td>
                <textarea name="wanc_spam_words" id="wanc_spam_words" cols="60" rows="5"><?php echo $data['spam_words']; ?></textarea>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label>
                    <?php echo __('White list', 'wanc') ?>
                </label>
                <span class="wanc_settings_desc">
                    <?php echo __(
                        'This option allows you to set several words in the white list, this means that every notification containing one of this words will show up as usual',
                        'wanc'
                    ); ?>
                </span>
                <span class="wanc_settings_desc">
                    <b><?php echo __('Please enter your words separated by comas', 'wanc'); ?></b>
                </span>
            </th>
            <td>
                <textarea name="wanc_white_list" id="wanc_white_list" cols="60" rows="5"><?php echo $data['white_list']; ?></textarea>
            </td>
        </tr>
    </table>
	<input type="hidden" name="wanc_display[saved]">
	<button class="button button-primary"><?php echo __('Save settings', 'wanc') ?></button>
</form>
