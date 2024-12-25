<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    $conn = new mysqli("localhost", "chedsmar_Cheds", "@Chedsmark24", "chedsmar_chedsmark.co.zw");

    if ($conn->connect_error) {
        $errors[] = "Database connection failed.";
    } else {
        // Sanitize and validate inputs
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $message = htmlspecialchars(trim($_POST['message']));

        if (!preg_match('/^\d{10}$/', $phone)) {
            $errors[] = "Phone number must be exactly 10 digits.";
        }

        if (!preg_match('/^[a-zA-Z0-9\s]{0,100}$/', $message)) {
            $errors[] = "Message must be alphanumeric and under 100 characters.";
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $message);

            if ($stmt->execute()) {
                echo "<script>
                    localStorage.setItem('thankYouModal', 'true');
                    window.location.href = 'index.html';
                </script>";
            } else {
                $errors[] = "Failed to submit your request. Please try again.";
            }

            $stmt->close();
        }

        $conn->close();
    }

    // If errors exist, pass them to the modal
    if (!empty($errors)) {
        echo "<script>
            localStorage.setItem('errorModal', '" . implode("<br>", $errors) . "');
            window.location.href = 'index.html';
        </script>";
    }
}
?>
