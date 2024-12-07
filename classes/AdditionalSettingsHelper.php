<?php
namespace auth_faceauth\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Helper class for managing additional settings.
 */
class AdditionalSettingsHelper {

    /**
     * Get the value of a specific setting.
     *
     * @param string $settingname The name of the setting.
     * @return mixed The value of the setting.
     */
    public static function get_setting($settingname) {
        return get_config('auth_faceauth', $settingname);
    }

    /**
     * Set the value of a specific setting.
     *
     * @param string $settingname The name of the setting.
     * @param mixed $value The value to set.
     */
    public static function set_setting($settingname, $value) {
        set_config($settingname, $value, 'auth_faceauth');
    }

    /**
     * Check if a specific setting is enabled.
     *
     * @param string $settingname The name of the setting.
     * @return bool True if the setting is enabled, false otherwise.
     */
    public static function is_setting_enabled($settingname) {
        return (bool) self::get_setting($settingname);
    }
}