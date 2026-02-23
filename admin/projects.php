
<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$page_title = "Proje Yönetimi";
include 'includes/header.php';

$message = '';

// Ekleme/Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $title = $_POST['title'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $category = $_POST['category'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $equipment_used = $_POST['equipment_used'] ?? null;
    
    $main_image = $_POST['existing_main_image'] ?? null;
    $before_image = $_POST['existing_before_image'] ?? null;
    $after_image = $_POST['existing_after_image'] ?? null;

    // Ana görsel yükleme
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $image_name = basename($_FILES['main_image']['name']);
        $main_image = $image_name;
        if (!move_uploaded_file($_FILES['main_image']['tmp_name'], $target_dir . $image_name)) {
            $message .= "Ana görsel yüklenirken hata oluştu. ";
            $main_image = $_POST['existing_main_image'] ?? null;
        }
    }

    // Öncesi görsel yükleme
    if (isset($_FILES['before_image']) && $_FILES['before_image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $image_name = basename($_FILES['before_image']['name']);
        $before_image = $image_name;
        if (!move_uploaded_file($_FILES['before_image']['tmp_name'], $target_dir . $image_name)) {
            $message .= "Öncesi görseli yüklenirken hata oluştu. ";
            $before_image = $_POST['existing_before_image'] ?? null;
        }
    }

    // Sonrası görsel yükleme
    if (isset($_FILES['after_image']) && $_FILES['after_image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $image_name = basename($_FILES['after_image']['name']);
        $after_image = $image_name;
        if (!move_uploaded_file($_FILES['after_image']['tmp_name'], $target_dir . $image_name)) {
            $message .= "Sonrası görseli yüklenirken hata oluştu. ";
            $after_image = $_POST['existing_after_image'] ?? null;
        }
    }

    if ($_POST['action'] == 'add') {
        $stmt = $conn->prepare("INSERT INTO projects (title, slug, category, location, description, equipment_used, main_image, before_image, after_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $title, $slug, $category, $location, $description, $equipment_used, $main_image, $before_image, $after_image);
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE projects SET title = ?, slug = ?, category = ?, location = ?, description = ?, equipment_used = ?, main_image = ?, before_image = ?, after_image = ? WHERE id = ?");
        $stmt->bind_param("sssssssssi", $title, $slug, $category, $location, $description, $equipment_used, $main_image, $before_image, $after_image, $id);
    }

    if (isset($stmt) && $stmt->execute()) {
        $message .= "Proje başarıyla kaydedildi.";
    } else if (isset($stmt)) {
        $message .= "Hata: " . $stmt->error;
    }
    if (isset($stmt)) $stmt->close();
}

// Silme İşlemi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Proje ile ilişkili tüm görselleri de sil (project_images tablosundan)
    $stmt_del_images = $conn->prepare("DELETE FROM project_images WHERE project_id = ?");
    $stmt_del_images->bind_param("i", $id);
    $stmt_del_images->execute();
    $stmt_del_images->close();

    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Proje başarıyla silindi.";
    } else {
        $message = "Hata: " . $stmt->error;
    }
    $stmt->close();
}

// Projeleri Çekme
$projects = [];
$result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}

$edit_project = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_project = $result->fetch_assoc();
    $stmt->close();
}

?>

<div class="admin-container">
    <h2>Proje Yönetimi</h2>
    <p class="message-status"><?php echo $message; ?></p>

    <h3><?php echo $edit_project ? 'Proje Düzenle' : 'Yeni Proje Ekle'; ?></h3>
    <form action="projects.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_project ? 'edit' : 'add'; ?>">
        <?php if ($edit_project): ?>
            <input type="hidden" name="id" value="<?php echo $edit_project['id']; ?>">
            <input type="hidden" name="existing_main_image" value="<?php echo htmlspecialchars($edit_project['main_image'] ?? ''); ?>">
            <input type="hidden" name="existing_before_image" value="<?php echo htmlspecialchars($edit_project['before_image'] ?? ''); ?>">
            <input type="hidden" name="existing_after_image" value="<?php echo htmlspecialchars($edit_project['after_image'] ?? ''); ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="title">Proje Başlığı:</label>
            <input type="text" id="title" name="title" value="<?php echo $edit_project ? htmlspecialchars($edit_project['title']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="category">Kategori:</label>
            <select id="category" name="category" required>
                <option value="cami" <?php echo ($edit_project && $edit_project['category'] == 'cami') ? 'selected' : ''; ?>>Cami</option>
                <option value="konferans" <?php echo ($edit_project && $edit_project['category'] == 'konferans') ? 'selected' : ''; ?>>Konferans Salonu</option>
                <option value="yayin" <?php echo ($edit_project && $edit_project['category'] == 'yayin') ? 'selected' : ''; ?>>Yayın</option>
            </select>
        </div>
        <div class="form-group">
            <label for="location">Konum:</label>
            <input type="text" id="location" name="location" value="<?php echo $edit_project ? htmlspecialchars($edit_project['location']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Açıklama:</label>
            <textarea id="description" name="description" rows="8" required><?php echo $edit_project ? htmlspecialchars($edit_project['description']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="equipment_used">Kullanılan Ekipman (Opsiyonel):</label>
            <textarea id="equipment_used" name="equipment_used" rows="4"><?php echo $edit_project ? htmlspecialchars($edit_project['equipment_used']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="main_image">Ana Görsel:</label>
            <input type="file" id="main_image" name="main_image">
            <?php if ($edit_project && $edit_project['main_image']): ?>
                <p>Mevcut Görsel: <img src="../assets/images/<?php echo htmlspecialchars($edit_project['main_image']); ?>" alt="Mevcut Ana Görsel" width="100"></p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="before_image">Öncesi Görsel (Opsiyonel):</label>
            <input type="file" id="before_image" name="before_image">
            <?php if ($edit_project && $edit_project['before_image']): ?>
                <p>Mevcut Görsel: <img src="../assets/images/<?php echo htmlspecialchars($edit_project['before_image']); ?>" alt="Mevcut Öncesi Görsel" width="100"></p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="after_image">Sonrası Görsel (Opsiyonel):</label>
            <input type="file" id="after_image" name="after_image">
            <?php if ($edit_project && $edit_project['after_image']): ?>
                <p>Mevcut Görsel: <img src="../assets/images/<?php echo htmlspecialchars($edit_project['after_image']); ?>" alt="Mevcut Sonrası Görsel" width="100"></p>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $edit_project ? 'Projeyi Güncelle' : 'Proje Ekle'; ?></button>
    </form>

    <h3 style="margin-top: 40px;">Mevcut Projeler</h3>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>Kategori</th>
                    <th>Konum</th>
                    <th>Ana Görsel</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?php echo $project['id']; ?></td>
                        <td><?php echo htmlspecialchars($project['title']); ?></td>
                        <td><?php echo htmlspecialchars($project['category']); ?></td>
                        <td><?php echo htmlspecialchars($project['location']); ?></td>
                        <td>
                            <?php if ($project['main_image']): ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($project['main_image']); ?>" alt="" width="50">
                            <?php else: ?>
                                Yok
                            <?php endif; ?>
                        </td>
                        <td class="action-buttons">
                            <a href="project_images.php?project_id=<?php echo $project['id']; ?>" class="btn btn-sm btn-info">Görselleri Yönet</a>
                            <a href="projects.php?edit=<?php echo $project['id']; ?>" class="btn btn-sm btn-edit">Düzenle</a>
                            <a href="projects.php?delete=<?php echo $project['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bu projeyi silmek istediğinizden emin misiniz? Bu işlem ilişkili tüm görselleri de silecektir.');">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
