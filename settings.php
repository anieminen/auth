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

/**
 * Admin settings and defaults.
 *
 * @package auth_saml
 * @author Erlend Strømsvik - Ny Media AS
 * @author Piers Harding - made quite a number of changes
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 * Authentication Plugin: SAML based SSO Authentication
 *
 * Authentication using SAML2 with SimpleSAMLphp.
 *
 * Based on plugins made by Sergio Gómez (moodle_ssp) and Martin Dougiamas (Shibboleth).
 *
 * 2008-10  Created
 * 2009-07  Added new configuration options
 * 2017-11  Added settings.php file for Moodle 3.3 support
 **/

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    //require_once("courses.php");
    //require_once("roles.php");

    // Introductory explanation.
    $settings->add(new admin_setting_heading('auth_saml/pluginname', '',
        new lang_string('auth_samldescription', 'auth_saml')));

    // samllib path.
    $settings->add(new admin_setting_configtext('auth_saml/samllib', get_string('auth_saml_samllib', 'auth_saml'),
        get_string('auth_saml_samllib_description', 'auth_saml'), '/var/www/simplesamlphp/lib', PARAM_RAW_TRIMMED));

    // sp_source.
    $settings->add(new admin_setting_configtext('auth_saml/sp_source', get_string('auth_saml_sp_source', 'auth_saml'),
        get_string('auth_saml_sp_source_description', 'auth_saml'), 'default-sp', PARAM_RAW_TRIMMED));

    // username.
    $settings->add(new admin_setting_configtext('auth_saml/username', get_string('auth_saml_username', 'auth_saml'),
        get_string('auth_saml_username_description', 'auth_saml'), 'eduPersonPrincipalName', PARAM_RAW_TRIMMED));

    // dosinglelogout.
    $settings->add(new admin_setting_configcheckbox('auth_saml/dosinglelogout', get_string('auth_saml_dosinglelogout', 'auth_saml'),
        get_string('auth_saml_dosinglelogout_description', 'auth_saml'), 1));

    // samllogoimage.
    $settings->add(new admin_setting_configtext('auth_saml/samllogoimage', get_string('auth_saml_logo_path', 'auth_saml'),
        get_string('auth_saml_logo_path_description', 'auth_saml'), 'logo.gif', PARAM_RAW_TRIMMED));

    // samllogoinfo.
    $settings->add(new admin_setting_configtextarea('auth_saml/samllogoinfo', get_string('auth_saml_logo_info', 'auth_saml'),
        get_string('auth_saml_logo_info_description', 'auth_saml'), 'SAML login', PARAM_RAW));

    // autologin.
    $settings->add(new admin_setting_configcheckbox('auth_saml/autologin', get_string('auth_saml_autologin', 'auth_saml'),
        get_string('auth_saml_autologin_description', 'auth_saml'), 0));

    // samllogfile.
    $settings->add(new admin_setting_configtext('auth_saml/samllogfile', get_string('auth_saml_logfile', 'auth_saml'),
        get_string('auth_saml_logfile_description', 'auth_saml'), '', PARAM_RAW_TRIMMED));

    // samlhookfile.
    $settings->add(new admin_setting_configtext('auth_saml/samlhookfile', get_string('auth_saml_samlhookfile', 'auth_saml'),
        get_string('auth_saml_samlhookfile_description', 'auth_saml'), $CFG->dirroot . '/auth/saml/custom_hook.php', PARAM_RAW_TRIMMED));

    // disablejit.
    $settings->add(new admin_setting_configcheckbox('auth_saml/disablejit', get_string('auth_saml_disablejit', 'auth_saml'),
        get_string('auth_saml_disablejit_description', 'auth_saml'), 0));

    // Sync users from another auth module.
    $authmods = array();
    $authmods[''] = 'Disabled';
    foreach (get_enabled_auth_plugins() as $authname) {
        $plugin = get_auth_plugin($authname);
        if (method_exists($plugin, 'sync_users')) {
            $authmods[$authname] = $authname;
        }
    }
    $settings->add(new admin_setting_configselect('auth_saml/syncusersfrom',
            get_string('auth_saml_syncusersfrom', 'auth_saml'),
            get_string('auth_saml_syncusersfrom_description', 'auth_saml'), '', $authmods));

    // Display locking / mapping of profile fields.
    $authplugin = get_auth_plugin('saml');
    display_auth_lock_options($settings, $authplugin->authtype, $authplugin->userfields,
            get_string('auth_fieldlocks_help', 'auth'), true, false);
}
