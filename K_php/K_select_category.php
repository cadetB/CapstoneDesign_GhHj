<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['user_id'])) {
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

// 사용자 정보 가져오기
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>분야 선택</title>
</head>
<body>
    <h2><?php echo $user['name']; ?>님, 기입할 분야를 선택하세요.</h2>
    <button onclick="location.href='K_ji.php'">지</button>
    <button onclick="location.href='K_in.php'">인</button>
    <button onclick="location.href='K_yong.php'">용</button>
    <br><br>
    <button onclick="location.href='K_view_records.php'">조회</button>
    <button onclick="location.href='K_logout.php'">로그아웃</button>
</body>
</html>