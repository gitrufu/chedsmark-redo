<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Database connection
    $conn = new mysqli("localhost", "root", "chiko.dev", "chedsmark");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize Inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate inputs
    if (!preg_match('/^[a-zA-Z0-9\s]{0,100}$/', $message)) {
        die("Error: Additional message must be alphanumeric and under 100 characters.");
    }

    // Prevent SQL Injection using prepared statements
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute and redirect
    if ($stmt->execute()) {
        // Pass a success message to JavaScript for the modal
        echo "<script>
            localStorage.setItem('thankYouModal', 'true');
            window.location.href = 'index.html';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
