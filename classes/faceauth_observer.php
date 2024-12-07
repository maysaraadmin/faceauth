<?php
namespace auth_faceauth\task;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/auth/faceauth/classes/facematch.php');

/**
 * Event observer for the Face Authentication plugin.
 */
class faceauth_observer {

    /**
     * Event handler for user login.
     *
     * @param \core\event\user_loggedin $event The event.
     */
    public static function user_loggedin(\core\event\user_loggedin $event) {
        global $DB;

        $user = $DB->get_record('user', ['id' => $event->userid], '*', MUST_EXIST);
        $face_data = $DB->get_field('auth_faceauth_metadata', 'face_data', ['userid' => $user->id]);

        if ($face_data) {
            $facematch = new \auth_faceauth\facematch();
            $result = $facematch->match_face($face_data);

            if ($result['success']) {
                mtrace("Face match successful for user: {$user->username}");
            } else {
                mtrace("Face match failed for user: {$user->username}. Error: {$result['error']}");
            }
        }
    }

    /**
     * Event handler for user logout.
     *
     * @param \core\event\user_loggedout $event The event.
     */
    public static function user_loggedout(\core\event\user_loggedout $event) {
        mtrace("User logged out: {$event->userid}");
    }
}