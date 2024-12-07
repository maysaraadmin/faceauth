<?php
namespace auth_faceauth\task;

defined('MOODLE_INTERNAL') || die();

/**
 * General helper functions for the Face Authentication plugin.
 */
class helper {

    /**
     * Get the full name of a user.
     *
     * @param int $userid The user ID.
     * @return string The full name of the user.
     */
    public static function get_user_fullname($userid) {
        global $DB;

        $user = $DB->get_record('user', ['id' => $userid], 'firstname, lastname', MUST_EXIST);
        return fullname($user);
    }

    /**
     * Log a message to the Moodle log.
     *
     * @param string $message The message to log.
     * @param int $level The log level (optional, default is DEBUG_NORMAL).
     */
    public static function log_message($message, $level = DEBUG_NORMAL) {
        mtrace($message, '', $level);
    }
}