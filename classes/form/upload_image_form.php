<?php
namespace auth_faceauth\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Form for uploading face images.
 */
class upload_image_form extends \moodleform {

    /**
     * Define the form elements.
     */
    protected function definition() {
        $mform = $this->_form;

        // File upload element.
        $mform->addElement('filepicker', 'face_image', get_string('uploadfaceimage', 'auth_faceauth'), null, ['accepted_types' => ['.jpg', '.jpeg', '.png']]);
        $mform->addRule('face_image', get_string('required'), 'required', null, 'client');

        // Submit button.
        $this->add_action_buttons(true, get_string('upload', 'auth_faceauth'));
    }

    /**
     * Validate the form data.
     *
     * @param array $data Form data.
     * @param array $files Form files.
     * @return array Errors.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Validate file type.
        $file = $this->get_draft_file('face_image');
        if ($file) {
            $file_extension = strtolower(pathinfo($file->get_filename(), PATHINFO_EXTENSION));
            if (!in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
                $errors['face_image'] = get_string('invalidimageformat', 'auth_faceauth');
            }
        }

        // Validate file size.
        if ($file && $file->get_filesize() > 5000000) { // Limit size to 5MB.
            $errors['face_image'] = get_string('filesizeexceeded', 'auth_faceauth');
        }

        return $errors;
    }

    /**
     * Get the uploaded file from the draft area.
     *
     * @param string $elementname The name of the filepicker element.
     * @return \stored_file|null The uploaded file or null if no file is uploaded.
     */
    private function get_draft_file($elementname) {
        $draftitemid = file_get_submitted_draft_itemid($elementname);
        file_prepare_draft_area($draftitemid, $this->_form->get_context()->id, 'auth_faceauth', 'face_images', 0);
        $this->_form->set_data(['face_image' => $draftitemid]);

        $fs = get_file_storage();
        $files = $fs->get_area_files($this->_form->get_context()->id, 'user', 'draft', $draftitemid, 'id DESC', false);
        return reset($files);
    }
}