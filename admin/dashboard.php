
<?php
session_start();
include '../includes/db_connection.php';

// Kullanıcı girişi kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Admin mi kontrolü (isteğe bağlı, rol tabanlı erişim için)
// if ($_SESSION['role'] !== 'admin') {
//     header("Location: index.php"); // Yetkisiz erişimde giriş sayfasına yönlendir
//     exit();
// }

$page_title = "Dashboard";
include 'includes/header.php';
?>

<div class="admin-container">
    <h2>Yönetim Paneli Dashboard</h2>
    <p>Hoş geldiniz, <?php echo $_SESSION['username']; ?> (<?php echo $_SESSION['role']; ?>).</p>
    
    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>Toplam Hizmetler</h3>
            <p><?php 
                $result = $conn->query("SELECT COUNT(*) as total FROM services");
                echo $result->fetch_assoc()['total'];
            ?></p>
        </div>
        <div class="stat-card">
            <h3>Toplam Projeler</h3>
            <p><?php 
                $result = $conn->query("SELECT COUNT(*) as total FROM projects");
                echo $result->fetch_assoc()['total'];
            ?></p>
        </div>
        <div class="stat-card">
            <h3>Yeni Mesajlar</h3>
            <p><?php 
                $result = $conn->query("SELECT COUNT(*) as total FROM messages WHERE is_read = 0");
                echo $result->fetch_assoc()['total'];
            ?></p>
        </div>
    </div>

    <div class="quick-actions">
        <h3>Hızlı İşlemler</h3>
        <ul>
            <li><a href="services.php" class="btn btn-primary">Hizmetleri Yönet</a></li>
            <li><a href="projects.php" class="btn btn-primary">Projeleri Yönet</a></li>
            <li><a href="messages.php" class="btn btn-primary">Mesajları Görüntüle</a></li>
            <li><a href="blog.php" class="btn btn-primary">Blog Yazıları</a></li>
        </ul>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
