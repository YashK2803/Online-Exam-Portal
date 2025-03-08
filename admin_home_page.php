<?php
session_start();  // Start the session

if (!isset($_SESSION['student_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background-color: #f3f4f6;
        padding: 20px;
    }

    

    header {
        background-color: #2C3E50;
        color: white;
        padding: 15px;
        text-align: center;
        border-radius: 8px;
    }

    .header {
    position: relative;
    width: 100%;
    height: 50px;
  }
  
  .logout-btn {
    position: absolute;
    top: -35px;
    right: 10px;
    background-color: #ff0000;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
  }
  
  .logout-btn:hover {
    background-color: #cc0000;
  }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    nav {
        display: flex;
        justify-content: space-between;
        background-color: #2C3E50; /* Dark Gray */
        padding: 10px 15px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    nav a {
        text-decoration: none;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    }

    nav a.active {
        /* background-color: #4682B4; Rich Emerald */
        color: white;
        transform: translateY(-2px);
    }

    nav a:hover {
        background-color: #4682B4; /* Steel Blue */
        color: white;
    }

    h2 {
        margin-top: 10px;
        color: #333;
    }

    .search-bar {
        margin: 15px 0;
        display: flex;
        gap: 10px;
    }

    .search-bar input[type="search"] {
        width: 50%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
    }

    .tab-content {
        display: none;
        background-color: #F8F9FA; /* Light Gray */
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        opacity: 0;
        transform: translateY(10px);
        animation: fadeIn 0.5s forwards;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    table {
        width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    background-color: #ffffff;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

th {
    background-color: #34495E; /* Deep Charcoal */
    color: white;
    font-weight: bold;
}

tr:nth-child(even) {
    background-color: #F2F3F4; /* Soft Gray */
}

tr:hover {
    background-color: #E5E8E8; /* Muted Cool Gray */
}

.action-btn {
    background-color: #28a745;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.action-btn:hover {
    background-color: #218838;
}

    /* Enhanced Submit Button Styling */
    .btn-primary {
        background-color: #007BFF;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }


    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-3px);
    }

    .btn-primary:active {
        background-color: #004085;
        transform: translateY(0);
    }

    /* Booking Form Enhancements */
    #booking-form {
        margin-bottom: 20px;
    }

    #booking-form label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    #booking-form select,
    #booking-form button {
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ddd;
        margin-bottom: 10px;
    }

    #available-slots {
        margin-top: 20px;
    }

    /* Enhanced button styling for form submission */
    form button[type="submit"] {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    form button[type="submit"]:hover {
        background-color: #218838;
        transform: translateY(-3px);
    }

    form button[type="submit"]:active {
        background-color: #1e7e34;
        transform: translateY(0);
    }
</style>
</head>
<body>
<div class="header">
  <a href="logout.php" class="logout-btn">Logout</a>
</div>
    <header>
        <h1>Admin Dashboard - Exam Portal</h1>
    </header>
    <div class="container">
        <nav>
            <a href="#" class="tab-link active" data-tab="exams">Exams</a>
            <a href="#" class="tab-link" data-tab="create-exam">Create Exam</a>
            <a href="#" class="tab-link" data-tab="create-question">Create Question</a>
            <a href="#" class="tab-link" data-tab="assign-exam">Assign Exam</a>
            <a href="#" class="tab-link" data-tab="bookings">Bookings</a>
            <a href="#" class="tab-link" data-tab="results">Results</a>
        </nav>

        <!-- Exams Tab -->
        <section id="exams" class="tab-content active">
            <h2>Exam Slots</h2>
            <div style="margin-top: 10px; display: flex; gap: 20px;">
        <div>
            <input type="text" id="search-slot-id" placeholder="Search by Slot ID">
        </div>
        <div>
            <input type="text" id="search-exam-id-slot" placeholder="Search By Exam ID">
        </div>
        <div>
            <input type="text" id="search-start-time" placeholder="Search by Start Time">
        </div>
        <div>
            <input type="text" id="search-end-time" placeholder="Search By End Time">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slotIdInput = document.getElementById('search-slot-id');
            const examIdInput = document.getElementById('search-exam-id-slot');
            const startTimeInput = document.getElementById('search-start-time');
            const endTimeInput = document.getElementById('search-end-time');
            const tableRows = document.querySelectorAll('#slots tbody tr');

            function filterTable() {
                const slotIdValue = slotIdInput.value.toLowerCase();
                const examIdValue = examIdInput.value.toLowerCase();
                const startTimeValue = startTimeInput.value.toLowerCase();
                const endTimeValue = endTimeInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const slotIdCell = row.cells[0].textContent.toLowerCase();
                    const examIdCell = row.cells[1].textContent.toLowerCase();
                    const startTimeCell = row.cells[2].textContent.toLowerCase();
                    const endTimeCell = row.cells[3].textContent.toLowerCase();

                    if ((slotIdValue === "" || slotIdCell.includes(slotIdValue)) &&
                        (examIdValue === "" || examIdCell.includes(examIdValue)) &&
                        (startTimeValue === "" || startTimeCell.includes(startTimeValue)) &&
                        (endTimeValue === "" || endTimeCell.includes(endTimeValue))) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            slotIdInput.addEventListener('input', filterTable);
            examIdInput.addEventListener('input', filterTable);
            startTimeInput.addEventListener('input', filterTable);
            endTimeInput.addEventListener('input', filterTable);
        });
    </script>
            <table id="slots">
                <thead>
                    <tr>
                        <th>Slot ID</th>
                        <th>Exam ID</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "lab4");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_slot"])) {
                        $slot_id = $_POST["slot_id"];
                        $conn->query("DELETE FROM slots WHERE Slot_ID='$slot_id'");
                    }

                    $result = $conn->query("SELECT * FROM slots");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['Slot_ID']}</td>
                                    <td>{$row['EID']}</td>
                                    <td>{$row['Start_Time']}</td>
                                    <td>{$row['End_Time']}</td>
                                    <td>
                                        <form method='POST'>
                                            <input type='hidden' name='slot_id' value='{$row['Slot_ID']}'>
                                            <button type='submit' name='delete_slot'>Cancel</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No Slots Available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Create Exam Tab -->
        <section id="create-exam" class="tab-content">
            <h2>Create a New Exam</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Exam ID:</label>
                    <input type="text" name="eid" required>
                </div>
                <div class="form-group">
                    <label>Department:</label>
                    <input type="text" name="department" required>
                </div>
                <button type="submit" name="create_exam" class="submit-btn">Create Exam</button>
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_exam"])) {
                $eid = $_POST["eid"];
                $department = $_POST["department"];
                $check = $conn->query("SELECT * FROM exams WHERE EID='$eid'");
                if ($check->num_rows == 0) {
                    $conn->query("INSERT INTO exams (EID, Department) VALUES ('$eid', '$department')");
                    echo "<p>Exam created successfully!</p>";
                } else {
                    echo "<p>Exam already exists.</p>";
                }
            }
            ?>
        </section>

                <!-- Create Question Tab -->
                <section id="create-question" class="tab-content">
            <h2>Create a New Question</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Question ID:</label>
                    <input type="text" name="qid" required>
                </div>
                <div class="form-group">
                    <label>Question:</label>
                    <input type="text" name="question" required>
                </div>
                <div class="form-group">
                    <label>Answer:</label>
                    <input type="text" name="answer" required>
                </div>
                <div class="form-group">
                    <label>Exam ID:</label>
                    <input type="text" name="eid" required>
                </div>
                <div class="form-group">
                    <label>Topic:</label>
                    <input type="text" name="topic" required>
                </div>
                <div class="form-group">
                    <label>Difficulty:</label>
                    <input type="text" name="difficulty" required>
                </div>
                <button type="submit" name="create_question" class="submit-btn">Create Question</button>
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_question"])) {
                $qid = $_POST["qid"];
                $question = $_POST["question"];
                $answer = $_POST["answer"];
                $eid = $_POST["eid"];
                $topic = $_POST["topic"];
                $difficulty = $_POST["difficulty"];
                $check = $conn->query("SELECT * FROM questions WHERE QID='$qid'");
                if ($check->num_rows == 0) {
                    $conn->query("INSERT INTO questions (QID, Question, Answer, EID, Topic, Difficulty) VALUES ('$qid', '$question','$answer','$eid','$topic','$difficulty')");
                    echo "<p>Question created successfully!</p>";
                } else {
                    echo "<p>Question ID already exists.</p>";
                }
            }
            ?>
        </section>

        <!-- Assign Exam Tab -->
        <section id="assign-exam" class="tab-content">
    <h2>Assign Exam Slot</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label>Slot ID:</label>
            <input type="text" name="slotid" required>
        </div>
        <div class="form-group">
            <label>Exam ID:</label>
            <input type="text" name="eid" required>
        </div>
        <div class="form-group">
            <label>Start Time (HH:MM:SS):</label>
            <input type="time" step="1" name="starttime" required>
        </div>
        <div class="form-group">
            <label>End Time (HH:MM:SS):</label>
            <input type="time" step="1" name="endtime" required>
        </div>
        <button type="submit" name="assign_exam" class="submit-btn">Assign Slot</button>
    </form>

    <?php
    if (isset($_POST['assign_exam'])) {
        $slotid = $_POST['slotid'];
        $eid = $_POST['eid'];
        $starttime = $_POST['starttime'] . ":00";  // Ensure seconds are included
        $endtime = $_POST['endtime'] . ":00";      // Add ":00" for full HH:MM:SS format

        // Check if the entry already exists
        $checkQuery = "SELECT * FROM slots WHERE Slot_ID = '$slotid' AND EID = '$eid'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            echo "<p style='color: red;'>This slot already exists for the given Exam ID.</p>";
        } else {
            // Insert the new slot into the slots table
            $insertQuery = "INSERT INTO slots (Slot_ID, EID, Start_Time, End_Time) VALUES ('$slotid', '$eid', '$starttime', '$endtime')";
            if ($conn->query($insertQuery) === TRUE) {
                echo "<p style='color: green;'>Exam slot assigned successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
            }
        }
    }
    ?>
</section>




        <!-- Bookings Tab -->
        <section id="bookings" class="tab-content">
    <h2>Bookings</h2>
    <table id="booking">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Exam ID</th>
                <th>Slot ID</th>
                <th>Fee Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM booking");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['SID']}</td>
                            <td>{$row['EID']}</td>
                            <td>{$row['Slot_ID']}</td>
                            <td>{$row['Fees']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No bookings found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>


        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const tabs = document.querySelectorAll(".tab-link");
                const contents = document.querySelectorAll(".tab-content");

                tabs.forEach(tab => {
                    tab.addEventListener("click", function (event) {
                        event.preventDefault();
                        tabs.forEach(t => t.classList.remove("active"));
                        contents.forEach(c => c.classList.remove("active"));
                        this.classList.add("active");
                        document.getElementById(this.getAttribute("data-tab")).classList.add("active");
                    });
                });
            });
        </script>
    </div>

        <!-- Results Tab -->
        <section id="results" class="tab-content">
            <h2>Results</h2>
            <div style="margin-top: 10px; display: flex; gap: 20px;">
        <div>
            <input type="text" id="search-student-id" placeholder="Search by Student ID">
        </div>
        <div>
            <input type="text" id="search-exam-id-slot" placeholder="Search By Exam ID">
        </div>
        <div>
            <input type="text" id="search-score" placeholder="Search by Score">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slotIdInput = document.getElementById('search-student-id');
            const examIdInput = document.getElementById('search-exam-id-slot');
            const startTimeInput = document.getElementById('search-score');
            const tableRows = document.querySelectorAll('#results tbody tr');

            function filterTable() {
                const slotIdValue = slotIdInput.value.toLowerCase();
                const examIdValue = examIdInput.value.toLowerCase();
                const startTimeValue = startTimeInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const slotIdCell = row.cells[0].textContent.toLowerCase();
                    const examIdCell = row.cells[1].textContent.toLowerCase();
                    const startTimeCell = row.cells[2].textContent.toLowerCase();

                    if ((slotIdValue === "" || slotIdCell.includes(slotIdValue)) &&
                        (examIdValue === "" || examIdCell.includes(examIdValue)) &&
                        (startTimeValue === "" || startTimeCell.includes(startTimeValue))) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            slotIdInput.addEventListener('input', filterTable);
            examIdInput.addEventListener('input', filterTable);
            startTimeInput.addEventListener('input', filterTable);
        });
    </script>
            <table id="results">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Exam ID</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "lab4");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_slot"])) {
                    //     $slot_id = $_POST["slot_id"];
                    //     $conn->query("DELETE FROM slots WHERE Slot_ID='$slot_id'");
                    // }

                    $result = $conn->query("SELECT * FROM results");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['SID']}</td>
                                    <td>{$row['EID']}</td>
                                    <td>{$row['Score']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No Results Available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

</body>
</html>
