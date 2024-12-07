<?php
require_once(__DIR__ . '/../../config.php');

// Ensure this script is accessed within Moodle.
defined('MOODLE_INTERNAL') || die();

// Require the user to be logged in.
require_login();
$PAGE->set_url(new moodle_url('/auth/faceauth/report.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('faceauthreport', 'auth_faceauth'));

// Query to fetch face authentication logs.
$logs = $DB->get_records('auth_faceauth_logs', null, 'attempt_time DESC');

// Output the header.
echo $OUTPUT->header();

// Display the report.
echo '<h2>' . get_string('faceauthreport', 'auth_faceauth') . '</h2>';
echo '<table class="generaltable">';
echo '<tr><th>User ID</th><th>Status</th><th>Attempt Time</th><th>IP Address</th><th>Error Message</th></tr>';
foreach ($logs as $log) {
    echo '<tr>';
    echo '<td>' . $log->userid . '</td>';
    echo '<td>' . $log->status . '</td>';
    echo '<td>' . userdate($log->attempt_time) . '</td>';
    echo '<td>' . $log->ip_address . '</td>';
    echo '<td>' . $log->error_message . '</td>';
    echo '</tr>';
}
echo '</table>';

// Output the footer.
echo $OUTPUT->footer();