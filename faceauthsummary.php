<?php
require_once(__DIR__ . '/../../config.php');

// Ensure this script is accessed within Moodle.
defined('MOODLE_INTERNAL') || die();

// Require the user to be logged in.
require_login();
$PAGE->set_url(new moodle_url('/auth/faceauth/faceauthsummary.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('faceauthsummary', 'auth_faceauth'));

// Query to fetch summary data.
$success_count = $DB->count_records('auth_faceauth_logs', array('status' => 'success'));
$failure_count = $DB->count_records('auth_faceauth_logs', array('status' => 'failure'));

// Output the header.
echo $OUTPUT->header();

// Display the summary.
echo '<h2>' . get_string('faceauthsummary', 'auth_faceauth') . '</h2>';
echo '<p>' . get_string('successcount', 'auth_faceauth') . ': ' . $success_count . '</p>';
echo '<p>' . get_string('failurecount', 'auth_faceauth') . ': ' . $failure_count . '</p>';

// Output the footer.
echo $OUTPUT->footer();