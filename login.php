<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "lab4";
$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST['login_sid']) && isset($_POST['login_password'])){
    $sid = $_POST['login_sid'];
    $password = $_POST['login_password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM students WHERE SID = ?");
    $stmt->bind_param("s", $sid);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        
        // Verify the hashed password
        if(password_verify($password, $row['Password'])){
            $_SESSION['student_id'] = $sid;
            // Check if SID is 0 for admin
            if($sid == 0){ // SID = 0 for admin
                header("Location: admin_home_page.php");
                exit();
            }
            
            // Redirect to student home page for valid student SID
            header("Location: student_home_page.php");
            exit();
        } else {
            echo "Incorrect password! Please try again.";
        }
    } else {
        echo "Student is not registered!";
    }

    // Close the prepared statement
    $stmt->close();
}
?>
