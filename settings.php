<?php
// Ensure this script is accessed within Moodle.
defined('MOODLE_INTERNAL') || die();

// Add a setting for the facial data storage path.
$settings->add(new admin_setting_configtext(
    'auth_faceauth/facedata_path',           // Setting name (used internally).
    get_string('facedata_path', 'auth_faceauth'),       // Display name (localized string).
    get_string('facedata_path_desc', 'auth_faceauth'),  // Description for the setting (localized string).
    '/path/to/faces',                        // Default value for the setting.
    PARAM_TEXT                               // Data validation type (string input).
));

// Log the addition of the setting.
debugging("Added setting 'auth_faceauth/facedata_path'", DEBUG_DEVELOPER);