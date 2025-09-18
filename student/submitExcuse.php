<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student'){
    header("Location: ../index.php");
    exit;
}

include 'navbar.php';
require_once '../classes/student.php';
require_once '../classes/excuseLetter.php';

$studentObj = new Student();
$excuseObj = new ExcuseLetter();

$studentId = $_SESSION['user']['id'];

$student = $studentObj->selectOne("students", "user_id = :uid", ["uid"=>$studentId]);
$excuses = $excuseObj->selectAll("excuse_letters", "student_id = :sid", ["sid"=>$student['id']]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Excuse Letter</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5; 
            margin: 0; 
            padding: 0; 
        }
        h1 { 
            margin-top: 30px; 
            text-align: center; 
            color: dimgray; 
        }
        .form-box, .table-box {
            background: #fff; 
            border: 2px solid #a78385; 
            border-radius: 10px;
            padding: 20px; 
            margin: 20px auto; 
            max-width: 700px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
            color: #a78385; 
        }
        textarea { 
            width: 100%; 
            padding: 10px; 
            border-radius: 6px; 
            border: 1px solid #ccc; 
            margin-bottom: 15px; 
        }
        input[type="file"] {
            display: block;
            margin-bottom: 15px;
        }
        button { 
            background: #a78385; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 6px; 
            cursor: pointer; 
            display: block;
            margin: 10px auto 0 auto;
        }
        button:hover { 
            background: #c8aeb0; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: center; 
        }
        th { 
            background: #c8aeb0; 
            color: white; 
        }
    </style>
</head>
<body>
    <h1>Excuse Letter Submission</h1>

    <div class="form-box">
        <form method="POST" action="../handlers/handleForms.php" enctype="multipart/form-data">
            <input type="hidden" name="submit_excuse" value="1">
            <input type="hidden" name="student_id" value="<?= $student['id'] ?>">

            <label for="reason">Reason</label>
            <textarea name="reason" id="reason" rows="4" required></textarea>

            <label for="photo">Upload handwritten letter</label>
            <input type="file" name="photo" id="photo" accept="image/*">

            <button type="submit">Submit</button>
        </form>
    </div>

    <div class="table-box">
        <h2>Your Submitted Excuse Letters</h2>
        <?php if($excuses): ?>
        <table>
            <tr>
                <th>Date Submitted</th>
                <th>Reason</th>
                <th>Letter</th>
                <th>Status</th>
            </tr>
            <?php foreach($excuses as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['date_submitted']) ?></td>
                <td><?= htmlspecialchars($e['reason']) ?></td>
                <td>
                    <?php if (!empty($e['photo'])): ?>
                        <a href="<?= htmlspecialchars($e['photo']) ?>" target="_blank">
                            <img src="<?= htmlspecialchars($e['photo']) ?>" alt="Photo" style="max-width:80px; max-height:80px;">
                        </a>
                    <?php else: ?>
                        No Photo
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                        if ($e['status'] === 'Pending') echo "Pending";
                        elseif ($e['status'] === 'Approved') echo "Approved";
                        else echo "Rejected";
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
            <p style="text-align:center;">No excuse letters submitted yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>