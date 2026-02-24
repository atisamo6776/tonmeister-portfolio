
<?php
include 'includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? null;
    $message_content = $_POST['message'] ?? '';
    $type = 'contact';

    if (empty($name) || empty($email) || empty($message_content)) {
        header("Location: index.php?status=error&msg=missing_fields");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO messages (name, email, phone, message, type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $message_content, $type);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=contact_sent");
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
