<?php
defined('MOODLE_INTERNAL') || die();

// Add a setting for the facial data storage path.
$settings->add(new admin_setting_configtext(
    'auth_faceauth/facedata_path',
    get_string('facedata_path', 'auth_faceauth'), // Language string for setting name.
    get_string('facedata_path_desc', 'auth_faceauth'), // Language string for setting description.
    '/path/to/faces', // Default path.
    PARAM_TEXT // Validation type.
));
