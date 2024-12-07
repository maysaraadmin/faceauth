<?php
require_once(__DIR__ . '/../../config.php');

// Ensure this script is accessed within Moodle.
defined('MOODLE_INTERNAL') || die();

// Require the user to be logged in.
require_login();
$PAGE->set_url(new moodle_url('/auth/faceauth/deleteallimages.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('deleteallimages', 'auth_faceauth'));

// Directory to store enrolled face images.
$upload_dir = $CFG->dataroot . '/faceauth/faces/';

// Ensure the upload directory exists.
if (!is_dir($upload_dir)) {
    debugging("Upload directory does not exist: {$upload_dir}", DEBUG_DEVELOPER);
    echo $OUTPUT->notification(get_string('dirdoesnotexist', 'auth_faceauth'), 'notifyproblem');
    exit;
}

// Delete all images in the directory.
$files = glob($upload_dir . '*');
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}

// Output the header.
echo $OUTPUT->header();

// Display the success message.
echo $OUTPUT->notification(get_string('deleteallimagessuccess', 'auth_faceauth'), 'notifysuccess');

// Output the footer.
echo $OUTPUT->footer();