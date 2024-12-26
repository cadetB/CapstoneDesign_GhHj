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
    $pass_status = $_POST['pass_status'];
    $grade = $_POST['grade'];
    $total_score = $_POST['total_score'];
    $previous_score = $_POST['previous_score'];

    $sql = "INSERT INTO physical_tests (user_id, pass_status, grade, total_score, previous_score) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issii", $user_id, $pass_status, $grade, $total_score, $previous_score);

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
    <title>체력검정 정보 입력</title>
</head>
<body>
    <h2>체력검정 정보 입력</h2>
    
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
        <button onclick="location.href='K_select_category.php'">홈으로</button>
    <?php else: ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="pass_status">합불여부:</label>
            <select id="pass_status" name="pass_status" required>
                <option value="regular_pass">정기 합격</option>
                <option value="first_additional_pass">1차 추가 합격</option>
                <option value="second_additional_pass">2차 추가 합격</option>
                <option value="regular_fail">정기 불합격</option>
            </select><br><br>

            <label for="grade">등급:</label>
            <select id="grade" name="grade" required>
                <option value="gold">골드</option>
                <option value="silver">실버</option>
                <option value="special">특급</option>
                <option value="first">1급</option>
                <option value="second">2급</option>
                <option value="third">3급</option>
            </select><br><br>

            <label for="total_score">총점:</label>
            <input type="number" id="total_score" name="total_score" required><br><br>

            <label for="previous_score">전 학기 점수:</label>
            <input type="number" id="previous_score" name="previous_score" required><br><br>

            <input type="submit" value="제출">
        </form>
    <?php endif; ?>
</body>
</html>