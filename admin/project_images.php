
<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$project_id = $_GET['project_id'] ?? null;
if (!$project_id) {
    header("Location: projects.php");
    exit();
}

$stmt_project = $conn->prepare("SELECT title FROM projects WHERE id = ?");
$stmt_project->bind_param("i", $project_id);
$stmt_project->execute();
$project_title_result = $stmt_project->get_result();
$project_title = $project_title_result->fetch_assoc()['title'] ?? 'Bilinmeyen Proje';
$stmt_project->close();

$page_title = "\'" . htmlspecialchars($project_title) . "\' Proje Görsel Yönetimi";
include 'includes/header.php';

$message = '';

// Görsel Ekleme İşlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_image') {
    $caption = $_POST['caption'] ?? null;
    $sort_order = $_POST['sort_order'] ?? 0;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $image_name = basename($_FILES['image']['name']);
        $image_url = $image_name;
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO project_images (project_id, image_url, caption, sort_order) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $project_id, $image_url, $caption, $sort_order);
            if ($stmt->execute()) {
                $message = "Görsel başarıyla eklendi.";
            } else {
                $message = "Hata: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Görsel yüklenirken hata oluştu.";
        }
    } else {
        $message = "Lütfen bir görsel seçin.";
    }
}

// Görsel Silme İşlemi
if (isset($_GET['delete_image'])) {
    $image_id = $_GET['delete_image'];
    $stmt = $conn->prepare("DELETE FROM project_images WHERE id = ? AND project_id = ?");
    $stmt->bind_param("ii", $image_id, $project_id);
    if ($stmt->execute()) {
        $message = "Görsel başarıyla silindi.";
    } else {
        $message = "Hata: " . $stmt->error;
    }
    $stmt->close();
}

// Proje Görsellerini Çekme
$project_images = [];
$result = $conn->query("SELECT * FROM project_images WHERE project_id = ".$project_id." ORDER BY sort_order ASC");
while ($row = $result->fetch_assoc()) {
    $project_images[] = $row;
}

?>

<div class="admin-container">
    <h2>\'<?php echo htmlspecialchars($project_title); ?>\' Projesi Görsel Yönetimi</h2>
    <p class="message-status"><?php echo $message; ?></p>

    <h3>Yeni Görsel Ekle</h3>
    <form action="project_images.php?project_id=<?php echo $project_id; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_image">
        
        <div class="form-group">
            <label for="image">Görsel:</label>
            <input type="file" id="image" name="image" required>
        </div>
        <div class="form-group">
            <label for="caption">Açıklama (Opsiyonel):</label>
            <input type="text" id="caption" name="caption">
        </div>
        <div class="form-group">
            <label for="sort_order">Sıralama:</label>
            <input type="number" id="sort_order" name="sort_order" value="0">
        </div>
        <button type="submit" class="btn btn-primary">Görsel Ekle</button>
    </form>

    <h3 style="margin-top: 40px;">Mevcut Proje Görselleri</h3>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Görsel</th>
                    <th>Açıklama</th>
                    <th>Sıra</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($project_images) > 0): ?>
                    <?php foreach ($project_images as $img): ?>
                        <tr>
                            <td><?php echo $img['id']; ?></td>
                            <td><img src="../assets/images/<?php echo htmlspecialchars($img['image_url']); ?>" alt="" width="100"></td>
                            <td><?php echo htmlspecialchars($img['caption']); ?></td>
                            <td><?php echo $img['sort_order']; ?></td>
                            <td class="action-buttons">
                                <a href="project_images.php?project_id=<?php echo $project_id; ?>&delete_image=<?php echo $img['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bu görseli silmek istediğinizden emin misiniz?');">Sil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Bu projeye henüz görsel eklenmemiştir.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p style="margin-top: 20px;"><a href="projects.php" class="btn btn-secondary">Projelere Geri Dön</a></p>

</div>

<?php include 'includes/footer.php'; ?>
