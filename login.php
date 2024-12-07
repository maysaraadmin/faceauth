<?php
// This file is part of the Face Authentication plugin for Moodle
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/authlib.php');

// Ensure this script is accessed within Moodle.
defined('MOODLE_INTERNAL') || die();

// Set up the page.
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/auth/faceauth/login.php'));
$PAGE->set_title(get_string('faceauth_login', 'auth_faceauth'));
$PAGE->set_heading(get_string('faceauth_login_heading', 'auth_faceauth'));

// Require JS and CSS for the plugin.
$PAGE->requires->js('/auth/faceauth/faceauth.js');
$PAGE->requires->css('/auth/faceauth/style.css');

// Output the header.
echo $OUTPUT->header();

// Display the face authentication container.
echo '<div id="faceauth-container">';
echo '<h2>' . get_string('faceauth_prompt', 'auth_faceauth') . '</h2>';
echo '<video id="video" autoplay></video>';
echo '<canvas id="canvas" style="display:none;"></canvas>';
echo '<img id="imagePreview" style="display:none;">';
echo '<button id="captureButton">' . get_string('capture_face', 'auth_faceauth') . '</button>';
echo '<button id="retryButton" style="display:none;">' . get_string('retry', 'auth_faceauth') . '</button>';
echo '<button id="submitButton" style="display:none;">' . get_string('submit', 'auth_faceauth') . '</button>';
echo '<div id="loader" style="display:none;">' . get_string('loading', 'auth_faceauth') . '</div>';
echo '<div id="messageBox" style="display:none;"></div>';
echo '</div>';

// Include a fallback link for standard login.
echo '<div class="fallback-login">';
echo '<p>' . get_string('fallback_login_prompt', 'auth_faceauth') . '</p>';
echo '<a href="' . new moodle_url('/login/index.php') . '">' . get_string('fallback_login_link', 'auth_faceauth') . '</a>';
echo '</div>';

// Output the footer.
echo $OUTPUT->footer();