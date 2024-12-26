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

$user_id = $_SESSION['user_id'];

// 데이터 삭제 처리
if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    $delete_table = $_POST['delete_table'];
    $sql = "DELETE FROM $delete_table WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// 각 테이블에서 사용자 데이터 가져오기
$tables = ['language_exams', 'certifications', 'academic_activities', 'reading_activities', 'dop_activities', 'program_activities', 'physical_tests', 'physical_certifications', 'physical_competitions'];

$all_data = [];

foreach ($tables as $table) {
    $sql = "SELECT * FROM $table WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $all_data[$table] = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>사용자 기록 조회</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>사용자 기록 조회</h2>

    <?php foreach ($all_data as $table => $data): ?>
        <h3><?php echo ucfirst(str_replace('_', ' ', $table)); ?></h3>
        <?php if (!empty($data)): ?>
            <table>
                <tr>
                    <?php foreach ($data[0] as $key => $value): ?>
                        <?php if ($key != 'id' && $key != 'user_id'): ?>
                            <th><?php echo ucfirst(str_replace('_', ' ', $key)); ?></th>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php foreach ($row as $key => $value): ?>
                            <?php if ($key != 'id' && $key != 'user_id'): ?>
                                <td><?php echo htmlspecialchars($value); ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <td>
                            <form action="K_edit_record.php" method="post">
                                <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="edit_table" value="<?php echo htmlspecialchars($table); ?>">
                                <input type="submit" value="수정">
                            </form>
                        </td>
                        <td>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="delete_table" value="<?php echo htmlspecialchars($table); ?>">
                                <input type="submit" name="delete" value="삭제" onclick="return confirm('정말로 삭제하시겠습니까?');">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>데이터가 없습니다.</p>
        <?php endif; ?>
    <?php endforeach; ?>

    <br>
    <button onclick="location.href='K_select_category.php'">홈으로</button>
</body>
</html>