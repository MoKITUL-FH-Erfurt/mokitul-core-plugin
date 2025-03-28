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
 * Plugin strings are defined here.
 *
 * @package     local_mokitul
 * @category    string
 * @copyright   2024 Your Name <you@example.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined("MOODLE_INTERNAL") || die();

$string["pluginname"] = "mokitulcore";
$string["serviceUrl"] = "Service URL";
$string["serviceUrlDesc"] = "The URL of the service to connect to.";
$string["analyticsUrl"] = "Analytics URL";
$string["analyticsUrlDesc"] = "The URL of the analyitcs service to connect to.";

$string["llmModel"] = "Ollama LLM Model";
$string["llmModelDesc"] =
    'Please provide a ollama model identifier found on <a href="https://ollama.com/library">ollama/library</a>';

$string["downloadApiKey"] = "API Key for Downloads";
$string["downloadApiKeyDesc"] =
    "Please provide a API Key which is used for downloading files from Model (this has to be the same used by other services)";
