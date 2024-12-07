<?php
namespace auth_faceauth\task;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * External functions for the Face Authentication plugin.
 */
class external extends \external_api {

    /**
     * Returns description of method parameters.
     *
     * @return \external_function_parameters
     */
    public static function get_face_data_parameters() {
        return new \external_function_parameters([
            'userid' => new \external_value(PARAM_INT, 'The user ID')
        ]);
    }

    /**
     * Get face data for a specific user.
     *
     * @param int $userid The user ID.
     * @return array The face data.
     */
    public static function get_face_data($userid) {
        global $DB;

        $params = self::validate_parameters(self::get_face_data_parameters(), ['userid' => $userid]);

        $user = $DB->get_record('user', ['id' => $params['userid']], '*', MUST_EXIST);
        self::validate_context(\context_user::instance($user->id));

        $face_data = $DB->get_field('auth_faceauth_metadata', 'face_data', ['userid' => $user->id]);

        return ['face_data' => $face_data];
    }

    /**
     * Returns description of method result value.
     *
     * @return \external_description
     */
    public static function get_face_data_returns() {
        return new \external_single_structure([
            'face_data' => new \external_value(PARAM_TEXT, 'The face data')
        ]);
    }
}