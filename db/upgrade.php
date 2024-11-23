<?php
/**
 * Upgrade script for the Face Authentication plugin.
 *
 * @package    auth_faceauth
 * @copyright  2024 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade script for the Face Authentication plugin.
 *
 * @param int $oldversion The version of the plugin before the upgrade.
 * @return bool True on success.
 */
function xmldb_auth_faceauth_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // Example: Add a new table for storing face enrollment logs.
    if ($oldversion < 2024112100) {
        // Define table auth_faceauth_logs to be created.
        $table = new xmldb_table('auth_faceauth_logs');

        // Adding fields to table auth_faceauth_logs.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('event', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timestamp', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('details', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table auth_faceauth_logs.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Conditionally launch create table for auth_faceauth_logs.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Plugin savepoint reached.
        upgrade_plugin_savepoint(true, 2024112100, 'auth', 'faceauth');
    }

    // Example: Add a new column to an existing table.
    if ($oldversion < 2024112101) {
        // Define field face_data_hash to be added to auth_faceauth.
        $table = new xmldb_table('auth_faceauth');
        $field = new xmldb_field('face_data_hash', XMLDB_TYPE_CHAR, '64', null, null, null, null, 'face_data');

        // Conditionally launch add field face_data_hash.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Plugin savepoint reached.
        upgrade_plugin_savepoint(true, 2024112101, 'auth', 'faceauth');
    }

    // Add additional upgrade steps here as needed.

    return true;
}
