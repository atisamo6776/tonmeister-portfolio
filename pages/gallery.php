
<?php include '../includes/db_connection.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - Serkan Ölçer</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <section id="gallery" class="section-padding">
            <div class="container">
                <h2>Galeri</h2>
                <div class="gallery-grid">
                    <?php
                    $sql = "SELECT title, image_url, description FROM gallery_images ORDER BY created_at DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($image = $result->fetch_assoc()) {
                            echo '<div class="gallery-item">';
                            echo '    <img src="../assets/images/' . htmlspecialchars($image['image_url']) . '" alt="' . htmlspecialchars($image['title']) . '">';
                            echo '    <div class="overlay">';
                            echo '        <h3>' . htmlspecialchars($image['title']) . '</h3>';
                            echo '        <p>' . htmlspecialchars($image['description']) . '</p>';
                            echo '    </div>
                            </div>';
                        }
                    } else {
                        echo "<p>Henüz galeriye eklenmiş görsel bulunmamaktadır.</p>";
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
