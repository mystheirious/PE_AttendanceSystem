<?php
session_start();

require_once '../classes/student.php';
require_once '../classes/course.php';
require_once '../classes/attendance.php';
require_once '../classes/user.php';
require_once '../classes/excuseLetter.php';

$userObj = new User();
$studentObj = new Student();
$courseObj = new Course();
$attendanceObj = new Attendance();
$excuseObj = new ExcuseLetter();


// Student Actions
if (isset($_POST['file_attendance']) && $_SESSION['user']['role'] === 'student') {
    $user_id = $_SESSION['user']['id'];
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];
    $status = $_POST['status'];
    
    $studentObj->fileAttendance($user_id, $course, $year_level, $status);
    header("Location: ../student/dashboard.php?success=attendance_filed");
    exit;
}

if (isset($_POST['update_student_profile']) && $_SESSION['user']['role'] === 'student') {
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];

    $studentObj = new Student();
    $studentObj->updateStudent($id, $full_name, $email, $course, $year_level);

    $_SESSION['user']['full_name'] = $full_name;
    $_SESSION['user']['email'] = $email;

    header("Location: ../student/dashboard.php?success=profile_updated");
    exit;
}

if(isset($_POST['register_student'])){
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $student_id = $_POST['student_id'];
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];

    if($password !== $confirm_password){
        $_SESSION['message'] = "Passwords do not match";
        $_SESSION['status'] = "400";
        header("Location: ../register.php");
        exit;
    }

    $userObj->register($full_name, $email, $password, 'student');
    $user = $userObj->login($email, $password);
    $studentObj->createProfile($user['id'], $full_name, $student_id, $course, $year_level);

    $_SESSION['message'] = "Registration successful. Please login.";
    $_SESSION['status'] = "200";
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['submit_excuse']) && $_SESSION['user']['role'] === 'student') {
    $user_id = $_SESSION['user']['id'];
    $student = $studentObj->selectOne("students", "user_id = :uid", ["uid" => $user_id]);

    if ($student) {
        $student_id = $student['id'];
        $course = $student['course'];
        $year_level = $student['year_level'];
        $reason = trim($_POST['reason']);

        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = "../excuses/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . "_" . basename($_FILES['photo']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                $photoPath = $targetFile;
            }
        }

        $excuseObj->submitLetter($student_id, $course, $year_level, $reason, $photoPath);

        header("Location: ../student/submitExcuse.php?success=excuse_submitted");
        exit;
    } else {
        header("Location: ../student/submitExcuse.php?error=no_student_profile");
        exit;
    }
}


// Admin Actions
if (isset($_POST['add_course']) && $_SESSION['user']['role'] === 'admin') {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $year_levels = $_POST['year_levels'];
    $courseObj->addCourse($code, $name, $year_levels);
    header("Location: ../admin/dashboard.php?success=course_added");
    exit;
}

if (isset($_POST['edit_course']) && $_SESSION['user']['role'] === 'admin') {
    $id = $_POST['id'];        
    $code = $_POST['code'];
    $name = $_POST['name'];
    $year_levels = $_POST['year_levels'] ?? '';
    $courseObj->editCourse($id, $code, $name, $year_levels);
    header("Location: ../admin/dashboard.php?success=course_updated");
    exit;
}

if (isset($_POST['delete_course']) && $_SESSION['user']['role'] === 'admin') {
    $id = $_POST['id'];         
    $courseObj->deleteCourse($id);
    header("Location: ../admin/dashboard.php?success=course_deleted");
    exit;
}


if (isset($_GET['filter_attendance']) && $_SESSION['user']['role'] === 'admin') {
    $course = $_GET['course'];
    $year_level = $_GET['year_level'];
    $_SESSION['attendance_list'] = $attendanceObj->getByCourseYear($course, $year_level);
    header("Location: ../admin/attendanceReports.php");
    exit;
}

if(isset($_POST['register_admin'])){
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password !== $confirm_password){
        $_SESSION['message'] = "Passwords do not match";
        $_SESSION['status'] = "400";
        header("Location: ../admin/register.php");
        exit;
    }

    $userObj->register($full_name, $email, $password, 'admin');
    $user = $userObj->login($email, $password);
    $_SESSION['message'] = "Registration successful. Please login.";
    $_SESSION['status'] = "200";
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['updateExcuseStatus'])) {
    $excuse_id = $_POST['excuse_id'];
    $status = $_POST['status'];

    if ($excuseObj->updateStatus($excuse_id, $status)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}


// login
if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

    $user = $userObj->login($email, $pass);

    if($user){
        $_SESSION['user'] = $user;
        header("Location: ../".$user['role']."/dashboard.php");
        exit;
    } else {
        header("Location: ../index.php?error=1");
        exit;
    }
}


// logout
if (isset($_GET['logoutUserBtn'])) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}