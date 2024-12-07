<?php
namespace auth_faceauth\tests;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/auth/faceauth/classes/practice.php');

/**
 * Unit tests for the practice functionality of the Face Authentication plugin.
 */
class practice_test extends \advanced_testcase {

    /**
     * Test the practice functionality.
     */
    public function test_practice() {
        $this->resetAfterTest();

        // Create a user.
        $user = $this->getDataGenerator()->create_user();

        // Simulate practice functionality.
        $practice = new \auth_faceauth\practice();
        $result = $practice->simulate_practice($user->id);

        // Assert the result.
        $this->assertTrue($result['success']);
        $this->assertEmpty($result['error']);
    }
}