
<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$page_title = "Hizmet Yönetimi";
include 'includes/header.php';

$message = '';

// Ekleme/Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $title = $_POST['title'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $description = $_POST['description'];
    $icon_class = $_POST['icon_class'] ?? null;
    $sort_order = $_POST['sort_order'] ?? 0;
    $image_url = $_POST['image_url'] ?? null; // Eğer direkt URL giriliyorsa

    // Görsel yükleme işlemi (Eğer dosya yükleniyorsa)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $image_name = basename($_FILES['image']['name']);
        $image_url = $image_name; // Veritabanına sadece dosya adını kaydet
        $target_file = $target_dir . $image_name;
        
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $message = "Hizmet görseli yüklenirken hata oluştu.";
            $image_url = $_POST['existing_image_url'] ?? null; // Hata durumunda eski URL'yi koru
        }
    } else if (isset($_POST['existing_image_url'])) {
        $image_url = $_POST['existing_image_url'];
    }

    if ($_POST['action'] == 'add') {
        $stmt = $conn->prepare("INSERT INTO services (title, slug, description, icon_class, image_url, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $title, $slug, $description, $icon_class, $image_url, $sort_order);
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE services SET title = ?, slug = ?, description = ?, icon_class = ?, image_url = ?, sort_order = ? WHERE id = ?");
        $stmt->bind_param("sssssii", $title, $slug, $description, $icon_class, $image_url, $sort_order, $id);
    }

    if (isset($stmt) && $stmt->execute()) {
        $message = "Hizmet başarıyla kaydedildi.";
    } else if (isset($stmt)) {
        $message = "Hata: " . $stmt->error;
    }
    if (isset($stmt)) $stmt->close();
}

// Silme İşlemi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Hizmet başarıyla silindi.";
    } else {
        $message = "Hata: " . $stmt->error;
    }
    $stmt->close();
}

// Hizmetleri Çekme
$services = [];
$result = $conn->query("SELECT * FROM services ORDER BY sort_order ASC");
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}

$edit_service = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_service = $result->fetch_assoc();
    $stmt->close();
}

?>

<div class="admin-container">
    <h2>Hizmet Yönetimi</h2>
    <p class="message-status"><?php echo $message; ?></p>

    <h3><?php echo $edit_service ? 'Hizmet Düzenle' : 'Yeni Hizmet Ekle'; ?></h3>
    <form action="services.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_service ? 'edit' : 'add'; ?>">
        <?php if ($edit_service): ?>
            <input type="hidden" name="id" value="<?php echo $edit_service['id']; ?>">
            <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($edit_service['image_url']); ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="title">Başlık:</label>
            <input type="text" id="title" name="title" value="<?php echo $edit_service ? htmlspecialchars($edit_service['title']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Açıklama:</label>
            <textarea id="description" name="description" rows="5" required><?php echo $edit_service ? htmlspecialchars($edit_service['description']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="icon_class">İkon Sınıfı (Font Awesome):</label>
            <input type="text" id="icon_class" name="icon_class" value="<?php echo $edit_service ? htmlspecialchars($edit_service['icon_class']) : ''; ?>" placeholder="fas fa-mosque">
        </div>
        <div class="form-group">
            <label for="image">Görsel:</label>
            <input type="file" id="image" name="image">
            <?php if ($edit_service && $edit_service['image_url']): ?>
                <p>Mevcut Görsel: <img src="../assets/images/<?php echo htmlspecialchars($edit_service['image_url']); ?>" alt="Mevcut Görsel" width="100"></p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="sort_order">Sıralama:</label>
            <input type="number" id="sort_order" name="sort_order" value="<?php echo $edit_service ? htmlspecialchars($edit_service['sort_order']) : '0'; ?>">
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $edit_service ? 'Hizmeti Güncelle' : 'Hizmet Ekle'; ?></button>
    </form>

    <h3 style="margin-top: 40px;">Mevcut Hizmetler</h3>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>İkon</th>
                    <th>Görsel</th>
                    <th>Sıra</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?php echo $service['id']; ?></td>
                        <td><?php echo htmlspecialchars($service['title']); ?></td>
                        <td><i class="<?php echo htmlspecialchars($service['icon_class']); ?>"></i></td>
                        <td>
                            <?php if ($service['image_url']): ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($service['image_url']); ?>" alt="" width="50">
                            <?php else: ?>
                                Yok
                            <?php endif; ?>
                        </td>
                        <td><?php echo $service['sort_order']; ?></td>
                        <td class="action-buttons">
                            <a href="services.php?edit=<?php echo $service['id']; ?>" class="btn btn-sm btn-edit">Düzenle</a>
                            <a href="services.php?delete=<?php echo $service['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bu hizmeti silmek istediğinizden emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
