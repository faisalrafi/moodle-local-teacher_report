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
 * View file for local_teacher_report.
 *
 * @package    local_teacher_report
 * @copyright  2024 Brain Station 23 Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $USER, $PAGE, $OUTPUT, $CFG, $DB;

require('../../config.php');

if(!is_siteadmin($USER)) {
    return redirect(new moodle_url('/'), 'Unauthorized', null, \core\output\notification::NOTIFY_ERROR);
}

$PAGE->set_url('/local/teacher_report/view.php');
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('pagetitle', 'local_teacher_report'));
$PAGE->set_heading(get_string('pagetitle', 'local_teacher_report'));

$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 2, PARAM_INT);

echo $OUTPUT->header();

$offset = $page * $perpage;

$totalcourses = $DB->count_records_select('course', 'id != 1');

$sql = "SELECT c.id, c.fullname 
        FROM {course} c 
        WHERE id != 1
        LIMIT $perpage OFFSET " . $offset;

$courses = $DB->get_records_sql($sql);

if ($courses) {
    $table = new html_table();
    $table->head = [get_string('course')];

    $table->attributes['style'] = 'width: 50%; margin: 0 auto;';

    foreach ($courses as $course) {
        $courseurl = new moodle_url('/local/teacher_report/coursedetails.php', ['id' => $course->id]);
        $courselink = html_writer::link($courseurl, $course->fullname, ['class' => 'text-decoration-none']);

        $table->data[] = [$courselink];
    }

    echo html_writer::table($table);
} else {
    echo html_writer::tag('p', get_string('nocourses', 'local_teacher_report'), ['class' => 'alert alert-info']);
}

$baseurl = new moodle_url('/local/teacher_report/view.php', ['page' => $page, 'perpage' => $perpage]);
echo $OUTPUT->paging_bar($totalcourses, $page, $perpage, $baseurl);

echo $OUTPUT->footer();
