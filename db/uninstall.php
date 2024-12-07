<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Uninstall function for the Face Authentication plugin.
 */
function xmldb_auth_faceauth_uninstall() {
    global $DB;

    // Delete all plugin data from the database.
    $DB->delete_records('auth_faceauth_metadata');
    $DB->delete_records('auth_faceauth_logs');

    // Remove any additional data or files if necessary.
    $upload_dir = $CFG->dataroot . '/faceauth/faces/';
    if (is_dir($upload_dir)) {
        $files = glob($upload_dir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        rmdir($upload_dir);
    }

    // Remove any configuration settings.
    unset_all_config_for_plugin('auth_faceauth');

    return true;
}