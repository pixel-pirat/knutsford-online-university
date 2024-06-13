<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knutsford - Login</title>
    <style>
          body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .container input {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .container button {
            width: 100%;
            padding: 10px;
            background-color: #6200ea;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .container button:hover {
            background-color: #3700b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Knutsford</h2>
        <form method="POST" action="login.php">
            <input type="text" name="studentid" placeholder="Student ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>

    <?php
    session_start();

    if (isset($_POST['login'])) {
        $studentid = $_POST['studentid'];
        $password = $_POST['password'];

        $conn = new mysqli('localhost', 'username', 'password', 'user_management');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT id, fullname, email, password FROM users WHERE studentid = ?");
        $stmt->bind_param("s", $studentid);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $fullname, $email, $hashed_password);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['userid'] = $id;
                $_SESSION['fullname'] = $fullname;
                $_SESSION['email'] = $email;
                $_SESSION['studentid'] = $studentid;
                header("Location: profile.php");
                exit();
            } else {
                echo "<script>alert('Invalid Student ID or Password');</script>";
            }
        } else {
            echo "<script>alert('Invalid Student ID or Password');</script>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
