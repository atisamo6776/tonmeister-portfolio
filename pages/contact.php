
<?php
include '../includes/db_connection.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? null;
    $subject = $_POST['subject'] ?? null;
    $message_content = $_POST['message'];
    $type = 'contact'; // İletişim formu

    $stmt = $conn->prepare("INSERT INTO messages (name, email, phone, subject, message, type) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $phone, $subject, $message_content, $type);

    if ($stmt->execute()) {
        $message = "Mesajınız başarıyla gönderildi. En kısa sürede sizinle iletişime geçeceğiz.";
    } else {
        $message = "Mesaj gönderilirken bir hata oluştu: " . $conn->error;
    }
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim - Serkan Ölçer</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <section id="contact" class="section-padding">
            <div class="container">
                <h2>İletişim</h2>
                <p class="message-status"><?php echo $message; ?></p>
                <div class="contact-grid">
                    <div class="contact-info">
                        <h3>Bize Ulaşın</h3>
                        <p><i class="fas fa-phone"></i> Telefon: <a href="tel:+905XX XXX XX XX">+90 5XX XXX XX XX</a></p>
                        <p><i class="fab fa-whatsapp"></i> WhatsApp: <a href="https://wa.me/905XXXXXXXXX" target="_blank">+90 5XX XXX XX XX</a></p>
                        <p><i class="fas fa-envelope"></i> E-posta: <a href="mailto:info@serkanolcer.com">info@serkanolcer.com</a></p>
                        <p><i class="fas fa-map-marker-alt"></i> Adres: İstanbul, Türkiye</p>
                        <div class="social-media">
                            <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="contact-form-wrapper">
                        <h3>Mesaj Gönderin</h3>
                        <form action="contact.php" method="POST">
                            <input type="text" name="name" placeholder="Adınız Soyadınız" required>
                            <input type="email" name="email" placeholder="E-posta Adresiniz" required>
                            <input type="tel" name="phone" placeholder="Telefon Numaranız (Opsiyonel)">
                            <input type="text" name="subject" placeholder="Konu (Opsiyonel)">
                            <textarea name="message" placeholder="Mesajınız" rows="6" required></textarea>
                            <button type="submit" class="btn btn-primary">Mesajı Gönder</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
</body>
</html>
