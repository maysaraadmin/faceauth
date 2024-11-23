<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/authlib.php');

/**
 * Face Authentication Plugin
 */
class auth_plugin_faceauth extends auth_plugin_base {

    /**
     * Hook to include custom scripts/styles for the login page.
     */
    public function loginpage_hook() {
        global $PAGE;

        // Include custom JS and CSS to handle webcam and face authentication.
        $PAGE->requires->js('/auth/faceauth/faceauth.js');
        $PAGE->requires->css('/auth/faceauth/style.css');
    }

    /**
     * Authenticate user via username/password fallback or facial recognition.
     *
     * @param string $username The username.
     * @param string $password The password.
     * @return bool True if authentication succeeds, false otherwise.
     */
    public function user_login($username, $password) {
        global $CFG;

        // First try the fallback authentication (username/password)
        if (parent::user_login($username, $password)) {
            return true;
        }

        // Path to the facial recognition script.
        $face_recognition_script = $CFG->dirroot . '/auth/faceauth/face_recognition.py';

        // Ensure the facial recognition script exists
        if (!file_exists($face_recognition_script)) {
            debugging("Face recognition script not found: {$face_recognition_script}", DEBUG_DEVELOPER);
            return false;
        }

        // Ensure Python is available in the system's PATH
        $python_path = trim(shell_exec('which python3'));
        if (empty($python_path)) {
            debugging("Python3 is not available in the system path.", DEBUG_DEVELOPER);
            return false;
        }

        // Sanitize username to prevent injection
        $username = escapeshellarg($username);

        // Prepare the command to execute the facial recognition script securely
        $command = escapeshellcmd("python3 {$face_recognition_script} {$username}");
        $output = shell_exec($command);

        // Handle script execution errors
        if ($output === null) {
            debugging("Error executing face recognition script: {$command}", DEBUG_DEVELOPER);
            return false;
        }

        // Match the script output for success
        if (trim($output) === 'MATCH') {
            return true;
        } else {
            debugging("Face recognition failed for username: {$username}. Output: {$output}", DEBUG_DEVELOPER);
            return false;
        }
    }
}
