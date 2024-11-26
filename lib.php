<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Face authentication repository plugin for Moodle.
 *
 * @package    auth_faceauth
 * @copyright  2024 [Your Name]
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/repository/lib.php');

/**
 * Repository plugin for handling facial uploads.
 *
 * @package    auth_faceauth
 */
class repository_upload_face extends repository {
    private $mimetypes = array();

    /**
     * Print the login form.
     *
     * @return array
     */
    public function print_login() {
        return $this->get_listing();
    }

    /**
     * Process uploaded facial data.
     *
     * @param string $saveas_filename File name to save as.
     * @param int $maxbytes Maximum allowed file size.
     * @return array|bool
     */
    public function upload($saveas_filename, $maxbytes) {
        global $CFG;

        $types = optional_param('accepted_types', '*', PARAM_RAW);
        $savepath = optional_param('savepath', '/', PARAM_PATH);
        $itemid = optional_param('itemid', 0, PARAM_INT);
        $license = optional_param('license', $CFG->sitedefaultlicense, PARAM_TEXT);
        $author = optional_param('author', '', PARAM_TEXT);
        $areamaxbytes = optional_param('areamaxbytes', FILE_AREA_MAX_BYTES_UNLIMITED, PARAM_INT);
        $overwriteexisting = optional_param('overwrite', false, PARAM_BOOL);

        return $this->process_upload_face($saveas_filename, $maxbytes, $types, $savepath, $itemid, $license, $author, $overwriteexisting, $areamaxbytes);
    }

    /**
     * Handles the actual file upload process.
     *
     * @param string $saveas_filename File name to save as.
     * @param int $maxbytes Maximum allowed file size.
     * @param mixed $types Accepted file types.
     * @param string $savepath Path to save the file to.
     * @param int $itemid ID for the file item.
     * @param string $license License for the file.
     * @param string $author Author of the file.
     * @param bool $overwriteexisting Overwrite flag.
     * @param int $areamaxbytes Maximum size for the file area.
     * @return array Information about the uploaded file.
     */
    public function process_upload_face($saveas_filename, $maxbytes, $types = '*', $savepath = '/', $itemid = 0,
                                        $license = null, $author = '', $overwriteexisting = false, $areamaxbytes = FILE_AREA_MAX_BYTES_UNLIMITED) {
        global $USER, $CFG;

        $context = context_user::instance($USER->id); // Updated to use context_user.
        $fs = get_file_storage();

        if (empty($types) || $types == '*') {
            $this->mimetypes = '*';
        } else {
            foreach ((array) $types as $type) {
                $this->mimetypes[] = mimeinfo('type', $type);
            }
        }

        $record = (object) [
            'filearea' => 'draft',
            'component' => 'user',
            'filepath' => file_correct_filepath($savepath),
            'itemid' => $itemid,
            'license' => $license ?? $CFG->sitedefaultlicense,
            'author' => $author,
            'filename' => $saveas_filename . '.jpg',
            'contextid' => $context->id,
            'userid' => $USER->id,
        ];

        // Simulate file creation for testing.
        $testfile = "/var/moodledata20/{$saveas_filename}.jpg";
        if (!file_exists($testfile)) {
            return false; // File does not exist; abort.
        }

        $stored_file = $fs->create_file_from_pathname($record, $testfile);
        unlink($testfile); // Clean up temporary file.

        return [
            'url' => moodle_url::make_draftfile_url($record->itemid, $record->filepath, $record->filename)->out(false),
            'id' => $record->itemid,
            'file' => $record->filename,
        ];
    }

    /**
     * Get a listing for the repository.
     *
     * @param string $path Current path.
     * @param string $page Current page.
     * @return array Repository listing.
     */
    public function get_listing($path = '', $page = '') {
        return [
            'nologin' => true,
            'nosearch' => true,
            'norefresh' => true,
            'list' => [],
            'dynload' => false,
            'upload' => ['label' => get_string('attachment', 'repository'), 'id' => 'repo-form-face'],
            'allowcaching' => true,
        ];
    }

    /**
     * Supported return types.
     *
     * @return int
     */
    public function supported_returntypes() {
        return FILE_INTERNAL;
    }
}
