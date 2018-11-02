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
 * From for Inactive user cleanup email setting
 *
 * @package    tool
 * @subpackage Inactive User cleanup
 * @copyright  2014 dualcube {@link https://dualcube.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot . '/user/editlib.php');

class tool_inactive_user_cleanup_config_form extends moodleform {
    public function definition () {
        $mform = $this->_form;
        $mform->addElement('header', 'configheader', get_string('setting', 'tool_inactive_user_cleanup'));

        $mform->addElement('text', 'config_daysofinactivity', get_string('daysofinactivity', 'tool_inactive_user_cleanup'));
        $mform->setDefault('config_daysofinactivity', '1095'); // Three years.
        $mform->setType('config_daysofinactivity', PARAM_TEXT);

        $mform->addElement('text', 'config_daysbeforedeletion', get_string('daysbeforedeletion', 'tool_inactive_user_cleanup'));
        $mform->addHelpButton('config_daysbeforedeletion', 'daysbeforedeletion', 'tool_inactive_user_cleanup');
        $mform->setDefault('config_daysbeforedeletion', '-1');
        $mform->setType('config_daysbeforedeletion', PARAM_TEXT);

        // Inactive or suspended?
        $mform->addElement('advcheckbox', 'config_inactiveorsuspended', get_string('inactiveorsuspended', 'tool_inactive_user_cleanup'));
        $mform->addHelpButton('config_inactiveorsuspended', 'inactiveorsuspended', 'tool_inactive_user_cleanup');

        // Delete or anonymize.
        $mform->addElement('advcheckbox', 'config_deleteoranon', get_string('deleteoranon', 'tool_inactive_user_cleanup'));
        $mform->addHelpButton('config_deleteoranon', 'deleteoranon', 'tool_inactive_user_cleanup');

        // Form for email subject and text.
        $mform->addElement('header', 'config_headeremail', get_string('emailsetting', 'tool_inactive_user_cleanup'));
        $mform->addElement('text', 'config_subjectemail', get_string('emailsubject', 'tool_inactive_user_cleanup'));
        $editoroptions = array('trusttext' => true, 'subdirs' => true, 'maxfiles' => 1, 'maxbytes' => 1024);
        $mform->addElement('editor', 'config_bodyemail', get_string('emailbody', 'tool_inactive_user_cleanup'), $editoroptions);
        $mform->setType('config_subjectemail', PARAM_TEXT);
        $mform->setDefault('config_subjectemail', 'subject');
        $mform->setType('config_bodyemail', PARAM_RAW);
        $mform->setDefault('config_bodyemail', 'body');

        // Disable email form if we don't send mails.
        $mform->disabledIf('config_subjectemail', 'config_daysbeforedeletion', 'eq', -1);
        $mform->disabledIf('config_bodyemail', 'config_daysbeforedeletion', 'eq', -1);

        $this->add_action_buttons();
    }
}
