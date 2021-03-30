<?php
/**
 * The  quiz mistakescollection
 *
 * @package   gradereport_mistakescollection
 * @author Jason <1129332567@qq.com>
 * @copyright  Jason (http://52.130.83.237)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require('../../../config.php');
require_once ('Page.class.php');

$courseid=required_param('id',PARAM_INT);
$groupid  = optional_param('group', 0, PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_login($course);
$context = context_course::instance($course->id);
require_capability('gradereport/mistakescollection:view', $context);
$teacher=has_capability('moodle/grade:viewall', $context)?true:false;
$PAGE->set_url(new moodle_url('/grade/report/mistakescollection/index.php'),array("id"=>$courseid));
$PAGE->set_pagelayout('report');
$title="测验错题集";
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);

if(!$teacher){
    $where="WHERE qas.userid = :userid AND qas.state IN(:state1,:state2) AND cm.course= :courseid";
    $params['userid']=$USER->id;
}else{
    $where="WHERE qas.state IN(:state1,:state2) AND cm.course= :courseid";
}
//分隔小组
if($teacher && $groupid){
    $where=$where." AND g.id= :groupid";
    $sql="SELECT count(qas.id) as totalcount
      FROM {question_attempts} qa 
      JOIN {question_attempt_steps} qas ON qas.questionattemptid = qa.id AND qas.sequencenumber = ( SELECT MAX(sequencenumber) FROM mdl_question_attempt_steps WHERE questionattemptid = qa.id )
      JOIN {question_usages} qu ON qu.id=qa.questionusageid
      JOIN {context} c ON c.id=qu.contextid
      JOIN {course_modules} cm ON cm.id=c.instanceid
      JOIN {user} u ON u.id=qas.userid
      JOIN {groups_members} gm ON gm.userid = u.id 
      JOIN {groups} g ON gm.groupid = g.id  "
        .$where;

    $sql1="SELECT qas.id,course.shortname as coursename,q.id quizid,q.name as quizname,u.firstname,u.lastname,qa.id AS questionattemptid, qa.questionid,quiza.id quizattemptid,quiza.attempt,qa.questionusageid, qa.slot, qa.questionid , qa.questionsummary, qa.rightanswer, qa.responsesummary, qa.timemodified, qas.id AS attemptstepid, qas.timecreated
      FROM {question_attempts} qa 
      JOIN {question_attempt_steps} qas ON qas.questionattemptid = qa.id AND qas.sequencenumber = ( SELECT MAX(sequencenumber) FROM mdl_question_attempt_steps WHERE questionattemptid = qa.id )
      JOIN {question_usages} qu ON qu.id=qa.questionusageid
      JOIN {quiz_attempts} quiza ON qu.id=quiza.uniqueid
      JOIN {context} c ON c.id=qu.contextid
      JOIN {course_modules} cm ON cm.id=c.instanceid
      JOIN {quiz} q ON q.id=cm.instance
      JOIN {course} course ON course.id=cm.course
      JOIN {user} u ON u.id=qas.userid 
      JOIN {groups_members} gm ON gm.userid = u.id 
      JOIN {groups} g ON gm.groupid = g.id  "
        .$where." ORDER BY qa.timemodified DESC ,qa.slot ASC";

    $params['groupid']=$groupid;

}else{

    $sql="SELECT count(qas.id) as totalcount
      FROM {question_attempts} qa 
      JOIN {question_attempt_steps} qas ON qas.questionattemptid = qa.id AND qas.sequencenumber = ( SELECT MAX(sequencenumber) FROM mdl_question_attempt_steps WHERE questionattemptid = qa.id )
      JOIN {question_usages} qu ON qu.id=qa.questionusageid
      JOIN {context} c ON c.id=qu.contextid
      JOIN {course_modules} cm ON cm.id=c.instanceid "
        .$where;

    $sql1="SELECT qas.id,course.shortname as coursename,q.id quizid,q.name as quizname,u.firstname,u.lastname,qa.id AS questionattemptid, qa.questionid,quiza.id quizattemptid,quiza.attempt,qa.questionusageid, qa.slot, qa.questionid , qa.questionsummary, qa.rightanswer, qa.responsesummary, qa.timemodified, qas.id AS attemptstepid, qas.timecreated
      FROM {question_attempts} qa 
      JOIN {question_attempt_steps} qas ON qas.questionattemptid = qa.id AND qas.sequencenumber = ( SELECT MAX(sequencenumber) FROM mdl_question_attempt_steps WHERE questionattemptid = qa.id )
      JOIN {question_usages} qu ON qu.id=qa.questionusageid
      JOIN {quiz_attempts} quiza ON qu.id=quiza.uniqueid
      JOIN {context} c ON c.id=qu.contextid
      JOIN {course_modules} cm ON cm.id=c.instanceid
      JOIN {quiz} q ON q.id=cm.instance
      JOIN {course} course ON course.id=cm.course
      JOIN {user} u ON u.id=qas.userid "
        .$where." ORDER BY qa.timemodified DESC ,qa.slot ASC";
}

$params['state1']="gradedpartial";
$params['state2']="gradedwrong";
$params['courseid']=$courseid;

$results = $DB->get_record_sql($sql, $params);
$per=10;
$page=new \gradereport_mistakescollecion\Page($results->totalcount,$per);
$limit=explode(',',$page->limit);
$results = $DB->get_records_sql($sql1, $params,$limit[0],$limit[1]);
$table = new html_table();
$showrightanswer =(bool)get_config('moodle',"gradereport_mistakescollection_showrightanswer");
$header=array (
    '课程名称',
    "测验名称",
    "用户名",
    "答题次数",
    "题目",
    "你的答案",
    '正确答案',
    "答题时间",
    "题目回顾"
);
if(!$teacher && !$showrightanswer){
    $header=array (
        '课程名称',
        "测验名称",
        "用户名",
        "答题次数",
        "题目",
        "你的答案",
        "答题时间",
        "题目回顾"
    );
}

$table->head = $header;
foreach ($results as $val) {
    $slotlink="<a href=".new moodle_url('/grade/report/mistakescollection/questionview.php',array('id'=>$courseid,'quizattemptid'=>$val->quizattemptid,'slot'=>$val->slot))." target='_blank' >回顾</a>";
    if(!$teacher && !$showrightanswer ){
        $row = array ($val->coursename,$val->quizname,$val->lastname.$val->firstname,"第".$val->attempt."次",$val->questionsummary,$val->responsesummary,date('Y-m-d H:i:s',$val->timecreated),$slotlink);
    }else{
        $row = array ($val->coursename,$val->quizname,$val->lastname.$val->firstname,"第".$val->attempt."次",$val->questionsummary,$val->responsesummary,$val->rightanswer,date('Y-m-d H:i:s',$val->timecreated),$slotlink);
    }
    $table->data[] = $row;
}
echo $OUTPUT->header();
if($teacher){
    $groups = groups_get_all_groups($course->id,0,0,'g.id,g.name');
    $groupoptions = array(0=>'所有小组');
    if ($groups) {
        foreach ($groups as $group) {
            $groupoptions[ $group->id] =$group->name;
        }
        echo html_writer::label("分隔小组&nbsp;:&nbsp;&nbsp;", 'menugroup', false);
        echo html_writer::select($groupoptions,'group',$groupid,null);
        echo "</br></br>";
        echo '<script type="text/javascript">
                  document.getElementById("menugroup").onchange=function(){
                    var url="'.$CFG->wwwroot.'/grade/report/mistakescollection/index.php?id='.$course->id.'&group="+$("#menugroup").val();
                    window.location.href=url;
                  }; 
          </script>';
    }
}
echo html_writer::start_tag('div', array('class' => 'no-overflow display-table'));
echo html_writer::table($table);
echo html_writer::end_tag('div');
echo "</br>".$page->fpage();
echo $OUTPUT->footer();