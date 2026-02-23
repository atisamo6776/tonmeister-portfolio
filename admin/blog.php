
<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$page_title = "Blog Yönetimi";
include 'includes/header.php';

$message = '';

// Ekleme/Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $title = $_POST['title'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $content = $_POST['content'];
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    $author_id = $_SESSION['user_id']; // Oturumdaki kullanıcı ID'si
    $image_url = $_POST['existing_image_url'] ?? null;

    // Görsel yükleme işlemi
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $image_name = basename($_FILES['image']['name']);
        $image_url = $image_name;
        $target_file = $target_dir . $image_name;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $message = "Blog görseli yüklenirken hata oluştu.";
            $image_url = $_POST['existing_image_url'] ?? null;
        }
    } else if (isset($_POST['existing_image_url'])) {
        $image_url = $_POST['existing_image_url'];
    }

    if ($_POST['action'] == 'add') {
        $stmt = $conn->prepare("INSERT INTO blog_posts (title, slug, content, author_id, image_url, is_published) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisb", $title, $slug, $content, $author_id, $image_url, $is_published);
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE blog_posts SET title = ?, slug = ?, content = ?, image_url = ?, is_published = ? WHERE id = ?");
        $stmt->bind_param("sssiis", $title, $slug, $content, $image_url, $is_published, $id);
    }

    if (isset($stmt) && $stmt->execute()) {
        $message = "Blog yazısı başarıyla kaydedildi.";
    } else if (isset($stmt)) {
        $message = "Hata: " . $stmt->error;
    }
    if (isset($stmt)) $stmt->close();
}

// Silme İşlemi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Blog yazısı başarıyla silindi.";
    } else {
        $message = "Hata: " . $stmt->error;
    }
    $stmt->close();
}

// Blog Yazılarını Çekme
$blog_posts = [];
$result = $conn->query("SELECT bp.*, u.username FROM blog_posts bp LEFT JOIN users u ON bp.author_id = u.id ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $blog_posts[] = $row;
}

$edit_post = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_post = $result->fetch_assoc();
    $stmt->close();
}

?>

<div class="admin-container">
    <h2>Blog Yönetimi</h2>
    <p class="message-status"><?php echo $message; ?></p>

    <h3><?php echo $edit_post ? 'Blog Yazısı Düzenle' : 'Yeni Blog Yazısı Ekle'; ?></h3>
    <form action="blog.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_post ? 'edit' : 'add'; ?>">
        <?php if ($edit_post): ?>
            <input type="hidden" name="id" value="<?php echo $edit_post['id']; ?>">
            <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($edit_post['image_url']); ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="title">Başlık:</label>
            <input type="text" id="title" name="title" value="<?php echo $edit_post ? htmlspecialchars($edit_post['title']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="content">İçerik:</label>
            <textarea id="content" name="content" rows="10" required><?php echo $edit_post ? htmlspecialchars($edit_post['content']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Görsel:</label>
            <input type="file" id="image" name="image">
            <?php if ($edit_post && $edit_post['image_url']): ?>
                <p>Mevcut Görsel: <img src="../assets/images/<?php echo htmlspecialchars($edit_post['image_url']); ?>" alt="Mevcut Görsel" width="100"></p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <input type="checkbox" id="is_published" name="is_published" <?php echo ($edit_post && $edit_post['is_published']) ? 'checked' : ''; ?>>
            <label for="is_published">Yayınla</label>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $edit_post ? 'Yazıyı Güncelle' : 'Yazı Ekle'; ?></button>
    </form>

    <h3 style="margin-top: 40px;">Mevcut Blog Yazıları</h3>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>Yazar</th>
                    <th>Görsel</th>
                    <th>Yayınlandı</th>
                    <th>Tarih</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blog_posts as $post): ?>
                    <tr>
                        <td><?php echo $post['id']; ?></td>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo htmlspecialchars($post['username'] ?? 'Bilinmiyor'); ?></td>
                        <td>
                            <?php if ($post['image_url']): ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($post['image_url']); ?>" alt="" width="50">
                            <?php else: ?>
                                Yok
                            <?php endif; ?>
                        </td>
                        <td><?php echo $post['is_published'] ? 'Evet' : 'Hayır'; ?></td>
                        <td><?php echo date('d M Y H:i', strtotime($post['created_at'])); ?></td>
                        <td class="action-buttons">
                            <a href="blog.php?edit=<?php echo $post['id']; ?>" class="btn btn-sm btn-edit">Düzenle</a>
                            <a href="blog.php?delete=<?php echo $post['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bu yazıyı silmek istediğinizden emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
