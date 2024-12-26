<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: K_login.php");
    exit();
}

$servername = "%";
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
    $activity_type = $_POST['activity_type'];
    $participation_date = $_POST['participation_date'];

    $proof_file_path = "";
    if ($_FILES['proof_file']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $proof_file_path = $upload_dir . basename($_FILES['proof_file']['name']);
        move_uploaded_file($_FILES['proof_file']['tmp_name'], $proof_file_path);
    }

    $sql = "INSERT INTO program_activities (user_id, activity_type, participation_date, proof_file_path) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $activity_type, $participation_date, $proof_file_path);

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
    <title>프로그램 활동 정보 입력</title>
</head>
<body>
    <h2>프로그램 활동 정보 입력</h2>
    
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
        <button onclick="location.href='K_select_category.php'">홈으로</button>
    <?php else: ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <label for="activity_type">활동 선택:</label>
            <select id="activity_type" name="activity_type" required>
                <option value="holiday_program">휴일 프로그램</option>
                <option value="small_group">소모임</option>
            </select><br><br>

            <label for="participation_date">참여 일자:</label>
            <input type="date" id="participation_date" name="participation_date" required><br><br>

            <label for="proof_file">증빙자료 업로드:</label>
            <input type="file" id="proof_file" name="proof_file" accept=".pdf,.jpg,.png,.doc,.docx" required><br><br>

            <input type="submit" value="제출">
        </form>
    <?php endif; ?>
</body>
</html>