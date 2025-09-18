<?php
require_once 'classes/course.php';

$courseObj = new Course();
$courses = $courseObj->getAllCourses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: dimgray;
            font-weight: bold;
            margin-bottom: 35px;
            text-align: center;
        }

        .form-box {
            background: #fff;
            border: 2px solid #a78385;
            border-radius: 8px;
            padding: 20px;
            margin: 30px auto;
            max-width: 500px;
        }

        a {
            color: #a78385;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }


        label {
            display: block;
            margin-bottom: 5px;
            color: #a78385;
            font-weight: bold;
        }

        input[type="text"], 
        input[type="email"], 
        input[type="password"], 
        input[type="number"], 
        select {
            width: 100%;       
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; 
        }

        button {
            background: #a78385;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background: #c8aeb0;
        }
    </style>
</head>
<body>
    <a href="index.php">Back to Login</a>

    <div class="form-box">
        <h1>Student Registration</h1>
        <form method="POST" action="handlers/handleForms.php">
            <label for="full_name">Full Name</label>
            <input type="text" name="full_name" id="full_name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <label for="student_id">Student ID</label>
            <input type="text" name="student_id" id="student_id" required>

            <label for="course">Course</label>
            <select name="course" id="course" required>
                <option value="">Select Course</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?= htmlspecialchars($c['name']) ?>">
                        <?= htmlspecialchars($c['name']) ?> (<?= htmlspecialchars($c['code']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="year_level">Year Level</label>
            <input type="number" name="year_level" id="year_level" min="1" max="6" required>

            <button type="submit" name="register_student">Register</button>
        </form>
    </div>
</body>
</html>