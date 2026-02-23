
<?php include '../includes/db_connection.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Serkan Ölçer</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <section id="blog" class="section-padding">
            <div class="container">
                <h2>Blog / Bilgi Merkezi</h2>
                <div class="blog-grid">
                    <?php
                    $sql = "SELECT id, title, slug, SUBSTRING(content, 1, 200) as excerpt, image_url, created_at FROM blog_posts WHERE is_published = 1 ORDER BY created_at DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($post = $result->fetch_assoc()) {
                            echo '<div class="blog-post-card">';
                            echo '    <img src="../assets/images/' . htmlspecialchars($post['image_url']) . '" alt="' . htmlspecialchars($post['title']) . '">';
                            echo '    <h3>' . htmlspecialchars($post['title']) . '</h3>';
                            echo '    <p class="post-date">' . date('d M Y', strtotime($post['created_at'])) . '</p>';
                            echo '    <p>' . htmlspecialchars($post['excerpt']) . '...</p>';
                            echo '    <a href="blog_detail.php?id=' . htmlspecialchars($post['id']) . '" class="btn btn-sm">Devamını Oku</a>';
                            echo '</div>';
                        }
                    } else {
                        echo "<p>Henüz blog yazısı bulunmamaktadır.</p>";
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
</body>
</html>
