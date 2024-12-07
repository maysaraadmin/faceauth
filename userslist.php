<?php
require_once(__DIR__ . '/../../config.php');

// Ensure this script is accessed within Moodle.
defined('MOODLE_INTERNAL') || die();

// Require the user to be logged in.
require_login();
$PAGE->set_url(new moodle_url('/auth/faceauth/userslist.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('userslist', 'auth_faceauth'));

// Query to fetch users with enrolled face images.
$users = $DB->get_records_sql('SELECT u.id, u.username, u.firstname, u.lastname 
                              FROM {user} u 
                              JOIN {auth_faceauth_metadata} fm ON u.id = fm.userid');

// Prepare the data for the template.
$template_data = [
    'users' => array_map(function($user) {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'image_path' => '/path/to/user/images/' . $user->id . '.jpg', // Adjust the path as needed
            'wwwroot' => $CFG->wwwroot
        ];
    }, $users)
];

// Render the template.
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('auth_faceauth/users_list', $template_data);
echo $OUTPUT->footer();