
<?php
include '../includes/db_connection.php';

$post = null;
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT id, title, content, image_url, author, created_at FROM blog_posts WHERE id = ? AND is_published = 1");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
}

if (!$post) {
    header("Location: blog.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Blog</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <section id="blog-detail" class="section-padding">
            <div class="container">
                <article>
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p class="post-meta">
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($post['author']); ?> |
                        <i class="fas fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($post['created_at'])); ?>
                    </p>

                    <?php if (!empty($post['image_url'])): ?>
                        <div class="blog-featured-image">
                            <img src="../assets/images/<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="max-width:100%; height:auto; border-radius:8px; margin-bottom:20px;">
                        </div>
                    <?php endif; ?>

                    <div class="blog-content">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>
                </article>

                <div class="back-to-blog" style="margin-top:30px;">
                    <a href="blog.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Tum Yazilara Don</a>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
</body>
</html>
