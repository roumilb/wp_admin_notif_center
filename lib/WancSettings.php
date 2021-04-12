<?php


namespace WANC;


class WancSettings
{
    public function getOption($option, $default = '')
    {
        $return = get_option($option);

        if (empty($return)) return $default;

        $optionJson = json_decode($return, true);
        if (json_last_error() === JSON_ERROR_NONE) return $optionJson;

        return $return;
    }

    public function updateOption($option, $value)
    {
        if (get_option($option) === false) {
            return add_option($option, $value);
        } else {
            return update_option($option, $value);
        }
    }

    public function currentUserAllowed()
    {
        $rolesSettings = $this->getOption('wanc_display_settings_roles', []);
        $isAllowed = false;
        if (empty($rolesSettings)) {
            $isAllowed = true;
        } else {
            $user = wp_get_current_user();
            $userRoles = $user->roles;
            foreach ($userRoles as $role) {
                if ($rolesSettings[$role]) {
                    $isAllowed = true;
                    break;
                }
            }
        }

        return $isAllowed;
    }
}
