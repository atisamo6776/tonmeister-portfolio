
<?php include '../includes/db_connection.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hizmetler - Serkan Ölçer</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <section id="services" class="section-padding">
            <div class="container">
                <h2>Hizmetlerimiz</h2>
                <div class="service-list">
                    <!-- Hizmetler veritabanından dinamik olarak yüklenecek -->

                    <div class="service-item" id="cami">
                        <i class="fas fa-mosque"></i>
                        <h3>Cami Ses Sistemleri</h3>
                        <p>İbadet ortamının maneviyatına uygun, net ve anlaşılır ses çözümleri sunuyoruz. Cami mimarisine ve akustiğine özel keşifler yaparak, yankı sorunlarını gideriyor, hoparlör yerleşimlerini optimize ediyor ve mikrofon sistemlerini kusursuz bir şekilde kuruyoruz.</p>
                        <ul>
                            <li>Detaylı Keşif ve Projelendirme</li>
                            <li>Akustik Düzenleme ve Ses Yalıtımı</li>
                            <li>Yüksek Kaliteli Hoparlör ve Mikrofon Sistemleri Kurulumu</li>
                            <li>Mevcut Sistemlerin Bakım ve İyileştirilmesi</li>
                        </ul>
                    </div>

                    <div class="service-item" id="konferans">
                        <i class="fas fa-microphone-alt"></i>
                        <h3>Konferans & Salon Sistemleri</h3>
                        <p>Toplantı salonları, kongre merkezleri ve çok amaçlı salonlar için en son teknolojiye sahip ses çözümleri. Katılımcıların her kelimeyi net duyabildiği, kablosuz ve entegre sistemler ile profesyonel bir deneyim sağlıyoruz.</p>
                        <ul>
                            <li>Kablosuz Mikrofon ve Simultane Çeviri Sistemleri</li>
                            <li>Dijital Mikser ve Ses Kontrol Altyapısı</li>
                            <li>Sahne Ses Sistemi ve Monitör Çözümleri</li>
                            <li>Video Konferans Entegrasyonu</li>
                        </ul>
                    </div>

                    <div class="service-item" id="yayin">
                        <i class="fas fa-broadcast-tower"></i>
                        <h3>Yayın & Canlı Yayın Altyapısı</h3>
                        <p>Televizyon, radyo ve online platformlar için yüksek kaliteli yayın ve canlı yayın ses altyapısı kurulumu. Gürültü giderme, feedback kontrolü ve ses netliğini artırma konularında uzman çözümler sunuyoruz.</p>
                        <ul>
                            <li>Stüdyo ve Canlı Yayın Ses Altyapısı Kurulumu</li>
                            <li>Ses Kayıt ve Miksaj Çözümleri</li>
                            <li>Gürültü ve Feedback Sorunları Giderme</li>
                            <li>Mevcut Yayın Sistemlerinin Revizyon ve Bakımı</li>
                        </ul>
                    </div>

                    <div class="service-item" id="danismanlik">
                        <i class="fas fa-headset"></i>
                        <h3>Akustik Danışmanlık ve İyileştirme</h3>
                        <p>Mekanların akustik özelliklerini analiz ederek yankı, gürültü ve ses dengesizliği gibi sorunlara bilimsel çözümler sunuyoruz. Mevcut sistemlerinizin performansını artırmak için detaylı analiz ve iyileştirme hizmetleri sağlıyoruz.</p>
                        <ul>
                            <li>Mekan Akustiği Analizi ve Raporlama</li>
                            <li>Ses Yalıtımı ve Akustik Panel Çözümleri</li>
                            <li>Mevcut Ses Sistemlerinin Performans Analizi</li>
                            <li>Sistem Optimizasyonu ve Kalibrasyon</li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
</body>
</html>
