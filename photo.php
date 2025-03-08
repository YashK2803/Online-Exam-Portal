<?php
session_start();
$EID = $_GET['EID'] ?? null;
$SID = $_GET['SID'] ?? null;

if (!$EID || !$SID) {
    die("Invalid access!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Verification</title>
    <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(135deg, #007bff, #6610f2); /* Gradient background */
    color: #fff;
}

.container {
    background: #ffffff; /* White background for the container */
    color: #333; /* Dark text color */
    width: 400px; /* Fixed width for the container */
    padding: 30px; /* Padding for the container */
    border-radius: 12px; /* Rounded corners */
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2); /* Shadow for depth */
    text-align: center; /* Centered text */
    animation: fadeIn 0.5s ease-in-out; /* Fade-in animation */
}

.container h2 {
    margin-bottom: 15px; /* Space below the heading */
    font-size: 24px; /* Font size for the heading */
    color: #007bff; /* Primary color for the heading */
}

.photo-container {
    display: flex; /* Flexbox for centering */
    justify-content: center; /* Center the image */
    margin: 20px 0; /* Margin around the photo container */
}

.photo-container img {
    width: 100%; /* Full width */
    max-width: 250px; /* Max width for the image */
    border-radius: 10px; /* Rounded corners for the image */
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* Shadow for the image */
}

.exam-btn {
    display: inline-block; /* Inline block for button */
    background: #007bff; /* Primary button color */
    color: white; /* Text color */
    border: none; /* No border */
    padding: 12px 20px; /* Padding for the button */
    font-size: 18px; /* Font size for the button */
    font-weight: bold; /* Bold text */
    border-radius: 6px; /* Rounded corners for the button */
    cursor: pointer; /* Pointer cursor on hover */
    text-decoration: none; /* No underline */
    transition: 0.3s ease-in-out; /* Transition for hover effect */
    margin-top: 15px; /* Space above the button */
}

.exam-btn:hover {
    background: #0056b3; /* Darker blue on hover */
    transform: translateY(-2px); /* Lift effect on hover */
}

@keyframes fadeIn {
    from {
        opacity: 0; /* Start transparent */
        transform: translateY(-10px); /* Start above */
    }
    to {
        opacity: 1; /* Fully visible */
        transform: translateY(0); /* End at original position */
    }
}
</style>
</head>
<body>

    <div class="container">
        <h2>Photo Verification</h2>
        <p>Please verify your identity before proceeding to the exam.</p>

        <div class="photo-container">
            <img src="photo.png" alt="Photo Verification">
        </div>

        <a href="exam.php?EID=<?php echo $EID; ?>&SID=<?php echo $SID; ?>" class="exam-btn">
            Proceed to Exam
        </a>
    </div>

</body>
</html>
