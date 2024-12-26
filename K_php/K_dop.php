<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: K_login.php");
    exit();
}

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
    $activity_type = $_POST['activity_type'];
    $activity_date = $_POST['activity_date'];
    $details = isset($_POST['details']) ? $_POST['details'] : null;
    $dop_selection = $_POST['dop_selection'];

    $proof_file_path = "";
    if ($_FILES['proof_file']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $proof_file_path = $upload_dir . basename($_FILES['proof_file']['name']);
        move_uploaded_file($_FILES['proof_file']['tmp_name'], $proof_file_path);
    }

    $sql = "INSERT INTO dop_activities (user_id, activity_type, activity_date, details, dop_selection, proof_file_path) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $user_id, $activity_type, $activity_date, $details, $dop_selection, $proof_file_path);

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
    <title>DOP 활동 정보 입력</title>
    <script>
        function showDetails() {
            var activityType = document.getElementById('activity_type');
            var detailsDiv = document.getElementById('details_div');
            if (activityType.value === 'volunteer') {
                detailsDiv.style.display = 'block';
            } else {
                detailsDiv.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <h2>DOP 활동 정보 입력</h2>
    
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
        <button onclick="location.href='K_select_category.php'">홈으로</button>
    <?php else: ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <label for="activity_type">활동 선택:</label>
            <select id="activity_type" name="activity_type" required onchange="showDetails()">
                <option value="volunteer">봉사활동</option>
                <option value="blood_donation">헌혈</option>
            </select><br><br>

            <label for="activity_date">활동 일자:</label>
            <input type="date" id="activity_date" name="activity_date" required><br><br>

            <div id="details_div" style="display: none;">
                <label for="details">세부 내용:</label>
                <textarea id="details" name="details" rows="4" cols="50"></textarea><br><br>
            </div>

            <label for="dop_selection">DOP생도 선정:</label>
            <select id="dop_selection" name="dop_selection" required>
                <option value="best">최우수</option>
                <option value="excellent">우수</option>
                <option value="good">장려</option>
                <option value="none">해당사항 없음</option>
            </select><br><br>

            <label for="proof_file">증빙자료 업로드:</label>
            <input type="file" id="proof_file" name="proof_file" accept=".pdf,.jpg,.png,.doc,.docx" required><br><br>

            <input type="submit" value="제출">
        </form>
    <?php endif; ?>
</body>
</html>