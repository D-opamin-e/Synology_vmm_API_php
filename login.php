<?php
session_start();
// 데이터베이스 연결
$conn = mysqli_connect('localhost', 'root', 'DB_PW', 'DB_table');

// 데이터베이스 연결 확인
if (!$conn) {
    die('데이터베이스 연결 실패: ' . mysqli_connect_error());
}

// 폼 데이터를 처리합니다.
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // 입력된 사용자 이름과 비밀번호를 확인합니다.
    $query = "SELECT * FROM test WHERE id='$username' AND pw='$password'";
    $result = mysqli_query($conn, $query);

    // 사용자 이름과 비밀번호가 일치하는 경우, 세션에 사용자 정보를 저장하고 index.php로 이동합니다.
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $username;
        if ($row['admin'] == 1) {
            header('Location: admin.php');
        } else {
            header('Location: index.php');
        }
    } else {
        $error = '잘못된 사용자 이름 또는 비밀번호입니다.';
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>로그인</title>
        <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            margin-top: 50px;
            text-align: center;
        }
        form {
            max-width: 300px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="password"], input[type="submit"] {
            display: block;
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            width: 100%;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #3e8e41;
        }
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>호스팅 고객 로그인</h2>
    <?php if (isset($error)) { ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="username" >사용자 이름:</label>
        <input type="text" name="username" required><br><br>
        <label for="password">비밀번호:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" name="submit" value="로그인">
    </form>
</body>
</html>
