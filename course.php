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
 * Redirect - Course redirect
 *
 * @package    local_redirect
 * @copyright  2020, Oxford Brookes University {@link http://www.brookes.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");

require_login();

$idnumber = required_param('idnumber', PARAM_TEXT);  // Course idnumber required

// Validate the given course and get it's id
$course = $DB->get_record('course', array('idnumber' => $idnumber), 'id');
if ($course == null) {
	redirect(new moodle_url('/'));
}
$id = $course->id;

// Get any meta-linked parent
$sql = 'SELECT parent.id FROM {enrol} e'
		. ' JOIN {course} parent ON parent.id = e.courseid'
		. ' WHERE e.enrol = "meta"'
		. '   AND e.customint1 = ?'
		. '   AND parent.shortname LIKE "% (%:%)"'
		. '   AND parent.idnumber LIKE "%.%"'
		. '   AND parent.visible = 1';
$db_ret = $DB->get_records_sql($sql, array($id));
foreach ($db_ret as $course) {
	$id = $course->id;
}

// Let's go!
redirect(new moodle_url('/course/view.php?id=' . $id));
