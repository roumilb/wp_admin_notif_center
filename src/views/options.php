<h2>WordPress Admin Notification Center Settings</h2>
<p>By default this plugin will move all of your admin notification in the notification center</p>
<p>In this settings page you can change that and force the display of some notifications, like errors to not miss them!</p>
<form method="post" action="">
	<p>Notification to display in the notification center:</p>
	<ul>
        <?php foreach ($data['display_settings'] as $notice => $displayNotice) { ?>
			<li>
				<label>
					<input type="checkbox" <?php echo empty(esc_html($displayNotice)) ? '' : 'checked'; ?> name="wanc_display[<?php echo esc_html($notice); ?>]">
                    <?php echo ucfirst(esc_html($notice)); ?> notices
				</label>
			</li>
        <?php } ?>
	</ul>
	<div id="wanc_settings_acl">
		<h3>ACL</h3>
        <?php
        foreach ($data['display_settings_roles'] as $key => $value) {
            ?>
			<div class="wanc_settings_acl_one">
				<label for="wanc_roles_<?php echo $key; ?>"><?php echo $key; ?></label>
				<select name="wanc_roles[<?php echo $key; ?>]" id="wanc_roles_<?php echo $key; ?>">
					<option value="1" <?php echo $value ? 'selected' : ''; ?>>Display notifications</option>
					<option value="0" <?php echo $value ? '' : 'selected'; ?>>Don't display notifications</option>
				</select>
			</div>
            <?php
        }
        ?>
	</div>
	<input type="hidden" name="wanc_display[saved]">
	<button class="button button-primary">Save settings</button>
</form>
