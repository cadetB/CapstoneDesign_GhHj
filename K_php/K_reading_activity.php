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
    $program_type = $_POST['program_type'];
    $participation_date = $_POST['participation_date'];
    $award_type = isset($_POST['award_type']) ? $_POST['award_type'] : null;

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

    $sql = "INSERT INTO reading_activities (user_id, program_type, participation_date, award_type, proof_file_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $program_type, $participation_date, $award_type, $proof_file_path);

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
    <title>독서활동 정보 입력</title>
    <script>
        function showAwardType() {
            var programType = document.getElementById('program_type');
            var awardTypeDiv = document.getElementById('award_type_div');
            if (programType.value === 'book_report_contest') {
                awardTypeDiv.style.display = 'block';
            } else {
                awardTypeDiv.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <h2>독서활동 정보 입력</h2>
    
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
        <button onclick="location.href='K_select_category.php'">홈으로</button>
    <?php else: ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <label for="program_type">프로그램 선택:</label>
            <select id="program_type" name="program_type" required onchange="showAwardType()">
                <option value="reading_program">독서프로그램</option>
                <option value="book_report_contest">독후감 대회</option>
            </select><br><br>

            <label for="participation_date">참여 일자:</label>
            <input type="date" id="participation_date" name="participation_date" required><br><br>

            <div id="award_type_div" style="display: none;">
                <label for="award_type">입상 선택:</label>
                <select id="award_type" name="award_type">
                    <option value="internal">영내 입상</option>
                    <option value="external">대외 입상</option>
                </select><br><br>
            </div>

            <label for="proof_file">증빙자료 업로드:</label>
            <input type="file" id="proof_file" name="proof_file" accept=".pdf,.jpg,.png,.doc,.docx"><br><br>

            <input type="submit" value="제출">
        </form>
    <?php endif; ?>
</body>
</html>