<?php
session_start();  // Start the session

if (!isset($_SESSION['student_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}

$SID = $_SESSION['student_id'];  // Retrieve the logged-in student ID
$conn = new mysqli("localhost", "root", "", "lab4");
$result = $conn->query("SELECT First_Name from students WHERE SID = '$SID'");
$name = $result->fetch_assoc();
echo "<h2>Welcome, " . htmlspecialchars($name['First_Name']) . "!</h2>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal - Student Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Include Font Awesome -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f7f9fc; /* Light gray background */
            padding: 20px;
        }

        header {
            background-color: #2C3E50; /* Dark Blue */
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            background-color: #e74c3c; /* Red */
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #c0392b; /* Darker Red */
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        nav {
            display: flex;
            justify-content: space-around; /* Space evenly */
            background-color: #34495e; /* Darker Blue */
            padding: 10px 0;
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
            font-weight: 600; /* Bold text */
        }

        nav a.active {
            background-color: #2980b9; /* Active Tab Color */
            transform: translateY(-2px);
        }

        nav a:hover {
            background-color: #3498db; /* Lighter Blue on Hover */
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
            background-color: #ffffff; /* White background for content */
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
            background-color: #28a745; /* Green */
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .action-btn:hover {
            background-color: #218838; /* Darker Green */
        }

        /* Enhanced Submit Button Styling */
        .btn-primary {
            background-color: #007BFF; /* Blue */
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
            background-color: #0056b3; /* Darker Blue */
            transform: translateY(-3px);
        }

        .btn-primary:active {
            background-color: #004085; /* Even Darker Blue */
            transform: translateY(0);
        }

        /* Enhanced button styling for form submission */
        form button[type="submit"] {
            background-color: #28a745; /* Green */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        form button[type="submit"]:hover {
            background-color: #218838; /* Darker Green */
            transform: translateY(-3px);
        }

        form button[type="submit"]:active {
            background-color: #1e7e34; /* Even Darker Green */
            transform: translateY(0);
        }

        .give-exam-btn {
            display: inline-block; /* Ensures the anchor behaves like a button */
            background-color: rgb(96, 20, 112); /* Purple */
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
            text-decoration: none; /* Removes underline */
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .give-exam-btn:hover {
            background-color: rgb(54, 16, 90); /* Darker Purple */
            transform: translateY(-3px);
        }

        .give-exam-btn:active {
            background-color: rgb(63, 20, 103); /* Even Darker Purple */
            transform: translateY(0);
        }

        /* Ensure the button inside behaves well */
        .give-exam-btn button {
            background: none;
            border: none;
            color: white;
            font-size: inherit;
            cursor: pointer;
        }
    </style>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f7fa; /* Light background for better contrast */
            padding: 20px;
        }

        header {
            background-color: #2C3E50; /* Dark Blue */
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            background-color: #e74c3c; /* Red */
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #c0392b; /* Darker Red */
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        nav {
            display: flex;
            justify-content: space-around; /* Space evenly */
            background-color: #34495e; /* Darker Blue */
            padding: 10px 0;
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
            font-weight: 600; /* Bold text */
        }

        nav a.active {
            background-color: #2980b9; /* Active Tab Color */
            transform: translateY(-2px);
        }

        nav a:hover {
            background-color: #3498db; /* Lighter Blue on Hover */
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
            background-color: #ffffff; /* White background for content */
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

        .give-exam-btn {
            display: inline-block; /* Ensures the anchor behaves like a button */
            background-color: rgb(96, 20, 112);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
            text-decoration: none; /* Removes underline */
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .give-exam-btn:hover {
            background-color: rgb(54, 16, 90);
            transform: translateY(-3px);
        }

        .give-exam-btn:active {
            background-color: rgb(63, 20, 103);
            transform: translateY(0);
        }

        /* Ensure the button inside behaves well */
        .give-exam-btn button {
            background: none;
            border: none;
            color: white;
            font-size: inherit;
            cursor: pointer;
        }
    </style> -->
     <!-- <style>
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

    .give-exam-btn {
    display: inline-block; /* Ensures the anchor behaves like a button */
    background-color: rgb(96, 20, 112);
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 14px;
    text-align: center;
    text-decoration: none; /* Removes underline */
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.give-exam-btn:hover {
    background-color: rgb(54, 16, 90);
    transform: translateY(-3px);
}

.give-exam-btn:active {
    background-color: rgb(63, 20, 103);
    transform: translateY(0);
}

/* Ensure the button inside behaves well */
.give-exam-btn button {
    background: none;
    border: none;
    color: white;
    font-size: inherit;
    cursor: pointer;
}
</style> -->
</head>
<body>
<div class="header">
  <a href="logout.php" class="logout-btn">Logout</a>
</div>

    <header>
        <h1>Welcome to the Exam Portal</h1>
    </header>
    <div class="container">
        <nav>
            <a href="#" class="tab-link active" data-tab="exams">Exams</a>
            <a href="#" class="tab-link" data-tab="slots">Slots</a>
            <a href="#" class="tab-link" data-tab="booking">Booking</a>
            <a href="#" class="tab-link" data-tab="reschedule">Reschedule</a>
            <a href="#" class="tab-link" data-tab="my-exams">My Exams</a>
        </nav>

        <!-- Exams Tab -->
        <section id="exams" class="tab-content active">
            <h2>Available Exams</h2>
            <div style="margin-top: 10px; display: flex; gap: 20px;">
        <div>
            <input type="text" id="search-exam-id" placeholder="Search by Exam ID">
        </div>
        <div>
            <input type="text" id="search-department" placeholder="Search by Department">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const examIdInput = document.getElementById('search-exam-id');
            const departmentInput = document.getElementById('search-department');
            const tableRows = document.querySelectorAll('#exams tbody tr');

            function filterTable() {
                const examIdValue = examIdInput.value.toLowerCase();
                const departmentValue = departmentInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const examIdCell = row.cells[0].textContent.toLowerCase();
                    const departmentCell = row.cells[1].textContent.toLowerCase();

                    if ((examIdValue === "" || examIdCell.includes(examIdValue)) &&
                        (departmentValue === "" || departmentCell.includes(departmentValue))) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            examIdInput.addEventListener('input', filterTable);
            departmentInput.addEventListener('input', filterTable);
        });
    </script>
            <table>
                <thead>
                    <tr>
                        <th>Exam ID</th>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "lab4");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $result = $conn->query("SELECT EID, Department FROM exams");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['EID']}</td><td>{$row['Department']}</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Slots Tab -->
        <section id="slots" class="tab-content">
            <h2>Available Slots</h2>
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

            <table>
                <thead>
                    <tr>
                        <th>Slot ID</th>
                        <th>Exam ID</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "lab4");
                    $result = $conn->query("SELECT Slot_ID, EID, Start_Time, End_Time FROM slots");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['Slot_ID']}</td><td>{$row['EID']}</td><td>{$row['Start_Time']}</td><td>{$row['End_Time']}</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Booking Tab -->
        <section id="booking" class="tab-content">
            <h2>Book an Exam</h2>
            <form method="post">
                <div class="form-group">
                <input type="text" name="EID" placeholder="Exam ID" required>
                </div>
                <div class="form-group">
                <input type="text" name="Slot_ID" placeholder="Slot ID" required>
                </div>
                <div class="form-group">
                <button type="submit" name="pay_fees">Pay Fees</button>
                </div>
            </form>
            <?php
            if (isset($_POST['pay_fees'])) {
                $EID = $_POST['EID'];
                $Slot_ID = $_POST['Slot_ID'];

                $conn = new mysqli("localhost", "root", "", "lab4");
                $check = $conn->query("SELECT * FROM booking WHERE EID='$EID' AND SID='$SID'");
                if($check->num_rows > 0){
                    echo "<p>Exam already registered! </p>";
                }
                else{
                $result = $conn->query("SELECT * FROM slots WHERE EID='$EID' AND Slot_ID='$Slot_ID'");
                if ($result->num_rows > 0) {
                    $conn->query("INSERT INTO booking (SID, EID, Slot_ID, Fees) VALUES ('$SID', '$EID', '$Slot_ID', 'paid')");
                    echo "<p>Booking successful! Fees paid.</p>";
                } else {
                    echo "<p>Invalid Exam ID or Slot ID.</p>";
                }
            }
                $conn->close();
            }
            ?>
        </section>

        <!-- Reschedule Tab -->
        <section id="reschedule" class="tab-content">
            <h2>Reschedule Exam</h2>
            <form method="post">
                <div class="form-group">
                <input type="text" name="EID" placeholder="Exam ID" required>
        </div>
        <div class="form-group">
                <input type="text" name="new_slotid" placeholder="New Slot ID" required>
        </div>
        <div class="form-group">
                <button type="submit" name="reschedule">Reschedule</button>
        </div>
            </form>
            <?php
            if (isset($_POST['reschedule'])) {
                $EID = $_POST['EID'];
                $new_slotid = $_POST['new_slotid'];

                $conn = new mysqli("localhost", "root", "", "lab4");
                $result = $conn->query("SELECT * FROM slots WHERE EID='$EID' AND Slot_ID='$new_slotid'");
                if ($result->num_rows > 0) {
                    $conn->query("UPDATE booking SET Slot_ID='$new_slotid' WHERE EID='$EID' AND SID='$SID'");
                    echo "<p>Reschedule successful!</p>";
                } else {
                    echo "<p>Invalid Exam ID or New Slot ID.</p>";
                }
                $conn->close();
            }
            ?>
        </section>

        <!-- My Exams Tab -->
        <section id="my-exams" class="tab-content">
            <h2>My Exams</h2>
            <table>
                <thead>
                    <tr>
                        <th>Exam ID</th>
                        <th>Slot ID</th>
                        <th>Fees</th>
                        <th>Start Time</th>
                        <th>End Time</Time><Time></Time>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
$conn = new mysqli("localhost", "root", "", "lab4");
$result = $conn->query("SELECT b.EID, b.Slot_ID, b.Fees, s.Start_Time, s.End_Time 
                         FROM booking b, slots s 
                         WHERE b.EID = s.EID AND b.Slot_ID = s.Slot_ID AND b.SID = '$SID'");
while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['EID']}</td>
        <td>{$row['Slot_ID']}</td>
        <td>{$row['Fees']}</td>
        <td>{$row['Start_Time']}</td>
        <td>{$row['End_Time']}</td>
        <td>
            <a href='photo.php?EID={$row['EID']}&SID={$SID}' 
                class='give-exam-btn'
                data-slot-start='{$row['Start_Time']}' 
                data-slot-end='{$row['End_Time']}'>
                <button type='button'>Give Exam</button>
            </a>
        </td>
      </tr>";
}
$conn->close();
?>

                </tbody>
            </table>
        </section>

    </div>

    <script>
// Function to convert string to Date object
// Function to convert string to Date object
// Function to convert string to Date object
function parseTime(timeString) {
    const [hours, minutes, seconds] = timeString.split(":").map(Number);
    const date = new Date();
    date.setHours(hours, minutes, seconds || 0, 0);
    return date;
}

document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.give-exam-btn');

    buttons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent immediate navigation

            const slotStart = parseTime(this.dataset.slotStart);
            const slotEnd = parseTime(this.dataset.slotEnd);
            const currentTime = new Date();

            if (currentTime >= slotStart && currentTime <= slotEnd) {
                // Redirect only if within the time range
                window.location.href = this.getAttribute('href');
            } else {
                alert('The current time does not fall within the exam slot. Please try during the scheduled time.');
            }
        });
    });
});


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
</body>
</html>
