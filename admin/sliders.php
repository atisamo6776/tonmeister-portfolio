
<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$page_title = "Slider Yönetimi";
include 'includes/header.php';

$message = '';

// Ekleme/Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $title = $_POST['title'] ?? null;
    $subtitle = $_POST['subtitle'] ?? null;
    $cta_text = $_POST['cta_text'] ?? null;
    $cta_link = $_POST['cta_link'] ?? null;
    $sort_order = $_POST['sort_order'] ?? 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $image_url = $_POST['existing_image_url'] ?? null;

    // Görsel yükleme işlemi
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $image_name = basename($_FILES['image']['name']);
        $image_url = $image_name;
        $target_file = $target_dir . $image_name;
        
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $message = "Slider görseli yüklenirken hata oluştu.";
            $image_url = $_POST['existing_image_url'] ?? null;
        }
    } else if (isset($_POST['existing_image_url'])) {
        $image_url = $_POST['existing_image_url'];
    }

    if ($_POST['action'] == 'add') {
        $stmt = $conn->prepare("INSERT INTO sliders (title, subtitle, image_url, cta_text, cta_link, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssii", $title, $subtitle, $image_url, $cta_text, $cta_link, $sort_order, $is_active);
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE sliders SET title = ?, subtitle = ?, image_url = ?, cta_text = ?, cta_link = ?, sort_order = ?, is_active = ? WHERE id = ?");
        $stmt->bind_param("sssssiii", $title, $subtitle, $image_url, $cta_text, $cta_link, $sort_order, $is_active, $id);
    }

    if (isset($stmt) && $stmt->execute()) {
        $message = "Slider başarıyla kaydedildi.";
    } else if (isset($stmt)) {
        $message = "Hata: " . $stmt->error;
    }
    if (isset($stmt)) $stmt->close();
}

// Silme İşlemi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM sliders WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Slider başarıyla silindi.";
    } else {
        $message = "Hata: " . $stmt->error;
    }
    $stmt->close();
}

// Sliderları Çekme
$sliders = [];
$result = $conn->query("SELECT * FROM sliders ORDER BY sort_order ASC");
while ($row = $result->fetch_assoc()) {
    $sliders[] = $row;
}

$edit_slider = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM sliders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_slider = $result->fetch_assoc();
    $stmt->close();
}

?>

<div class="admin-container">
    <h2>Slider Yönetimi</h2>
    <p class="message-status"><?php echo $message; ?></p>

    <h3><?php echo $edit_slider ? 'Slider Düzenle' : 'Yeni Slider Ekle'; ?></h3>
    <form action="sliders.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_slider ? 'edit' : 'add'; ?>">
        <?php if ($edit_slider): ?>
            <input type="hidden" name="id" value="<?php echo $edit_slider['id']; ?>">
            <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($edit_slider['image_url']); ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="title">Başlık (Opsiyonel):</label>
            <input type="text" id="title" name="title" value="<?php echo $edit_slider ? htmlspecialchars($edit_slider['title']) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="subtitle">Alt Başlık (Opsiyonel):</label>
            <input type="text" id="subtitle" name="subtitle" value="<?php echo $edit_slider ? htmlspecialchars($edit_slider['subtitle']) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="cta_text">CTA Metni (Opsiyonel):</label>
            <input type="text" id="cta_text" name="cta_text" value="<?php echo $edit_slider ? htmlspecialchars($edit_slider['cta_text']) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="cta_link">CTA Linki (Opsiyonel):</label>
            <input type="text" id="cta_link" name="cta_link" value="<?php echo $edit_slider ? htmlspecialchars($edit_slider['cta_link']) : ''; ?>" placeholder="index.php veya #iletisim">
        </div>
        <div class="form-group">
            <label for="image">Görsel:</label>
            <input type="file" id="image" name="image" <?php echo $edit_slider ? '' : 'required'; ?>>
            <?php if ($edit_slider && $edit_slider['image_url']): ?>
                <p>Mevcut Görsel: <img src="../assets/images/<?php echo htmlspecialchars($edit_slider['image_url']); ?>" alt="Mevcut Görsel" width="100"></p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="sort_order">Sıralama:</label>
            <input type="number" id="sort_order" name="sort_order" value="<?php echo $edit_slider ? htmlspecialchars($edit_slider['sort_order']) : '0'; ?>">
        </div>
        <div class="form-group">
            <input type="checkbox" id="is_active" name="is_active" <?php echo ($edit_slider && $edit_slider['is_active']) ? 'checked' : ''; ?>>
            <label for="is_active">Aktif</label>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $edit_slider ? 'Slider Güncelle' : 'Slider Ekle'; ?></button>
    </form>

    <h3 style="margin-top: 40px;">Mevcut Slider Öğeleri</h3>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Görsel</th>
                    <th>Başlık</th>
                    <th>Aktif</th>
                    <th>Sıra</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sliders as $slider): ?>
                    <tr>
                        <td><?php echo $slider['id']; ?></td>
                        <td><img src="../assets/images/<?php echo htmlspecialchars($slider['image_url']); ?>" alt="" width="50"></td>
                        <td><?php echo htmlspecialchars($slider['title']); ?></td>
                        <td><?php echo $slider['is_active'] ? 'Evet' : 'Hayır'; ?></td>
                        <td><?php echo $slider['sort_order']; ?></td>
                        <td class="action-buttons">
                            <a href="sliders.php?edit=<?php echo $slider['id']; ?>" class="btn btn-sm btn-edit">Düzenle</a>
                            <a href="sliders.php?delete=<?php echo $slider['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bu slider öğesini silmek istediğinizden emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
