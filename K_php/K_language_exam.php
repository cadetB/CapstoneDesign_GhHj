<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
    <label for="exam_type">시험 선택:</label>
    <select id="exam_type" name="exam_type" required onchange="showExamFields()">
        <option value="TOEIC">토익</option>
        <option value="TOEIC Speaking">토익스피킹</option>
        <option value="HSK">HSK</option>
        <option value="JLPT">JLPT</option>
        <option value="OTHER">기타</option>
    </select><br><br>

    <div id="other_exam_type_div" style="display: none;">
        <label for="other_exam_type">기타 시험 종류:</label>
        <input type="text" id="other_exam_type" name="other_exam_type"><br><br>
    </div>

    <div id="hsk_level_div" style="display: none;">
        <label for="hsk_level">HSK 등급:</label>
        <select id="hsk_level" name="hsk_level">
            <option value="1">1급</option>
            <option value="2">2급</option>
            <option value="3">3급</option>
            <option value="4">4급</option>
            <option value="5">5급</option>
            <option value="6">6급</option>
        </select><br><br>
    </div>

    <div id="jlpt_level_div" style="display: none;">
        <label for="jlpt_level">JLPT 등급:</label>
        <select id="jlpt_level" name="jlpt_level">
            <option value="N5">N5</option>
            <option value="N4">N4</option>
            <option value="N3">N3</option>
            <option value="N2">N2</option>
            <option value="N1">N1</option>
        </select><br><br>
    </div>

    <div id="score_div" style="display: none;">
        <label for="score">점수:</label>
        <input type="number" id="score" name="score"><br><br>
    </div>

    <div id="exam_date_div" style="display: none;">
        <label for="exam_date">응시 일자:</label>
        <input type="date" id="exam_date" name="exam_date"><br><br>
    </div>

    <div id="improvement_score_div" style="display: none;">
        <label for="improvement_score">전 학기 대비 향상 점수:</label>
        <input type="number" id="improvement_score" name="improvement_score"><br><br>
    </div>

    <div id="proof_file_div" style="display: none;">
        <label for="proof_file">증빙자료 업로드:</label>
        <input type="file" id="proof_file" name="proof_file" accept=".pdf,.jpg,.png,.doc,.docx"><br><br>
    </div>

    <input type="submit" value="제출">
</form>

<script>
function showExamFields() {
    var examType = document.getElementById('exam_type').value;
    var otherExamTypeDiv = document.getElementById('other_exam_type_div');
    var hskLevelDiv = document.getElementById('hsk_level_div');
    var jlptLevelDiv = document.getElementById('jlpt_level_div');
    var scoreDiv = document.getElementById('score_div');
    var examDateDiv = document.getElementById('exam_date_div');
    var improvementScoreDiv = document.getElementById('improvement_score_div');
    var proofFileDiv = document.getElementById('proof_file_div');

    otherExamTypeDiv.style.display = 'none';
    hskLevelDiv.style.display = 'none';
    jlptLevelDiv.style.display = 'none';
    scoreDiv.style.display = 'none';
    examDateDiv.style.display = 'none';
    improvementScoreDiv.style.display = 'none';
    proofFileDiv.style.display = 'none';

    if (examType === 'OTHER') {
        otherExamTypeDiv.style.display = 'block';
        examDateDiv.style.display = 'block';
        proofFileDiv.style.display = 'block';
    } else if (examType === 'HSK') {
        hskLevelDiv.style.display = 'block';
        examDateDiv.style.display = 'block';
        proofFileDiv.style.display = 'block';
    } else if (examType === 'JLPT') {
        jlptLevelDiv.style.display = 'block';
        examDateDiv.style.display = 'block';
        proofFileDiv.style.display = 'block';
    } else if (examType === 'TOEIC' || examType === 'TOEIC Speaking') {
        scoreDiv.style.display = 'block';
        examDateDiv.style.display = 'block';
        improvementScoreDiv.style.display = 'block';
        proofFileDiv.style.display = 'block';
    }
}
</script>
<body>
    <h2>어학시험 정보 입력</h2>
    
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
        <button onclick="location.href='K_select_category.php'">홈으로</button>
    <?php else: ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <label for="exam_type">시험 선택:</label>
            <select id="exam_type" name="exam_type" required onchange="showOtherExamType()">
                <option value="TOEIC">토익</option>
                <option value="TOEFL">토플</option>
                <option value="TOEIC Speaking">토익스피킹</option>
                <option value="OTHER">기타</option>
            </select><br><br>

            <div id="other_exam_type_div" style="display: none;">
                <label for="other_exam_type">기타 시험 종류:</label>
                <input type="text" id="other_exam_type" name="other_exam_type"><br><br>
            </div>

            <label for="score">점수 입력:</label>
            <input type="number" id="score" name="score" required><br><br>

            <label for="exam_date">응시 일자 선택:</label>
            <input type="date" id="exam_date" name="exam_date" required><br><br>

            <label for="improvement_score">전 학기 대비 향상 점수:</label>
            <input type="number" id="improvement_score" name="improvement_score"><br><br>

            <label for="proof_file">증빙자료 업로드:</label>
            <input type="file" id="proof_file" name="proof_file" accept=".pdf,.jpg,.png,.doc,.docx"><br><br>

            <input type="submit" value="제출">
        </form>
    <?php endif; ?>
</body>
</html>