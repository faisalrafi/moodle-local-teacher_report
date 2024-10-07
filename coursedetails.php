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
 * Course Details file for local_teacher_report.
 *
 * @package    local_teacher_report
 * @copyright  2024 Brain Station 23 Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $USER, $PAGE, $OUTPUT, $CFG, $DB;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot.'/local/teacher_report/lib.php');

$courseid = required_param('id', PARAM_INT);

$PAGE->set_url('/local/teacher_report/coursedetails.php', ['id' => $courseid]);
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('detailstitle', 'local_teacher_report'));
$PAGE->set_heading(get_string('detailsheading', 'local_teacher_report'));

echo $OUTPUT->header();

// Get total students in course.
$context = context_course::instance($courseid);
$total_students = count_role_users(5, $context);

$teachers = teacher_report_get_teacher_names($courseid);

$templatecontext = [
    'teachers' => array_values($teachers),
    'total_students' => $total_students,
    'userprofile_url' => new moodle_url('/user/profile.php'),
    'back_url' => new moodle_url('/local/teacher_report/view.php'),
];

echo $OUTPUT->render_from_template('local_teacher_report/coursedetails', $templatecontext);

echo $OUTPUT->footer();
