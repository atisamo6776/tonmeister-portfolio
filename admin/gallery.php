
<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$page_title = "Galeri Yönetimi";
include 'includes/header.php';

$message = '';

// Ekleme/Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $sort_order = $_POST['sort_order'] ?? 0;
    $image_url = $_POST['existing_image_url'] ?? null;

    // Görsel yükleme işlemi
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $image_name = basename($_FILES['image']['name']);
        $image_url = $image_name; // Veritabanına sadece dosya adını kaydet
        $target_file = $target_dir . $image_name;
        
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $message = "Galeri görseli yüklenirken hata oluştu.";
            $image_url = $_POST['existing_image_url'] ?? null; // Hata durumunda eski URL'yi koru
        }
    } else if (isset($_POST['existing_image_url'])) {
        $image_url = $_POST['existing_image_url'];
    }

    if ($_POST['action'] == 'add') {
        $stmt = $conn->prepare("INSERT INTO gallery_images (title, description, image_url, sort_order) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $description, $image_url, $sort_order);
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE gallery_images SET title = ?, description = ?, image_url = ?, sort_order = ? WHERE id = ?");
        $stmt->bind_param("sssii", $title, $description, $image_url, $sort_order, $id);
    }

    if (isset($stmt) && $stmt->execute()) {
        $message = "Görsel başarıyla kaydedildi.";
    } else if (isset($stmt)) {
        $message = "Hata: " . $stmt->error;
    }
    if (isset($stmt)) $stmt->close();
}

// Silme İşlemi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM gallery_images WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Görsel başarıyla silindi.";
    } else {
        $message = "Hata: " . $stmt->error;
    }
    $stmt->close();
}

// Galeri Görsellerini Çekme
$gallery_images = [];
$result = $conn->query("SELECT * FROM gallery_images ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $gallery_images[] = $row;
}

$edit_image = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM gallery_images WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_image = $result->fetch_assoc();
    $stmt->close();
}

?>

<div class="admin-container">
    <h2>Galeri Yönetimi</h2>
    <p class="message-status"><?php echo $message; ?></p>

    <h3><?php echo $edit_image ? 'Görsel Düzenle' : 'Yeni Görsel Ekle'; ?></h3>
    <form action="gallery.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_image ? 'edit' : 'add'; ?>">
        <?php if ($edit_image): ?>
            <input type="hidden" name="id" value="<?php echo $edit_image['id']; ?>">
            <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($edit_image['image_url']); ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="title">Başlık (Opsiyonel):</label>
            <input type="text" id="title" name="title" value="<?php echo $edit_image ? htmlspecialchars($edit_image['title']) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="description">Açıklama (Opsiyonel):</label>
            <textarea id="description" name="description" rows="3"><?php echo $edit_image ? htmlspecialchars($edit_image['description']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Görsel:</label>
            <input type="file" id="image" name="image" <?php echo $edit_image ? '' : 'required'; ?>>
            <?php if ($edit_image && $edit_image['image_url']): ?>
                <p>Mevcut Görsel: <img src="../assets/images/<?php echo htmlspecialchars($edit_image['image_url']); ?>" alt="Mevcut Görsel" width="100"></p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="sort_order">Sıralama:</label>
            <input type="number" id="sort_order" name="sort_order" value="<?php echo $edit_image ? htmlspecialchars($edit_image['sort_order']) : '0'; ?>">
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $edit_image ? 'Görseli Güncelle' : 'Görsel Ekle'; ?></button>
    </form>

    <h3 style="margin-top: 40px;">Mevcut Galeri Görselleri</h3>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Görsel</th>
                    <th>Başlık</th>
                    <th>Sıra</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gallery_images as $image): ?>
                    <tr>
                        <td><?php echo $image['id']; ?></td>
                        <td><img src="../assets/images/<?php echo htmlspecialchars($image['image_url']); ?>" alt="" width="50"></td>
                        <td><?php echo htmlspecialchars($image['title']); ?></td>
                        <td><?php echo $image['sort_order']; ?></td>
                        <td class="action-buttons">
                            <a href="gallery.php?edit=<?php echo $image['id']; ?>" class="btn btn-sm btn-edit">Düzenle</a>
                            <a href="gallery.php?delete=<?php echo $image['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bu görseli silmek istediğinizden emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
