<?php
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/form/upload_image_form.php');

// Ensure this script is accessed within Moodle.
defined('MOODLE_INTERNAL') || die();

// Require the user to be logged in.
require_login();
$PAGE->set_url(new moodle_url('/auth/faceauth/upload_image.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('uploadimage', 'auth_faceauth'));

// Instantiate the form.
$form = new \auth_faceauth\form\upload_image_form();

// Process the form submission.
if ($form->is_submitted() && $form->is_validated()) {
    $data = $form->get_data();
    $file = $form->get_draft_file('face_image');

    if ($file) {
        // Directory to store enrolled face images.
        $upload_dir = $CFG->dataroot . '/faceauth/faces/';

        // Ensure the upload directory exists and is writable.
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                debugging("Failed to create upload directory: {$upload_dir}", DEBUG_DEVELOPER);
                echo $OUTPUT->notification(get_string('dircreationsuccess', 'auth_faceauth'), 'notifyproblem');
                exit;
            }
        }

        // Move the uploaded file to the storage directory.
        $file_extension = strtolower(pathinfo($file->get_filename(), PATHINFO_EXTENSION));
        $upload_path = $upload_dir . $USER->username . '_' . time() . '.' . $file_extension;
        $file->copy_content_to($upload_path);

        // Display success message.
        echo $OUTPUT->notification(get_string('uploadsuccess', 'auth_faceauth'), 'notifysuccess');
    } else {
        // Display error message.
        echo $OUTPUT->notification(get_string('uploadfailed', 'auth_faceauth'), 'notifyproblem');
    }
}

// Output the header.
echo $OUTPUT->header();

// Display the form.
$form->display();

// Output the footer.
echo $OUTPUT->footer();