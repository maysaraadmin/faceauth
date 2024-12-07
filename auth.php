<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/authlib.php');

/**
 * Face Authentication Plugin
 */
class auth_plugin_faceauth extends auth_plugin_base {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'faceauth'; // Set the authentication type.
        $this->config = get_config('auth_faceauth'); // Load plugin configuration.
    }

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
        $command = escapeshellcmd("{$python_path} {$face_recognition_script} {$username}");
        exec($command, $output, $return_var);

        // Handle script execution errors
        if ($return_var !== 0) {
            debugging("Error executing face recognition script: {$command}", DEBUG_DEVELOPER);
            return false;
        }

        // Match the script output for success
        if (trim(implode("\n", $output)) === 'MATCH') {
            return true;
        } else {
            debugging("Face recognition failed for username: {$username}. Output: " . implode("\n", $output), DEBUG_DEVELOPER);
            return false;
        }
    }

    /**
     * Prevent standard login for face-authenticated users.
     *
     * @param string $username The username.
     * @param string $password The password.
     * @return bool True if standard login is allowed, false otherwise.
     */
    public function prevent_local_passwords() {
        return true;
    }

    /**
     * Indicates if the authentication plugin can change the user's password.
     *
     * @return bool True if the plugin can change the password, false otherwise.
     */
    public function can_change_password() {
        return false;
    }

    /**
     * Returns the URL for changing the user's password, or empty if the default URL should be used.
     *
     * @return moodle_url|null The URL for changing the password, or null.
     */
    public function change_password_url() {
        return null;
    }

    /**
     * Indicates if the authentication plugin can edit the user's profile.
     *
     * @return bool True if the plugin can edit the profile, false otherwise.
     */
    public function can_edit_profile() {
        return true;
    }

    /**
     * Returns the URL for editing the user's profile, or empty if the default URL should be used.
     *
     * @return moodle_url|null The URL for editing the profile, or null.
     */
    public function edit_profile_url() {
        return null;
    }

    /**
     * Indicates if the authentication plugin can reset the user's password.
     *
     * @return bool True if the plugin can reset the password, false otherwise.
     */
    public function can_reset_password() {
        return false;
    }

    /**
     * Returns the URL for resetting the user's password, or empty if the default URL should be used.
     *
     * @return moodle_url|null The URL for resetting the password, or null.
     */
    public function reset_password_url() {
        return null;
    }

    /**
     * Indicates if the authentication plugin can signup new users.
     *
     * @return bool True if the plugin can signup new users, false otherwise.
     */
    public function can_signup() {
        return false;
    }

    /**
     * Returns the URL for signing up new users, or empty if the default URL should be used.
     *
     * @return moodle_url|null The URL for signing up new users, or null.
     */
    public function signup_url() {
        return null;
    }

    /**
     * Indicates if the authentication plugin can confirm new users.
     *
     * @return bool True if the plugin can confirm new users, false otherwise.
     */
    public function can_confirm() {
        return false;
    }

    /**
     * Returns the URL for confirming new users, or empty if the default URL should be used.
     *
     * @return moodle_url|null The URL for confirming new users, or null.
     */
    public function confirm_url() {
        return null;
    }

    /**
     * Indicates if the authentication plugin can logout users.
     *
     * @return bool True if the plugin can logout users, false otherwise.
     */
    public function can_logout() {
        return true;
    }

    /**
     * Returns the URL for logging out users, or empty if the default URL should be used.
     *
     * @return moodle_url|null The URL for logging out users, or null.
     */
    public function logout_url() {
        return null;
    }

    /**
     * Indicates if the authentication plugin can login users.
     *
     * @return bool True if the plugin can login users, false otherwise.
     */
    public function can_login() {
        return true;
    }

    /**
     * Returns the URL for logging in users, or empty if the default URL should be used.
     *
     * @return moodle_url|null The URL for logging in users, or null.
     */
    public function login_url() {
        return null;
    }

    /**
     * Indicates if the authentication plugin can view the user's profile.
     *
     * @return bool True if the plugin can view the profile, false otherwise.
     */
    public function can_view_profile() {
        return true;
    }

}