
<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Yalnızca admin rolündeki kullanıcıların bu sayfaya erişimi olmalı
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php"); // Yetkisiz kullanıcıları dashboard'a yönlendir
    exit();
}

$page_title = "Kullanıcı Yönetimi";
include 'includes/header.php';

$message = '';

// Ekleme/Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'] ?? null;

    if ($_POST['action'] == 'add') {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $role);
        } else {
            $message = "Yeni kullanıcı eklerken şifre boş bırakılamaz.";
        }
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
            $stmt->bind_param("sssi", $username, $hashed_password, $role, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
            $stmt->bind_param("ssi", $username, $role, $id);
        }
    }

    if (isset($stmt) && $stmt->execute()) {
        $message = "Kullanıcı başarıyla kaydedildi.";
    } else if (isset($stmt)) {
        $message = "Hata: " . $stmt->error;
    }
    if (isset($stmt)) $stmt->close();
}

// Silme İşlemi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($id == $_SESSION['user_id']) {
        $message = "Kendi hesabınızı silemezsiniz!";
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Kullanıcı başarıyla silindi.";
        } else {
            $message = "Hata: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Kullanıcıları Çekme
$users = [];
$result = $conn->query("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$edit_user = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_user = $result->fetch_assoc();
    $stmt->close();
}

?>

<div class="admin-container">
    <h2>Kullanıcı Yönetimi</h2>
    <p class="message-status"><?php echo $message; ?></p>

    <h3><?php echo $edit_user ? 'Kullanıcı Düzenle' : 'Yeni Kullanıcı Ekle'; ?></h3>
    <form action="users.php" method="POST">
        <input type="hidden" name="action" value="<?php echo $edit_user ? 'edit' : 'add'; ?>">
        <?php if ($edit_user): ?>
            <input type="hidden" name="id" value="<?php echo $edit_user['id']; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" id="username" name="username" value="<?php echo $edit_user ? htmlspecialchars($edit_user['username']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Şifre <?php echo $edit_user ? '(Değiştirmek istemiyorsanız boş bırakın)' : ''; ?>:</label>
            <input type="password" id="password" name="password" <?php echo $edit_user ? '' : 'required'; ?>>
        </div>
        <div class="form-group">
            <label for="role">Rol:</label>
            <select id="role" name="role" required>
                <option value="admin" <?php echo ($edit_user && $edit_user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="editor" <?php echo ($edit_user && $edit_user['role'] == 'editor') ? 'selected' : ''; ?>>Editör</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $edit_user ? 'Kullanıcıyı Güncelle' : 'Kullanıcı Ekle'; ?></button>
    </form>

    <h3 style="margin-top: 40px;">Mevcut Kullanıcılar</h3>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>Rol</th>
                    <th>Oluşturulma Tarihi</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><?php echo date('d M Y H:i', strtotime($user['created_at'])); ?></td>
                        <td class="action-buttons">
                            <a href="users.php?edit=<?php echo $user['id']; ?>" class="btn btn-sm btn-edit">Düzenle</a>
                            <?php if ($user['id'] != $_SESSION['user_id']): // Kendi hesabını silmeyi engelle ?>
                                <a href="users.php?delete=<?php echo $user['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');">Sil</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
