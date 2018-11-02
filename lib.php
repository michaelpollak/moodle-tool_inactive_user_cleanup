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
 * Inactive user cleanup library
 * Extended to be more flexible by michaelpollak, see readme.
 *
 * @package    tool
 * @subpackage inactive user cleanup
 * @copyright  2014 dualcube {@link https://dualcube.com}
 * @copyright  2018 michael pollak <moodle@michaelpollak.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 defined('MOODLE_INTERNAL') || die;
/*
 * Standard cron function
 */
require_once($CFG->libdir.'/adminlib.php');
has_capability('moodle/user:delete', context_system::instance());

function tool_inactive_user_cleanup_cron() {
    global $DB, $CFG;

    mtrace("Hey, admin tool inactive user cleanup is running");

    // Extended: If beforedelete is -1 we do not send out emails and just delete the user.
    $beforedelete = get_config('tool_inactive_user_cleanup', 'daysbeforedeletion');
    $inactivity = get_config('tool_inactive_user_cleanup', 'daysofinactivity');
    $inactiveorsuspended = get_config('tool_inactive_user_cleanup', 'inactiveorsuspended');
    $deleteoranon = get_config('tool_inactive_user_cleanup', 'deleteoranon');

    if ($inactiveorsuspended) {
        $users = $DB->get_records('user', array('suspended' => '1', 'deleted' => '0')); // Only check already suspended users.
    } else {
        $users = $DB->get_records('user', array('deleted' => '0'));
    }

    // Mailstuff is only needed if we want to send them.
    if ($beforedelete != "-1") {
        $subject = get_config('tool_inactive_user_cleanup', 'emailsubject');
        $body = get_config('tool_inactive_user_cleanup', 'emailbody');
        $messagetext = html_to_text($body);
        $mainadminuser = get_admin();
    }

    foreach ($users as $usersdetails) {

        if ($inactiveorsuspended) {
            $minus = round((time() - $usersdetails->timemodified)/60/60/24); // This checks for suspended status.
        } else {
            $minus = round((time() - $usersdetails->lastaccess)/60/60/24); // This checks for access.
        }

        if ($minus > $inactivity) {
            // In case beforedelete is -1 we don't want any mails to be sent.
            if ($beforedelete == "-1") {

                // We will delete this user without sending mails.
                if (!isguestuser($usersdetails->id)) {
                    // Log for future reference.
                    mtrace($logtxt = 'Deleted user ' . $usersdetails->id . ' with the name ' . $usersdetails->username . '.');
                    // email_to_user(get_admin(), get_admin(), $logtxt, $logtxt); // Send mail to admin.

                    // Delete or anonymize user.
                    if  ($deleteoranon) {
                        $dataobject = (object) ['id' => $usersdetails->id];
                        $keyword = ''; // Either use keyword or just remove personal info.
                        $dataobject->username = $dataobject->idnumber = $dataobject->password = $keyword;
                        $dataobject->firstname = $dataobject->lastname = $dataobject->email = $keyword;
                        $dataobject->deleted = '1';
                        $DB->update_record('user', $dataobject);
                    } else {
                        delete_user($usersdetails);
                    }

                }
            } else {
                $ischeck = $DB->get_record('tool_inactive_user_cleanup', array('userid' => $usersdetails->id));

                if (!$ischeck) {
                    mtrace("!$ischeck");
                    $record = new stdClass();
                    $record->userid = $usersdetails->id;
                    if (email_to_user($usersdetails, $mainadminuser, $subject, $messagetext)) {
                        mtrace('id');
                        mtrace($usersdetails->id. '---' .$usersdetails->email);
                        mtrace('minus'.$minus);
                        mtrace('email sent');
                        $record->emailsent = 1;
                        $record->date = time();
                        $lastinsertid = $DB->insert_record('tool_inactive_user_cleanup', $record, false);
                    }
                }
            }
            if ($beforedelete != 0) {
                $deleteuserafternotify = $DB->get_record('tool_inactive_user_cleanup', array('userid' => $usersdetails->id));
                if (!empty($deleteuserafternotify)) {
                    mtrace('days before delete'. strtotime('+'.$beforedelete.' day', $deleteuserafternotify->date));
                    $minus_timestamp = strtotime('+' . $minus .' days');

                    if (($minus_timestamp) >= ( strtotime('+'.$beforedelete.' day', $deleteuserafternotify->date) )) {
                        if (!isguestuser($usersdetails->id)) {
                            delete_user($usersdetails);
                            mtrace('delete user' . $usersdetails->id);
                        }
                    }
                }
            }
        }
    }
    return true;
}
