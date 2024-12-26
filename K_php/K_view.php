<?php
session_start();

// 관리자 권한 확인
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: K_login.php");
    exit();
}

// 데이터베이스 연결 설정
$servername = "%";
$username = "GHHJ";
$password = "1234";
$dbname = "KK";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$user_info = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];

    $sql = "SELECT * FROM users WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_info = $result->fetch_assoc();
    } else {
        $message = "해당 교번의 사용자를 찾을 수 없습니다.";
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
    <title>사용자 정보 조회</title>
</head>
<body>
    <h2>사용자 정보 조회</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="student_id">조회할 교번을 입력하세요:</label>
        <input type="text" id="student_id" name="student_id" required>
        <input type="submit" value="조회">
    </form>

    <?php if($message) echo "<p>$message</p>"; ?>

    <?php if($user_info): ?>
        <h3>사용자 정보</h3>
        <p>교번: <?php echo $user_info['student_id']; ?></p>
        <p>이름: <?php echo $user_info['name']; ?></p>
        <p>권한: <?php echo $user_info['role']; ?></p>
        <!-- 여기에 추가적인 사용자 정보를 표시할 수 있습니다 -->
    <?php endif; ?>

    <br>
    <button onclick="location.href='K_select_category.php'">홈으로</button>
</body>
</html>