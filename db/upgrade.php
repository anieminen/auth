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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

/**
 * Function to upgrade auth_saml.
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_auth_saml_upgrade($oldversion) {
    global $CFG, $DB;

    if ($oldversion < 2017111300) {
        // Convert info in config_plugins from auth/saml to auth_saml.
        upgrade_fix_config_auth_plugin_names('saml');
        upgrade_fix_config_auth_plugin_defaults('saml');

        // Fix legacy values that were set as "on".
        $pluginconfig = get_config('auth_saml');
        foreach ($pluginconfig as $name => $value) {
            if($value == 'on') {
                set_config($name, 1, 'auth_saml');
            }
        }
        upgrade_plugin_savepoint(true, 2017111300, 'auth', 'saml');
    }

    return true;
}
