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
    <title>지 카테고리</title>
</head>
<body>
    <h2>지 카테고리</h2>
    <button onclick="location.href='K_language_exam.php'">어학시험</button>
    <button onclick="location.href='K_certification.php'">자격증</button>
    <button onclick="location.href='K_academic_conference.php'">학술관련</button>
    <br><br>
    <button onclick="location.href='K_select_category.php'">홈으로</button>
</body>
</html>