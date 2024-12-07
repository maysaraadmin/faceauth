<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Custom rule for face authentication.
 */
class auth_faceauth_rule {
    /**
     * Check if the user has enrolled their face image.
     *
     * @param int $userid The user ID.
     * @return bool True if the user has enrolled their face image, false otherwise.
     */
    public static function has_enrolled_face($userid) {
        global $DB;
        return $DB->record_exists('auth_faceauth_metadata', array('userid' => $userid));
    }
}