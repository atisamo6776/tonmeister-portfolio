
<?php
include 'includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $project_details = $_POST['project_details'] ?? '';
    $type = 'quote';

    if (empty($name) || empty($email) || empty($phone) || empty($project_details)) {
        header("Location: index.php?status=error&msg=missing_fields");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO messages (name, email, phone, message, type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $project_details, $type);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=quote_sent");
    } else {
        header("Location: index.php?status=error&msg=send_failed");
    }
    $stmt->close();
} else {
    header("Location: index.php");
}

$conn->close();
exit();
?>
