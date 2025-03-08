<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "lab4";
$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die("Error detected: " . mysqli_error($con));
}

if(isset($_POST["signup_sid"])){
    $sid = $_POST["signup_sid"];
    $first_name = $_POST["signup_first_name"];
    $last_name = $_POST["signup_last_name"];
    $branch = $_POST["signup_branch"];
    $email = $_POST["signup_email"];
    $phone = $_POST["signup_phone"];
    
    $password = $_POST["signup_password"];
    $password2 = $_POST["signup_confirm_password"];

    if($password === $password2){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO `students`(`SID`, `First_Name`, `Last_Name`, `Branch`,  `Email`, `Phone`, `Password`) VALUES ('$sid', '$first_name', '$last_name', '$branch', '$email', '$phone', '$hashed_password')";

        if(mysqli_query($con, $query)){
            echo "Registration successful! Redirecting to login page...";
            header("refresh:2;url=login.html");
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
    else{
        echo "Passwords do not match! Please try again.";
    }
}
?>
