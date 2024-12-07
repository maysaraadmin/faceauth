<?php
namespace auth_faceauth\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Screenshot capture functions for the Face Authentication plugin.
 */
class screenshot {

    /**
     * Capture a screenshot of the current page.
     *
     * @return string The base64-encoded screenshot.
     */
    public static function capture_screenshot() {
        global $PAGE;

        $screenshot = $PAGE->get_renderer('core')->render_page_screenshot();
        return base64_encode($screenshot);
    }

    /**
     * Save a screenshot to a file.
     *
     * @param string $screenshot The base64-encoded screenshot.
     * @param string $filename The filename to save the screenshot as.
     * @return bool True if the screenshot was saved successfully, false otherwise.
     */
    public static function save_screenshot($screenshot, $filename) {
        $decoded_screenshot = base64_decode($screenshot);
        return file_put_contents($filename, $decoded_screenshot) !== false;
    }
}