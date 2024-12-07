<?php
require_once(__DIR__ . '/../../config.php');

// Ensure this script is accessed within Moodle.
defined('MOODLE_INTERNAL') || die();

// Require the user to be logged in.
require_login();
$PAGE->set_url(new moodle_url('/auth/faceauth/enrollment.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('faceenrollment', 'auth_faceauth'));

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

// Process the form submission.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['face_image'])) {
    $username = clean_param($USER->username, PARAM_USERNAME); // Ensure username is safe.
    $uploaded_file = $_FILES['face_image'];
    $file_extension = pathinfo($uploaded_file['name'], PATHINFO_EXTENSION);
    $upload_path = $upload_dir . $username . '_' . time() . '.' . $file_extension; // Prevent overwriting.

    // Validate the uploaded file.
    if ($uploaded_file['error'] !== UPLOAD_ERR_OK) {
        debugging("Upload error: " . $uploaded_file['error'], DEBUG_DEVELOPER);
        echo $OUTPUT->notification(get_string('uploaderror', 'auth_faceauth'), 'notifyproblem');
    } elseif (!in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
        debugging("Invalid image format: {$file_extension}", DEBUG_DEVELOPER);
        echo $OUTPUT->notification(get_string('invalidimageformat', 'auth_faceauth'), 'notifyproblem');
    } elseif ($uploaded_file['size'] > 5000000) { // Limit size to 5MB.
        debugging("File size exceeded: " . $uploaded_file['size'], DEBUG_DEVELOPER);
        echo $OUTPUT->notification(get_string('filesizeexceeded', 'auth_faceauth'), 'notifyproblem');
    } elseif (!in_array(mime_content_type($uploaded_file['tmp_name']), ['image/jpeg', 'image/png'])) {
        debugging("Invalid MIME type: " . mime_content_type($uploaded_file['tmp_name']), DEBUG_DEVELOPER);
        echo $OUTPUT->notification(get_string('invalidimageformat', 'auth_faceauth'), 'notifyproblem');
    } elseif (!move_uploaded_file($uploaded_file['tmp_name'], $upload_path)) {
        debugging("Failed to move uploaded file to: {$upload_path}", DEBUG_DEVELOPER);
        echo $OUTPUT->notification(get_string('uploadfailed', 'auth_faceauth'), 'notifyproblem');
    } else {
        debugging("File uploaded successfully: {$upload_path}", DEBUG_DEVELOPER);
        echo $OUTPUT->notification(get_string('enrollsuccess', 'auth_faceauth'), 'notifysuccess');
    }
}

// Display the form.
echo $OUTPUT->header();
?>
<form method="post" enctype="multipart/form-data">
    <label for="face_image"><?php echo get_string('uploadfaceimage', 'auth_faceauth'); ?></label>
    <input type="file" name="face_image" id="face_image" accept="image/jpeg, image/png" required>
    <button type="submit"><?php echo get_string('enroll', 'auth_faceauth'); ?></button>
</form>
<?php
echo $OUTPUT->footer();
?>