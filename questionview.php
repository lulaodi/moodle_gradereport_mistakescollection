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
 * The  quiz mistakescollection
 *
 * @package   gradereport_mistakescollection
 * @author Jason <1129332567@qq.com>
 * @copyright  Jason (http://52.130.83.237)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');
$courseid=required_param('id',PARAM_INT);
$quizattemptid  = required_param('quizattemptid', PARAM_INT);
$slot  = required_param('slot', PARAM_INT);

$url=new moodle_url('/grade/report/mistakescollection/questionview.php',array("id"=>$courseid,"quizattemptid"=>$quizattemptid,"slot"=>$slot));
$PAGE->set_url($url);
require_login($courseid);
$PAGE->set_pagelayout('report');
question_engine::initialise_js();
$nav="<a href='".new moodle_url('/grade/report/mistakescollection/index.php',array("id"=>$courseid))."'>测验错题集</a>&nbsp;&nbsp;/&nbsp;&nbsp;题目回顾";
$PAGE->navbar->add($nav);
$title="测验错题集";
$PAGE->set_title($title."_题目回顾");
$PAGE->set_heading($title);

$attemptobj = quiz_attempt::create($quizattemptid);
$attemptobj->check_review_capability();
$accessmanager = $attemptobj->get_access_manager(time());
$accessmanager->setup_attempt_page($PAGE);
$options = $attemptobj->get_display_options(true);
if ($attemptobj->is_own_attempt()) {
    if (!$attemptobj->is_finished()) {
        redirect($attemptobj->attempt_url(null, 1));
    } else if (!$options->attempt) {
        $accessmanager->back_to_view_page($PAGE->get_renderer('mod_quiz'),
            $attemptobj->cannot_review_message());
    }
} else if (!$attemptobj->is_review_allowed()) {
    throw new moodle_quiz_exception($attemptobj->get_quizobj(), 'noreviewattempt');
}
$output = $PAGE->get_renderer('gradereport_mistakescollection');
echo $output->review_page($attemptobj, array($slot), 0, 1, 1, $options, array());
