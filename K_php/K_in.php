<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['user_id'])) {
    header("Location: K_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>인 카테고리</title>
</head>
<body>
    <h2>인 카테고리</h2>
    <button onclick="location.href='K_reading_activity.php'">독서활동</button>
    <button onclick="location.href='K_dop.php'">DOP</button>
    <button onclick="location.href='K_program.php'">프로그램</button>
    <br><br>
    <button onclick="location.href='K_select_category.php'">홈으로</button>
</body>
</html>