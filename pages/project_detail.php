
<?php
include '../includes/db_connection.php';

$project = null;
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $project = $result->fetch_assoc();
    $stmt->close();

    if ($project) {
        $images_stmt = $conn->prepare("SELECT image_url, caption FROM project_images WHERE project_id = ? ORDER BY sort_order");
        $images_stmt->bind_param("i", $project_id);
        $images_stmt->execute();
        $project['images'] = $images_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $images_stmt->close();
    }
}

if (!$project) {
    // Proje bulunamazsa veya ID geçersizse 404 veya yönlendirme
    header("Location: projects.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?> - Proje Detayı</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <section id="project-detail" class="section-padding">
            <div class="container">
                <h2><?php echo htmlspecialchars($project['title']); ?></h2>
                <p class="project-meta"><strong>Konum:</strong> <?php echo htmlspecialchars($project['location']); ?> | <strong>Kategori:</strong> <?php echo htmlspecialchars($project['category']); ?></p>
                
                <?php if (!empty($project['main_image'])): ?>
                    <div class="main-project-image">
                        <img src="../assets/images/<?php echo htmlspecialchars($project['main_image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                    </div>
                <?php endif; ?>

                <div class="project-description">
                    <h3>Yapılan İşler</h3>
                    <p><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
                </div>

                <?php if (!empty($project['equipment_used'])): ?>
                    <div class="project-equipment">
                        <h3>Kullanılan Ekipmanlar</h3>
                        <p><?php echo nl2br(htmlspecialchars($project['equipment_used'])); ?></p>
                    </div>
                <?php endif; ?>

                <?php if (isset($project['images']) && count($project['images']) > 0): ?>
                    <div class="project-gallery">
                        <h3>Proje Galerisi</h3>
                        <div class="gallery-grid">
                            <?php foreach ($project['images'] as $img): ?>
                                <div class="gallery-item">
                                    <img src="../assets/images/<?php echo htmlspecialchars($img['image_url']); ?>" alt="<?php echo htmlspecialchars($img['caption']); ?>">
                                    <?php if (!empty($img['caption'])): ?>
                                        <div class="overlay"><h4><?php echo htmlspecialchars($img['caption']); ?></h4></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($project['before_image']) || !empty($project['after_image'])): ?>
                    <div class="before-after-section">
                        <h3>Öncesi / Sonrası</h3>
                        <div class="before-after-grid">
                            <?php if (!empty($project['before_image'])): ?>
                                <div class="before-image">
                                    <h4>Öncesi</h4>
                                    <img src="../assets/images/<?php echo htmlspecialchars($project['before_image']); ?>" alt="Öncesi">
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($project['after_image'])): ?>
                                <div class="after-image">
                                    <h4>Sonrası</h4>
                                    <img src="../assets/images/<?php echo htmlspecialchars($project['after_image']); ?>" alt="Sonrası">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="back-to-projects">
                    <a href="projects.php" class="btn btn-secondary">Tüm Projelere Geri Dön</a>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
</body>
</html>
