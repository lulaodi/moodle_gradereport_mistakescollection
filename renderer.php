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

defined('MOODLE_INTERNAL') || die();

/**
 * Custom renderer for the quiz mistakescollection
 * @package   gradereport_mistakescollection
 * @author Jason <1129332567@qq.com>
 * @copyright  Jason (http://52.130.83.237)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gradereport_mistakescollection_renderer extends mod_quiz_renderer {
    /**
     * Custom builds the review page
     *
     * @param quiz_attempt $attemptobj an instance of quiz_attempt.
     * @param array $slots an array of intgers relating to questions.
     * @param int $page the current page number
     * @param bool $showall whether to show entire attempt on one page.
     * @param bool $lastpage if true the current page is the last page.
     * @param mod_quiz_display_options $displayoptions instance of mod_quiz_display_options.
     * @param array $summarydata contains all table data
     * @return $output containing html data.
     */
    public function review_page(quiz_attempt $attemptobj, $slots, $page, $showall,
                                $lastpage, mod_quiz_display_options $displayoptions,
                                $summarydata) {
        $output = '';
        $output .= $this->header();
        $output .= $this->review_summary_table($summarydata, $page);
        $output .= $this->review_form($page, $showall, $displayoptions,
            $this->questions($attemptobj, true, $slots, $page, $showall, $displayoptions),
            $attemptobj);
        $url     = new moodle_url('/grade/report/mistakescollection/index.php',array("id"=>$attemptobj->get_courseid()));
        $nav     = html_writer::link($url, "结束回顾", array('class' => 'mod_quiz-next-nav'));
        $output .= html_writer::tag('div', $nav, array('class' => 'submitbtns'));;
        $output .= $this->footer();
        return $output;
    }

}
