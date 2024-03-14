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

$home = new moodle_url('/'); // Default redirect

// They can give us the required course (short)name, idnumber or id (in order of priority)
$name = optional_param('name', '', PARAM_TEXT);
$idnumber = optional_param('idnumber', '', PARAM_RAW);
$id = optional_param('id', 0, PARAM_INT);

$params = array();
if (!empty($name)) {
	$params = array('shortname' => $name);
} else if (!empty($idnumber)) {
	$params = array('idnumber' => $idnumber);
} else if (!empty($id)) {
	$params = array('id' => $id);
}else {
	redirect($home);
}


// Validate the given course and get its id
$course = $DB->get_record('course', $params, 'id');
if ($course == null) {
	redirect($home);
}
$id = $course->id;

require_once($CFG->dirroot . "/local/obu_metalinking/lib.php");
$id = get_teaching_course_id($id);

// Let's go!
redirect(new moodle_url('/course/view.php?id=' . $id));
