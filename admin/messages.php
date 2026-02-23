
<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$page_title = "Mesaj Yönetimi";
include 'includes/header.php';

$message = '';

// Mesaj Silme İşlemi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Mesaj başarıyla silindi.";
    } else {
        $message = "Hata: " . $stmt->error;
    }
    $stmt->close();
}

// Mesajı Okundu Olarak İşaretle
if (isset($_GET['mark_read'])) {
    $id = $_GET['mark_read'];
    $stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Mesaj okundu olarak işaretlendi.";
    } else {
        $message = "Hata: " . $stmt->error;
    }
    $stmt->close();
}

// Mesajları Çekme
$messages = [];
$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

?>

<div class="admin-container">
    <h2>Mesaj Yönetimi</h2>
    <p class="message-status"><?php echo $message; ?></p>

    <h3 style="margin-top: 40px;">Gelen Mesajlar</h3>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ad Soyad</th>
                    <th>E-posta</th>
                    <th>Konu</th>
                    <th>Mesaj</th>
                    <th>Tip</th>
                    <th>Okundu</th>
                    <th>Tarih</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                    <tr class="<?php echo $msg['is_read'] ? 'read-message' : 'unread-message'; ?>">
                        <td><?php echo $msg['id']; ?></td>
                        <td><?php echo htmlspecialchars($msg['name']); ?></td>
                        <td><?php echo htmlspecialchars($msg['email']); ?></td>
                        <td><?php echo htmlspecialchars($msg['subject'] ?? 'Yok'); ?></td>
                        <td><?php echo nl2br(htmlspecialchars(substr($msg['message'], 0, 100))) . (strlen($msg['message']) > 100 ? '...' : ''); ?></td>
                        <td><?php echo htmlspecialchars($msg['type']); ?></td>
                        <td><?php echo $msg['is_read'] ? 'Evet' : 'Hayır'; ?></td>
                        <td><?php echo date('d M Y H:i', strtotime($msg['created_at'])); ?></td>
                        <td class="action-buttons">
                            <?php if (!$msg['is_read']): ?>
                                <a href="messages.php?mark_read=<?php echo $msg['id']; ?>" class="btn btn-sm btn-info">Okundu</a>
                            <?php endif; ?>
                            <a href="messages.php?delete=<?php echo $msg['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bu mesajı silmek istediğinizden emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
