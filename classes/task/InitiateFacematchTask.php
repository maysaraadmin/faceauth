<?php
namespace auth_faceauth\task;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/auth/faceauth/classes/facematch.php');

/**
 * Scheduled task for initiating face matching operations.
 */
class InitiateFacematchTask extends \core\task\scheduled_task {

    /**
     * Get the name of the task.
     *
     * @return string
     */
    public function get_name() {
        return get_string('initiatefacemathtask', 'auth_faceauth');
    }

    /**
     * Execute the task.
     */
    public function execute() {
        global $DB;

        mtrace("Initiating face matching task...");

        // Fetch users with enrolled face images.
        $users = $DB->get_records_sql('SELECT u.id, u.username, fm.face_data 
                                      FROM {user} u 
                                      JOIN {auth_faceauth_metadata} fm ON u.id = fm.userid');

        if (empty($users)) {
            mtrace("No users with enrolled face images found.");
            return;
        }

        // Initialize the face matching service.
        $facematch = new \auth_faceauth\facematch();

        foreach ($users as $user) {
            mtrace("Initiating face match for user: {$user->username} (ID: {$user->id})");

            // Initiate face matching.
            $result = $facematch->initiate_match($user->face_data);

            if ($result['success']) {
                mtrace("Face match initiation successful for user: {$user->username}");
            } else {
                mtrace("Face match initiation failed for user: {$user->username}. Error: {$result['error']}");
            }
        }

        mtrace("Face matching initiation task completed.");
    }
}