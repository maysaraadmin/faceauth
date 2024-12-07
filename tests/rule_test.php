<?php
namespace auth_faceauth\tests;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/auth/faceauth/classes/rule.php');

/**
 * Unit tests for the rule functionality of the Face Authentication plugin.
 */
class rule_test extends \advanced_testcase {

    /**
     * Test the rule functionality.
     */
    public function test_rule() {
        $this->resetAfterTest();

        // Create a user.
        $user = $this->getDataGenerator()->create_user();

        // Simulate rule functionality.
        $rule = new \auth_faceauth\rule();
        $result = $rule->simulate_rule($user->id);

        // Assert the result.
        $this->assertTrue($result['success']);
        $this->assertEmpty($result['error']);
    }
}