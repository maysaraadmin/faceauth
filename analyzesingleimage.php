<?php
require_once(__DIR__ . '/../../config.php');

// Ensure this script is accessed within Moodle.
defined('MOODLE_INTERNAL') || die();

// Require the user to be logged in.
require_login();
$PAGE->set_url(new moodle_url('/auth/faceauth/analyzesingleimage.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('analyzesingleimage', 'auth_faceauth'));

// Get the image path from the request.
$image_path = required_param('image_path', PARAM_RAW);

// Directory to store enrolled face images.
$upload_dir = $CFG->dataroot . '/faceauth/faces/';

// Ensure the upload directory exists.
if (!is_dir($upload_dir)) {
    debugging("Upload directory does not exist: {$upload_dir}", DEBUG_DEVELOPER);
    echo $OUTPUT->notification(get_string('dirdoesnotexist', 'auth_faceauth'), 'notifyproblem');
    exit;
}

// Ensure the image exists.
if (!file_exists($image_path)) {
    debugging("Image does not exist: {$image_path}", DEBUG_DEVELOPER);
    echo $OUTPUT->notification(get_string('imagedoesnotexist', 'auth_faceauth'), 'notifyproblem');
    exit;
}

// Analyze the image.
$analysis_result = analyze_image($image_path);

// Output the header.
echo $OUTPUT->header();

// Display the analysis result.
echo '<h2>' . get_string('analyzesingleimage', 'auth_faceauth') . '</h2>';
echo '<p>' . get_string('analysisresult', 'auth_faceauth') . ': ' . $analysis_result . '</p>';

// Output the footer.
echo $OUTPUT->footer();

/**
 * Analyze the image.
 *
 * @param string $image_path The path to the image.
 * @return string The analysis result.
 */
function analyze_image($image_path) {
    // Placeholder for actual image analysis logic.
    return 'Image analyzed successfully.';
}