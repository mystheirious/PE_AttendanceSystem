<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
            margin-bottom: 40px;
            text-align: center;
        }

        .form-box {
            background: #fff;
            border: 2px solid #a78385;
            border-radius: 8px;
            padding: 20px;
            margin: 30px auto;
            max-width: 400px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #a78385;
            text-align: left;
            font-weight: bold;
        }

        input[type="email"], 
        input[type="password"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background: #c8aeb0;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background: #a78385;
        }

        p {
            margin-top: 15px;
        }

        a {
            color: #a78385;
            font-weight: bold;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h1>Login</h1>
        <form method="POST" action="handlers/handleForms.php">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" name="login">Login</button>
            <p>Don't have an account yet? You may <a href="register.php">register here!</a></p>
        </form>
    </div>
</body>
</html>