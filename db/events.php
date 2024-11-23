<?php
/**
 * Events for the Face Authentication plugin.
 *
 * @package    auth_faceauth
 * @copyright  2024 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$observers = [
    // Observer for successful face enrollment
    [
        'eventname' => '\core\event\user_created',
        'callback' => 'auth_faceauth_observer::user_face_enrolled',
        'includefile' => '/auth/faceauth/classes/observer.php',
        'priority' => 1000,
    ],

    // Observer for failed face authentication
    [
        'eventname' => '\core\event\user_login_failed',
        'callback' => 'auth_faceauth_observer::faceauth_failed',
        'includefile' => '/auth/faceauth/classes/observer.php',
        'priority' => 1000,
    ],

    // Observer for successful face authentication
    [
        'eventname' => '\core\event\user_loggedin',
        'callback' => 'auth_faceauth_observer::faceauth_success',
        'includefile' => '/auth/faceauth/classes/observer.php',
        'priority' => 1000,
    ],
];
