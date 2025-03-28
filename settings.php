<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_mokitul
 * @category    admin
 * @copyright   2024 Your Name <you@example.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined("MOODLE_INTERNAL") || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage(
        "local_mokitul_settings",
        new lang_string("pluginname", "local_mokitul")
    );

    $ADMIN->add("localplugins", $settings);

    // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
    $settings->add(
        new admin_setting_configtext(
            "local_mokitul/serviceUrl",
            get_string("serviceUrl", "local_mokitul"),
            get_string("serviceUrlDesc", "local_mokitul"),
            "",
            PARAM_TEXT
        )
    );
    $settings->add(
        new admin_setting_configtext(
            "local_mokitul/analyticsUrl",
            get_string("analyticsUrl", "local_mokitul"),
            get_string("analyticsUrlDesc", "local_mokitul"),
            "",
            PARAM_TEXT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            "local_mokitul/llmModel",
            get_string("llmModel", "local_mokitul"),
            get_string("llmModelDesc", "local_mokitul"),
            "",
            PARAM_TEXT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            "local_mokitul/downloadApiKey",
            get_string("downloadApiKey", "local_mokitul"),
            get_string("downloadApiKeyDesc", "local_mokitul"),
            "",
            PARAM_TEXT
        )
    );

    if ($ADMIN->fulltree) {
        // TODO: Define actual plugin settings page and add it to the tree - {@link https://docs.moodle.org/dev/Admin_settings}.
    }
}
