<?php
namespace auth_faceauth\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Link generator for the Face Authentication plugin.
 */
class LinkGenerator {

    /**
     * Generate a link to the user's face image.
     *
     * @param int $userid The user ID.
     * @return string The URL to the user's face image.
     */
    public static function generate_face_image_link($userid) {
        global $CFG;

        return $CFG->wwwroot . '/auth/faceauth/view_face_image.php?userid=' . $userid;
    }

    /**
     * Generate a link to the user's profile.
     *
     * @param int $userid The user ID.
     * @return string The URL to the user's profile.
     */
    public static function generate_profile_link($userid) {
        global $CFG;

        return $CFG->wwwroot . '/user/profile.php?id=' . $userid;
    }
}