<?php
/**
 * Library for Face Authentication plugin.
 *
 * @package    auth_faceauth
 * @copyright  2024 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * FaceAuth authentication plugin.
 */
class auth_plugin_faceauth extends auth_plugin_base {

    /**
     * Constructor for the Face Authentication plugin.
     */
    public function __construct() {
        $this->authtype = 'faceauth';
        $this->config = get_config('auth_faceauth');
    }

    /**
     * Authenticate the user using face data.
     *
     * @param string $username The username provided.
     * @param string $password The password provided (ignored for faceauth).
     * @return bool True if authentication is successful, false otherwise.
     */
    public function user_login($username, $password) {
        global $DB;

        // Fetch user record.
        $user = $DB->get_record('user', ['username' => $username, 'auth' => 'faceauth']);
        if (!$user) {
            return false;
        }

        // Here, you can add logic to verify the face data stored in the session or sent from the client.
        $facedata_verified = $this->verify_face_data($username);
        return $facedata_verified;
    }

    /**
     * Verify the face data for the user.
     *
     * @param string $username The username of the user.
     * @return bool True if the face data is valid, false otherwise.
     */
    private function verify_face_data($username) {
        // Example implementation. Replace with your face verification logic.
        // For instance, use APIs or libraries to match face data from the client with stored data.
        return !empty($_SESSION['face_verified']) && $_SESSION['face_verified'] === true;
    }

    /**
     * Checks if the plugin allows manual login.
     *
     * @return bool False to disable manual login, true to allow it.
     */
    public function can_manual_login() {
        return false; // Face authentication does not support manual login.
    }

    /**
     * Hook to prevent users from changing their authentication method.
     *
     * @return bool False to prevent users from changing their authentication method.
     */
    public function prevent_local_passwords() {
        return true;
    }

    /**
     * Update user record during authentication.
     *
     * @param object $user User object retrieved from the database.
     * @param string $username Username used for login.
     * @param string $password Password (not used in faceauth).
     * @return void
     */
    public function user_authenticated_hook(&$user, $username, $password) {
        // Perform any additional operations on the user record upon successful authentication.
    }

    /**
     * Determines if this plugin supports logout.
     *
     * @return bool True if logout is supported.
     */
    public function logoutpage_hook() {
        // Cleanup any session data related to face authentication.
        unset($_SESSION['face_verified']);
        return true;
    }

    /**
     * Hook for configuring settings on the admin page.
     *
     * @param admin_settingpage $settings The settings page for this plugin.
     * @param moodle_url $redirect The redirect URL after settings are saved.
     * @return void
     */
    public function config_settings(&$settings, $redirect) {
        global $CFG;

        // Add configuration settings here if needed.
        $settings->add(new admin_setting_configtext(
            'auth_faceauth/api_url',
            get_string('api_url', 'auth_faceauth'),
            get_string('api_url_desc', 'auth_faceauth'),
            '',
            PARAM_URL
        ));
        $settings->add(new admin_setting_configcheckbox(
            'auth_faceauth/enable_logging',
            get_string('enable_logging', 'auth_faceauth'),
            get_string('enable_logging_desc', 'auth_faceauth'),
            0
        ));
    }

    /**
     * Hook to test authentication settings.
     *
     * @param string $username The username to test authentication with.
     * @return string|null Error message or null if no error.
     */
    public function test_settings() {
        return null; // No specific test is implemented yet.
    }
}
