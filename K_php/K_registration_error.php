<?php
// K_registration_error.php

$error_message = "";
if (isset($_GET['error']) && $_GET['error'] == 'duplicate') {
    $error_message = "이미 등록된 교번입니다.";
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>등록 오류</title>
</head>
<body>
    <h2>등록 오류</h2>
    <?php if($error_message) echo "<p>$error_message</p>"; ?>
    <button onclick="location.href='K_register.php'">등록</button>
</body>
</html>