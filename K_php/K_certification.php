<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['user_id'])) {
    header("Location: K_login.php");
    exit();
}

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
    $user_id = $_SESSION['user_id'];
    $certification_name = $_POST['certification_name'];
    $acquisition_date = $_POST['acquisition_date'];

    // 파일 업로드 처리
    $proof_file_path = "";
    if ($_FILES['proof_file']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $proof_file_path = $upload_dir . basename($_FILES['proof_file']['name']);
        move_uploaded_file($_FILES['proof_file']['tmp_name'], $proof_file_path);
    }

    // 데이터베이스에 정보 저장
    $sql = "INSERT INTO certifications (user_id, certification_name, acquisition_date, proof_file_path) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $certification_name, $acquisition_date, $proof_file_path);

    if ($stmt->execute()) {
        $message = "제출이 완료되었습니다.";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
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
    <title>자격증 정보 입력</title>
</head>
<body>
    <h2>자격증 정보 입력</h2>
    
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
        <button onclick="location.href='K_select_category.php'">홈으로</button>
    <?php else: ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <label for="certification_name">자격증 이름:</label>
            <input type="text" id="certification_name" name="certification_name" required><br><br>

            <label for="acquisition_date">취득 일자:</label>
            <input type="date" id="acquisition_date" name="acquisition_date" required><br><br>

            <label for="proof_file">증빙자료 업로드:</label>
            <input type="file" id="proof_file" name="proof_file" accept=".pdf,.jpg,.png,.doc,.docx" required><br><br>

            <input type="submit" value="제출">
        </form>
    <?php endif; ?>
</body>
</html>