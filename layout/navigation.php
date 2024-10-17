<?php

use \local_yulearn\YULearnUser;
use \local_yulearn\Employee;

global $CFG, $DB, $PAGE, $OUTPUT, $USER;

// Get users positions
$activePositions = $DB->get_records(\local_yulearn\YULearn::TABLE_EMPLOYEE, ['userid' => $USER->id, 'deleted' => 0, 'active' => true]);
$hrbpRole = $DB->get_record('role', ['shortname' => 'yulearn_hrbp']);
$context = context_system::instance();


$PAGE->secondarynav->add(
    get_string('my_training_history', 'local_yulearn'),
    new moodle_url("/local/yulearn/reports/employee_training_history.php")
);


// Add the learning opportunities
$PAGE->secondarynav->add(
    get_string('learning_opportunities', 'local_yulearn'),
    new moodle_url("/local/yulearn/course_schedules.php")
);

// If the user is a manager, add the
foreach ($activePositions as $ap) {
    $EMPLOYEE = new Employee($ap->id);
    $isManager = $EMPLOYEE->getIsManager();
    if ($isManager) {
        $PAGE->secondarynav->add(
            get_string('my_team', 'local_yulearn'),
            new moodle_url("/local/yulearn/admin/my_team.php")
        );
        break;
    }
}

// If user has capability HRBP, Add HRBP link
if (user_has_role_assignment($USER->id, $hrbpRole->id, $context->id)) {
    $PAGE->secondarynav->add(
        get_string('hrbp_view', 'local_yulearn'),
        new moodle_url("/local/yulearn/admin/hrbp_employees.php")
    );
}