<?php
defined('MOODLE_INTERNAL') || die();

$settings->add(new admin_setting_configcheckbox(
    'auth_faceauth/enablelogging',
    get_string('enablelogging', 'auth_faceauth'),
    get_string('enablelogging_desc', 'auth_faceauth'),
    0
));

$settings->add(new admin_setting_configtext(
    'auth_faceauth/loglevel',
    get_string('loglevel', 'auth_faceauth'),
    get_string('loglevel_desc', 'auth_faceauth'),
    'INFO',
    PARAM_TEXT
));

$settings->add(new admin_setting_configtext(
    'auth_faceauth/facedata_path',
    get_string('facedata_path', 'auth_faceauth'),
    get_string('facedata_path_desc', 'auth_faceauth'),
    '/path/to/faces',
    PARAM_TEXT
));