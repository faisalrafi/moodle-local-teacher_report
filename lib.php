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
 * Library functions for local_teacher_report.
 *
 * @package    local_teacher_report
 * @copyright  2024 Brain Station 23 Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Get teacher names for a specific course.
 *
 * @param int $courseid The ID of the course to retrieve teachers for.
 * @return array Array of teacher records containing id, fullname, and email.
 * @throws dml_exception
 */
function teacher_report_get_teacher_names(int $courseid): array
{
    global $DB;

    $sql = "SELECT u.id, CONCAT(u.firstname, ' ', u.lastname) as fullname, u.email
            FROM {role_assignments} ra
            JOIN {context} ctx ON ra.contextid = ctx.id
            JOIN {user} u ON ra.userid = u.id
            JOIN {role} r ON ra.roleid = r.id
            WHERE r.shortname = 'editingteacher'
            AND ctx.contextlevel = 50
            AND ctx.instanceid = :courseid";

    $result = $DB->get_records_sql($sql, ['courseid' => $courseid]);

    return $result;
}