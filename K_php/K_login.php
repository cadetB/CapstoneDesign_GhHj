<?php
session_start();

// 데이터베이스 연결 설정
$servername = "localhost";
$username = "GHHJ";
$password = "1234";
$dbname = "KK";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            
            // 관리자와 일반 사용자를 구분하여 다른 페이지로 리다이렉트
            if ($user['role'] == 'admin') {
                header("Location: K_view.php");
            } else {
                header("Location: K_select_category.php");
            }
            exit();
        } else {
            $message = "비밀번호가 일치하지 않습니다.";
        }
    } else {
        $message = "등록되지 않은 교번입니다.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
</head>
<body>
    <h2>로그인</h2>
    <?php if($message) echo "<p>$message</p>"; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        교번: <input type="text" name="student_id" required><br>
        비밀번호: <input type="password" name="password" required><br>
        <input type="submit" value="로그인">
    </form>
    <br>
    <button onclick="location.href='K_register.php'">등록</button>
</body>
</html>