<?php
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
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $admin_code = isset($_POST['admin_code']) ? $_POST['admin_code'] : '';

    // 교번 중복 확인
    $check_sql = "SELECT * FROM users WHERE student_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $student_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // 이미 존재하는 교번인 경우
        header("Location: K_registration_error.php?error=duplicate");
        exit();
    }

    if ($password !== $confirm_password) {
        $message = "비밀번호가 일치하지 않습니다.";
    } elseif ($role == 'admin' && $admin_code !== '0000') {
        $message = "관리자 코드가 올바르지 않습니다.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (student_id, name, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $student_id, $name, $hashed_password, $role);
        
        if ($stmt->execute()) {
            header("Location: K_registration_complete.php");
            exit();
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
        $stmt->close();
    }
    $check_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>사용자 등록</title>
    <script>
        function showAdminCode() {
            var role = document.getElementById('role').value;
            var adminCodeDiv = document.getElementById('adminCodeDiv');
            if (role == 'admin') {
                adminCodeDiv.style.display = 'block';
            } else {
                adminCodeDiv.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <h2>사용자 등록</h2>
    <?php if($message) echo "<p>$message</p>"; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        교번: <input type="text" name="student_id" required><br>
        성명: <input type="text" name="name" required><br>
        비밀번호: <input type="password" name="password" required><br>
        비밀번호 확인: <input type="password" name="confirm_password" required><br>
        권한:
        <select name="role" id="role" onchange="showAdminCode()">
            <option value="user">사용자</option>
            <option value="admin">관리자</option>
        </select><br>
        <div id="adminCodeDiv" style="display: none;">
            관리자 코드: <input type="password" name="admin_code"><br>
        </div>
        <input type="submit" value="등록">
    </form>
    <br>
    <button onclick="location.href='K_login.php'">로그인</button>
</body>
</html>