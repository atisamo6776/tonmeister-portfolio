
<?php include 'includes/db_connection.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesyonel Ses Sistemleri Kurulumu - Serkan Ölçer</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="index.php">Serkan Ölçer</a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="pages/about.php">Hakkında</a></li>
                <li><a href="pages/services.php">Hizmetler</a></li>
                <li><a href="pages/projects.php">Projeler</a></li>
                <li><a href="pages/gallery.php">Galeri</a></li>
                <li><a href="pages/contact.php">İletişim</a></li>
                <li><a href="pages/blog.php">Blog</a></li>
            </ul>
            <div class="burger">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </nav>
    </header>

    <main>
        <!-- Hero Section -->
        <section id="hero" class="hero">
            <div class="hero-content">
                <h1>Profesyonel Ses Sistemleri Kurulumu</h1>
                <p>Cami, Konferans Salonu ve Yayın Sistemleri Uzmanı</p>
                <div class="cta-buttons">
                    <a href="#" class="btn btn-primary open-quote-modal">Teklif Al</a>
                    <a href="pages/contact.php" class="btn btn-secondary">İletişime Geç</a>
                </div>
            </div>
        </section>

        <!-- Services Short Summary -->
        <section id="services-summary" class="section-padding">
            <div class="container">
                <h2>Hizmetlerimiz</h2>
                <div class="service-cards">
                    <!-- Services will be loaded dynamically from DB -->
                    <div class="service-card">
                        <i class="fas fa-mosque"></i>
                        <h3>Cami Ses Sistemleri</h3>
                        <p>Modern ve akustik uyumlu cami ses çözümleri.</p>
                        <a href="pages/services.php#cami" class="btn btn-sm">Detaylı Bilgi</a>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-microphone-alt"></i>
                        <h3>Konferans & Salon Sistemleri</h3>
                        <p>Net ve güçlü ses deneyimi için profesyonel çözümler.</p>
                        <a href="pages/services.php#konferans" class="btn btn-sm">Detaylı Bilgi</a>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-broadcast-tower"></i>
                        <h3>Yayın & Canlı Yayın Altyapısı</h3>
                        <p>Kesintisiz ve yüksek kaliteli yayın ses sistemleri.</p>
                        <a href="pages/services.php#yayin" class="btn btn-sm">Detaylı Bilgi</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Reference Projects Slider -->
        <section id="projects-slider" class="section-padding bg-light">
            <div class="container">
                <h2>Referans Projeler</h2>
                <div class="slider">
                    <!-- Projects will be loaded dynamically from DB -->
                    <div class="slide">
                        <img src="assets/images/project-placeholder-1.jpg" alt="Proje 1">
                        <h3>Cami Projesi X</h3>
                        <p>İstanbul - Cami Ses Sistemi Kurulumu</p>
                    </div>
                    <div class="slide">
                        <img src="assets/images/project-placeholder-2.jpg" alt="Proje 2">
                        <h3>Konferans Salonu Y</h3>
                        <p>Ankara - Konferans Ses Sistemleri</p>
                    </div>
                </div>
                <div class="slider-nav">
                    <button class="prev"><i class="fas fa-chevron-left"></i></button>
                    <button class="next"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section id="why-choose-us" class="section-padding">
            <div class="container">
                <h2>Neden Bizi Tercih Etmelisiniz?</h2>
                <div class="features">
                    <div class="feature-item">
                        <i class="fas fa-star"></i>
                        <h3>Deneyim ve Uzmanlık</h3>
                        <p>Yılların verdiği tecrübe ile en karmaşık projelere dahi çözüm sunuyoruz.</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-tools"></i>
                        <h3>Teknik Yeterlilik</h3>
                        <p>En güncel teknolojiler ve profesyonel ekipmanlarla çalışıyoruz.</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-handshake"></i>
                        <h3>Güvenilir Çözümler</h3>
                        <p>Her projede müşteri memnuniyetini ön planda tutarak güven inşa ediyoruz.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Client Logos / References -->
        <section id="client-logos" class="section-padding bg-dark">
            <div class="container">
                <h2>Çalıştığımız Kurumlar</h2>
                <div class="logos-grid">
                    <img src="assets/images/client-logo-placeholder-1.png" alt="Müşteri 1">
                    <img src="assets/images/client-logo-placeholder-2.png" alt="Müşteri 2">
                    <img src="assets/images/client-logo-placeholder-3.png" alt="Müşteri 3">
                    <img src="assets/images/client-logo-placeholder-4.png" alt="Müşteri 4">
                </div>
            </div>
        </section>

        <!-- Quick Contact Form -->
        <section id="quick-contact" class="section-padding">
            <div class="container">
                <h2>Hızlı İletişim</h2>
                <form action="submit_contact.php" method="POST" class="contact-form">
                    <input type="text" name="name" placeholder="Adınız Soyadınız" required>
                    <input type="email" name="email" placeholder="E-posta Adresiniz" required>
                    <input type="tel" name="phone" placeholder="Telefon Numaranız (Opsiyonel)">
                    <textarea name="message" placeholder="Mesajınız" rows="5" required></textarea>
                    <button type="submit" class="btn btn-primary">Gönder</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Serkan Ölçer. Tüm Hakları Saklıdır.</p>
            <div class="social-links">
                <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <!-- Teklif Al Modal -->
    <div id="quote-modal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Teklif Alın</h2>
            <form action="submit_quote.php" method="POST">
                <input type="text" name="name" placeholder="Adınız Soyadınız" required>
                <input type="email" name="email" placeholder="E-posta Adresiniz" required>
                <input type="tel" name="phone" placeholder="Telefon Numaranız" required>
                <textarea name="project_details" placeholder="Proje Detayları" rows="5" required></textarea>
                <button type="submit" class="btn btn-primary">Teklif Talebi Gönder</button>
            </form>
        </div>
    </div>

    <!-- WhatsApp Sabit İletişim Barı -->
    <div class="whatsapp-fixed-bar">
        <a href="https://wa.me/YOUR_PHONE_NUMBER" target="_blank">
            <i class="fab fa-whatsapp"></i> WhatsApp'tan Yazın
        </a>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
