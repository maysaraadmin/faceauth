<?php
namespace auth_faceauth\privacy;

defined('MOODLE_INTERNAL') || die();

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;

/**
 * Privacy provider for the Face Authentication plugin.
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider,
    \core_privacy\local\request\core_userlist_provider {

    /**
     * Returns metadata about the Face Authentication plugin.
     *
     * @param collection $collection The initialised collection to add items to.
     * @return collection The updated collection of metadata items.
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_database_table(
            'auth_faceauth_metadata',
            [
                'userid' => 'privacy:metadata:auth_faceauth_metadata:userid',
                'face_data' => 'privacy:metadata:auth_faceauth_metadata:face_data',
                'created_at' => 'privacy:metadata:auth_faceauth_metadata:created_at',
                'updated_at' => 'privacy:metadata:auth_faceauth_metadata:updated_at',
            ],
            'privacy:metadata:auth_faceauth_metadata'
        );

        $collection->add_database_table(
            'auth_faceauth_logs',
            [
                'userid' => 'privacy:metadata:auth_faceauth_logs:userid',
                'status' => 'privacy:metadata:auth_faceauth_logs:status',
                'attempt_time' => 'privacy:metadata:auth_faceauth_logs:attempt_time',
                'ip_address' => 'privacy:metadata:auth_faceauth_logs:ip_address',
                'error_message' => 'privacy:metadata:auth_faceauth_logs:error_message',
            ],
            'privacy:metadata:auth_faceauth_logs'
        );

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user ID.
     * @return contextlist The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        $contextlist = new contextlist();

        $sql = "SELECT c.id
                  FROM {context} c
                  JOIN {auth_faceauth_metadata} fm ON fm.userid = :userid1
                  JOIN {auth_faceauth_logs} fl ON fl.userid = :userid2
                 WHERE c.contextlevel = :contextlevel";

        $params = [
            'userid1' => $userid,
            'userid2' => $userid,
            'contextlevel' => CONTEXT_USER,
        ];

        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $user = $contextlist->get_user();

        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel == CONTEXT_USER) {
                // Export face metadata.
                $metadata = $DB->get_records('auth_faceauth_metadata', ['userid' => $user->id]);
                if (!empty($metadata)) {
                    writer::with_context($context)->export_data(
                        [get_string('privacy:metadata:auth_faceauth_metadata', 'auth_faceauth')],
                        (object) ['metadata' => $metadata]
                    );
                }

                // Export face logs.
                $logs = $DB->get_records('auth_faceauth_logs', ['userid' => $user->id]);
                if (!empty($logs)) {
                    writer::with_context($context)->export_data(
                        [get_string('privacy:metadata:auth_faceauth_logs', 'auth_faceauth')],
                        (object) ['logs' => $logs]
                    );
                }
            }
        }
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param \context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;

        if ($context->contextlevel == CONTEXT_USER) {
            $DB->delete_records('auth_faceauth_metadata', ['userid' => $context->instanceid]);
            $DB->delete_records('auth_faceauth_logs', ['userid' => $context->instanceid]);
        }
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $user = $contextlist->get_user();

        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel == CONTEXT_USER) {
                $DB->delete_records('auth_faceauth_metadata', ['userid' => $user->id]);
                $DB->delete_records('auth_faceauth_logs', ['userid' => $user->id]);
            }
        }
    }

    /**
     * Get the list of users who have data within a specific context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();

        if ($context->contextlevel == CONTEXT_USER) {
            $sql = "SELECT userid
                      FROM {auth_faceauth_metadata}
                     WHERE userid = :userid";

            $params = ['userid' => $context->instanceid];

            $userlist->add_from_sql('userid', $sql, $params);
        }
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();

        if ($context->contextlevel == CONTEXT_USER) {
            list($userinsql, $userinparams) = $DB->get_in_or_equal($userlist->get_userids(), SQL_PARAMS_NAMED);

            $DB->delete_records_select('auth_faceauth_metadata', "userid $userinsql", $userinparams);
            $DB->delete_records_select('auth_faceauth_logs', "userid $userinsql", $userinparams);
        }
    }
}