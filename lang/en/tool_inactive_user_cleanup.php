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
 * Strings for component 'tool_inactive_user_cleanup', language 'en', branch 'MOODLE_22_STABLE'
 *
 * @package    tool
 * @subpackage inactive user cleanup
 * @copyright  2014 Dualcube {@link https://dualcube.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'User Cleanup';
$string['setting'] = 'Cleanup settings';
$string['daysofinactivity'] = 'Days before processing';
$string['daysbeforedeletion'] = 'Days before deleting';
$string['daysbeforedeletion_help'] = 'If -1 no mails will be sent and accounts will be deleted without warning the user.';
$string['inactiveorsuspended'] = 'Suspended instead of inactive';
$string['inactiveorsuspended_help'] = 'If checked this tool will look for accounts that have been suspended for the defined amount of time.';
$string['deleteoranon'] = 'Anonymize instead of deleting';
$string['deleteoranon_help'] = 'If checked userdata will be kept but anonymized.';
$string['emailsetting'] = 'Notification E-Mail';
$string['emailsubject'] = 'Subject';
$string['emailbody'] = 'Body';
$string['runcron'] = 'Run Cron Manually';

