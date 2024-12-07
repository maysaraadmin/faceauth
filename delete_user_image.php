<?php
require_once(__DIR__ . '/../../config.php');

// Ensure this script is accessed within Moodle.
defined('MOODLE_INTERNAL') || die();

// Require the user to be logged in.
require_login();
$PAGE->set_url(new moodle_url('/auth/faceauth/delete_user_image.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('deleteuserimage', 'auth_faceauth'));

// Get the user ID from the request.
$userid = required_param('userid', PARAM_INT);

// Directory to store enrolled face images.
$upload_dir = $CFG->dataroot . '/faceauth/faces/';

// Ensure the upload directory exists.
if (!is_dir($upload_dir)) {
    debugging("Upload directory does not exist: {$upload_dir}", DEBUG_DEVELOPER);
    echo $OUTPUT->notification(get_string('dirdoesnotexist', 'auth_faceauth'), 'notifyproblem');
    exit;
}

// Delete the user's image.
$files = glob($upload_dir . $userid . '_*');
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}

// Output the header.
echo $OUTPUT->header();

// Display the success message.
echo $OUTPUT->notification(get_string('deleteuserimagesuccess', 'auth_faceauth'), 'notifysuccess');

// Output the footer.
echo $OUTPUT->footer();