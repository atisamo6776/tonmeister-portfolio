
<?php include '../includes/db_connection.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeler - Serkan Ölçer</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <section id="projects" class="section-padding">
            <div class="container">
                <h2>Referans Projelerimiz</h2>
                
                <div class="project-filters">
                    <button class="btn btn-secondary active" data-filter="all">Tümü</button>
                    <button class="btn btn-secondary" data-filter="cami">Cami</button>
                    <button class="btn btn-secondary" data-filter="konferans">Konferans Salonu</button>
                    <button class="btn btn-secondary" data-filter="yayin">Yayın</button>
                </div>

                <div class="project-grid">
                    <?php
                    $sql = "SELECT id, title, category, location, main_image FROM projects ORDER BY created_at DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($project = $result->fetch_assoc()) {
                            echo '<div class="project-card" data-category="' . htmlspecialchars($project['category']) . '">';
                            echo '    <img src="../assets/images/' . htmlspecialchars($project['main_image']) . '" alt="' . htmlspecialchars($project['title']) . '">';
                            echo '    <h3>' . htmlspecialchars($project['title']) . '</h3>';
                            echo '    <p>' . htmlspecialchars($project['location']) . '</p>
                            <a href="project_detail.php?id=' . htmlspecialchars($project['id']) . '" class="btn btn-sm">Detayları Gör</a>';
                            echo '</div>';
                        }
                    } else {
                        echo "<p>Henüz eklenmiş bir proje bulunmamaktadır.</p>";
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
    <script>
        // Proje filtreleme JavaScript
        document.addEventListener('DOMContentLoaded', () => {
            const filterButtons = document.querySelectorAll('.project-filters .btn');
            const projectCards = document.querySelectorAll('.project-grid .project-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    const filter = button.dataset.filter;

                    projectCards.forEach(card => {
                        if (filter === 'all' || card.dataset.category === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
