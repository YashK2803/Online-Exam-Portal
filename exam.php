<?php
session_start();
$studentid = $_SESSION['student_id'] ?? null;
$eid = $_GET['EID'] ?? null;

// Database connection
$conn = new mysqli("localhost", "root", "", "lab4");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Timer setup (1-hour countdown)
if (!isset($_SESSION['Start_Time'][$eid])) {
    $_SESSION['Start_Time'][$eid] = time();
}
$remainingTime = 3600 - (time() - $_SESSION['Start_Time'][$eid]);

// End the exam if time runs out or if exam is submitted
if ($remainingTime <= 0 || isset($_POST['submit_exam'])) {
    endExam($conn, $studentid, $eid);
    exit();
}

// Initialize session variables for questions and progress
if (!isset($_SESSION['questions'][$eid])) {
    $_SESSION['questions'][$eid] = fetchQuestions($conn, $eid);

    // Check if questions were fetched successfully
    if (empty($_SESSION['questions'][$eid])) {
        echo "No questions available for exam ID: $eid.";
        exit();
    }

    $_SESSION['current_question'][$eid] = 0;
    $_SESSION['correct_easy_count'][$eid] = 0;
}

$questions = $_SESSION['questions'][$eid] ?? [];
$currentQuestionIndex = $_SESSION['current_question'][$eid] ?? 0;

// If the exam is completed
if ($currentQuestionIndex >= count($questions)) {
    endExam($conn, $studentid, $eid);
    exit();
}

// Handle user response
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['option'])) {
    $selectedOption = $_POST['option'];
    $currentQuestion = $questions[$currentQuestionIndex] ?? null;

    if ($currentQuestion) {
        $qid = $currentQuestion['QID'];
        $correctAnswer = $currentQuestion['Answer'];

        // Store the response in the answers table
        $stmt = $conn->prepare("INSERT INTO answers (SID, EID, QID, Answer) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $studentid, $eid, $qid, $selectedOption);
        $stmt->execute();
        $stmt->close();

        // Check if the answer is correct
        if ($selectedOption == $correctAnswer && $currentQuestion['Difficulty'] == 'Easy') {
            $_SESSION['correct_easy_count'][$eid] = ($_SESSION['correct_easy_count'][$eid] ?? 0) + 1;
        }

        // Move to the next question
        $_SESSION['current_question'][$eid]++;
        header("Location: exam.php?EID=$eid");
        exit();
    }
}

// Fetch the current question
$currentQuestion = $questions[$currentQuestionIndex] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam</title>
    <style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #e0eafc, #cfdef3);
    color: #333;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    max-width: 800px;
    margin-bottom: 20px;
}

.timer {
    font-size: 18px;
    font-weight: bold;
    color: #e74c3c;
}

.profile-photo {
    position: absolute;
    left: 0;
    top: 0;
    width: 60px;  /* Adjust size as needed */
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

button {
    padding: 12px 25px;
    background: linear-gradient(135deg, #4b79a1, #283e51);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: transform 0.2s, background 0.3s;
}

button:hover {
    transform: translateY(-3px);
    background: linear-gradient(135deg, #2c3e50, #1c2833);
}

.result-container {
    background-color: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    text-align: center;
    margin-top: 40px;
}

h2 {
    font-size: 28px;
    color: #2c3e50;
    margin-bottom: 20px;
}

p {
    font-size: 18px;
    color: #555;
}

.result-details {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.result-card {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    width: 30%;
    text-align: center;
}

.result-card h3 {
    margin: 0;
    font-size: 1.2rem;
    color: #555;
}

.result-card p {
    font-size: 1.3rem;
    font-weight: bold;
    color: #000;
    margin-top: 5px;
}

.strength-weakness {
    margin-top: 30px;
    font-size: 18px;
    text-align: left;
}

.strength {
    color: #27ae60;
    font-weight: bold;
}

.weakness {
    color: #e74c3c;
    font-weight: bold;
}

table {
    width: 100%;
    margin-top: 30px;
    border-collapse: collapse;
    background-color: white;
}

th, td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
    font-size: 16px;
}

th {
    background-color: #4b79a1;
    color: white;
}

.correct {
    color: #27ae60;
    font-weight: bold;
}

.incorrect {
    color: #e74c3c;
    font-weight: bold;
}

.options-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-top: 20px;
}

.option-btn {
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: transform 0.2s, background 0.3s, box-shadow 0.3s;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.option-btn:hover {
    transform: scale(1.05);
    background: linear-gradient(135deg, #1f618d, #154360);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

.option-btn:active {
    transform: scale(0.95);
}

.top-tab {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: linear-gradient(to right, #4b79a1, #283e51);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    z-index: 1000;
}

.photo-container {
    display: flex;
    align-items: center;
    margin-left: 40px;
}

.profile-photo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.timer {
    font-size: 18px;
    font-weight: bold;
    color: #ffdd57;
}

.submit-btn {
    padding: 12px 20px;
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: transform 0.2s, background 0.3s;
    flex-shrink: 0; /* Prevents the button from getting squeezed */
    margin-right: 40px;
}

.submit-btn:hover {
    transform: scale(1.05);
    background: #c0392b;
}

.container {
    max-width: 800px;
    width: 100%;
    padding: 30px;
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin-top: 150px; /* Spacing from top-tab */
}
    </style>
</head>
<body>
<!-- <div class="top-bar">
    <img src="photo.png" alt="Student Photo" class="profile-photo">
    <div class="timer">
        <?php if (!isset($_POST['submit_exam'])): ?>
            Time Left: <span id="timer"><?php echo gmdate("H:i:s", max($remainingTime, 0)); ?></span>
        <?php endif; ?>
    </div>
    <?php if (!isset($_POST['submit_exam'])): ?>
        <form method="POST">
            <button type="submit" name="submit_exam" class="submit-btn">Submit Exam</button>
        </form>
    <?php endif; ?>
</div> -->
<div class="top-tab">
    <div class="photo-container">
        <img src="photo.png" alt="Student Photo" class="profile-photo">
    </div>
    <div class="timer">
        <?php if (!isset($_POST['submit_exam'])): ?>
            Time Left: <span id="timer"><?php echo gmdate("H:i:s", max($remainingTime, 0)); ?></span>
        <?php endif; ?>
    </div>
    <?php if (!isset($_POST['submit_exam'])): ?>
        <form method="POST">
            <button type="submit" name="submit_exam" class="submit-btn">Submit Exam</button>
        </form>
    <?php endif; ?>
</div>

<script>
    let remainingTime = <?php echo $remainingTime; ?>;
    let timerInterval;

    function updateTimer() {
        if (remainingTime <= 0) {
            alert("Time's up! The exam will be submitted automatically.");
            window.location.href = "exam.php?EID=<?php echo $eid; ?>";
            return;
        }

        remainingTime--;

        let hours = Math.floor(remainingTime / 3600);
        let minutes = Math.floor((remainingTime % 3600) / 60);
        let seconds = remainingTime % 60;

        document.getElementById('timer').textContent = 
            String(hours).padStart(2, '0') + ":" + 
            String(minutes).padStart(2, '0') + ":" + 
            String(seconds).padStart(2, '0');
    }

    function stopTimer() {
        clearInterval(timerInterval);
    }

    <?php if (isset($_POST['submit_exam'])): ?>
        stopTimer(); // Stop timer when exam is submitted
    <?php else: ?>
        // Start the timer when the page loads
        timerInterval = setInterval(updateTimer, 1000);
    <?php endif; ?>
</script>

<div class="container">
    <?php if (!isset($_POST['submit_exam']) && $currentQuestion): ?>
        <h2>Question <?php echo $currentQuestionIndex + 1; ?></h2>
        <p><?php echo htmlspecialchars($currentQuestion['Question']);?></p>
        <form method="POST" class="options-container">
    <button type="submit" name="option" value="A" class="option-btn">A</button>
    <button type="submit" name="option" value="B" class="option-btn">B</button>
    <button type="submit" name="option" value="C" class="option-btn">C</button>
    <button type="submit" name="option" value="D" class="option-btn">D</button>
</form>

    <?php elseif (isset($_POST['submit_exam'])): ?>
        <!-- Show the result page here -->
        <h2>Exam Submitted</h2>
        <p>Your results are being processed. You will be redirected shortly.</p>
    <?php else: ?>
        <p>No question available.</p>
    <?php endif; ?>
</div>

<?php
// This part is for when the exam is submitted, or time runs out
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_exam'])) {
    // Stop timer and call endExam function
    endExam($conn, $studentid, $eid);
    exit();
}
?>

<?php
function fetchQuestions($conn, $eid) {
    $questions = [];
    $result = $conn->query("SELECT QID, Question, Answer, Difficulty, Topic FROM questions WHERE EID = $eid ORDER BY 
        CASE Difficulty 
            WHEN 'Easy' THEN 1 
            WHEN 'Medium' THEN 2 
            WHEN 'Hard' THEN 3 
        END LIMIT 10");

    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
    return $questions;
}

function endExam($conn, $studentid, $eid) {
    $score = 0;
    $topicCorrectCount = [];
    $topicTotalCount = [];
    $attemptedQuestions = [];

    // Fetch user answers, correct answers, and questions
    $result = $conn->query("SELECT q.Question AS question, q.Answer AS correct_answer, 
                                    q.Topic AS topic, a.Answer AS user_answer 
                            FROM answers a 
                            JOIN questions q ON a.QID = q.QID 
                            WHERE a.SID = '$studentid' AND a.EID = $eid");

    while ($row = $result->fetch_assoc()) {
        $topic = $row['topic'];
        $topicTotalCount[$topic] = ($topicTotalCount[$topic] ?? 0) + 1;

        $isCorrect = ($row['user_answer'] == $row['correct_answer']);
        if ($isCorrect) {
            $score++;
            $topicCorrectCount[$topic] = ($topicCorrectCount[$topic] ?? 0) + 1;
        }

        // Store attempt details
        $attemptedQuestions[] = [
            'question' => $row['question'],
            'topic' => $row['topic'],
            'correct_answer' => $row['correct_answer'],
            'user_answer' => $row['user_answer'],
            'is_correct' => $isCorrect
        ];
    }

    // Insert or update score in results table
    $stmt = $conn->prepare("INSERT INTO results (SID, EID, Score) VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE Score = ?");
    $stmt->bind_param("siii", $studentid, $eid, $score, $score);
    $stmt->execute();
    $stmt->close();

    // Calculate rank
    $rankQuery = $conn->query("SELECT SID, Score, FIND_IN_SET(Score, (SELECT GROUP_CONCAT(Score ORDER BY Score DESC) FROM results WHERE EID = $eid)) AS rank FROM results WHERE EID = $eid");

    $rank = 0;
    while ($row = $rankQuery->fetch_assoc()) {
        if ($row['SID'] == $studentid) {
            $rank = $row['rank'];
            break;
        }
    }

    // Calculate percentile
    $maxScoreQuery = $conn->query("SELECT MAX(Score) AS max_score FROM results WHERE EID = $eid");
    $maxScore = $maxScoreQuery->fetch_assoc()['max_score'];
    $percentile = ($maxScore > 0) ? ($score / $maxScore) * 100 : 0;

// Determine strengths and weaknesses
$strengths = $weaknesses = [];

// Check if there are any correct answers and handle accordingly
if (!empty($topicTotalCount)) {
    foreach ($topicTotalCount as $topic => $total) {
        $topicCorrectCount[$topic] = $topicCorrectCount[$topic] ?? 0;
    }

    $maxCorrect = !empty($topicCorrectCount) ? max($topicCorrectCount) : 0;
    $minCorrect = !empty($topicCorrectCount) ? min($topicCorrectCount) : 0;

    foreach ($topicCorrectCount as $topic => $count) {
        if ($count == $maxCorrect) {
            $strengths[] = $topic;
        }
        if ($count == $minCorrect) {
            $weaknesses[] = $topic;
        }
    }
}

// Handle the case when no correct answers exist
if (empty($strengths)) {
    $strengthString = "None";
} else {
    $strengthString = implode(", ", $strengths);
}

// Handle the case when no wrong answers exist
if (empty($weaknesses)) {
    $weaknessString = "None";
} else {
    $weaknessString = implode(", ", $weaknesses);
}


    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Exam Result</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f4f4f4;
                display: flex;
                flex-direction: column;
                align-items: center;
                min-height: 100vh;
                margin: 0;
                padding: 20px;
            }
            .result-container {
                background-color: white;
                border-radius: 12px;
                padding: 40px;
                box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
                max-width: 800px;
                text-align: center;
            }
            h2 {
                color: #333;
                margin-bottom: 20px;
            }
            .result-details {
                display: flex;
                justify-content: space-around;
                margin-top: 20px;
            }
            .result-card {
                background-color: #f3f3f3;
                padding: 15px;
                border-radius: 8px;
                width: 30%;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            }
            .result-card h3 {
                margin: 0;
                font-size: 1.2rem;
                color: #555;
            }
            .result-card p {
                font-size: 1.3rem;
                color: #000;
                margin-top: 5px;
            }
            .strength-weakness {
                text-align: left;
                margin-top: 30px;
            }
            .strength-weakness p {
                font-size: 1rem;
                line-height: 1.6;
            }
            .strength {
                color: #27ae60;
                font-weight: bold;
            }
            .weakness {
                color: #e74c3c;
                font-weight: bold;
            }
            table {
                width: 100%;
                margin-top: 30px;
                border-collapse: collapse;
                background-color: white;
            }
            th, td {
                padding: 10px;
                border: 1px solid #ddd;
                text-align: left;
            }
            th {
                background-color: #3498db;
                color: white;
            }
            .correct {
                color: #27ae60;
                font-weight: bold;
            }
            .incorrect {
                color: #e74c3c;
                font-weight: bold;
            }
            button {
                background-color: #3498db;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                margin-top: 20px;
                font-size: 1rem;
            }
            button:hover {
                background-color: #2980b9;
            }
        </style>
    </head>
    <body>
        <div class="result-container">
            <h2>Your Exam Result</h2>
            <div class="result-details">
                <div class="result-card">
                    <h3>Score</h3>
                    <p>{$score}</p>
                </div>
                <div class="result-card">
                    <h3>Rank</h3>
                    <p>{$rank}</p>
                </div>
                <div class="result-card">
                    <h3>Percentile</h3>
                    <p>{$percentile}%</p>
                </div>
            </div>
            <div class="strength-weakness">
            <p><strong class="strength">Strength(s):</strong> {$strengthString}</p>
            <p><strong class="weakness">Weakness(es):</strong> {$weaknessString}</p>
            </div>

            <h2>Attempted Questions</h2>
            <table>
                <tr>
                    <th>Question</th>
                    <th>Topic</th>
                    <th>Correct Answer</th>
                    <th>Your Answer</th>
                    <th>Result</th>
                </tr>
HTML;

    foreach ($attemptedQuestions as $attempt) {
        $correctClass = $attempt['is_correct'] ? "correct" : "incorrect";
        echo "<tr>
                <td>{$attempt['question']}</td>
                <td>{$attempt['topic']}</td>
                <td>{$attempt['correct_answer']}</td>
                <td>{$attempt['user_answer']}</td>
                <td class='{$correctClass}'>" . ($attempt['is_correct'] ? "✔ Correct" : "✖ Incorrect") . "</td>
              </tr>";
    }

    echo <<<HTML
            </table>
            <button onclick="window.location.href='student_home_page.php'">Return to Home Page</button>
        </div>
    </body>
    </html>
    HTML;

        // Delete student and exam entry from booking table
    $conn->query("DELETE FROM booking WHERE SID = '$studentid' AND EID = $eid");

    $conn->query("DELETE FROM answers WHERE SID = '$studentid' AND EID = $eid");

    unset($_SESSION['Start_Time'][$eid], $_SESSION['questions'][$eid], $_SESSION['current_question'][$eid], $_SESSION['correct_easy_count'][$eid]);
    exit();
}


?>
