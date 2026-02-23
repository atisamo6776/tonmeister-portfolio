
<?php

// Veritabanı kimlik bilgilerini ortam değişkenlerinden veya güvenli bir yapılandırma dosyasından alın.
// Güvenlik nedeniyle bu bilgileri doğrudan Git deposunda tutmaktan kaçının.
// CPanel ortamında genellikle manuel olarak doldurmanız veya public_html dışında bir dosyada tutmanız gerekir.

$host = getenv('DB_HOST') ?: '92.249.63.61'; // Varsayılan olarak direkt IP, CPanel'de düzenlenmeli
$database = getenv('DB_NAME') ?: 'kesictrs_test'; // CPanel'de düzenlenmeli
$user = getenv('DB_USER') ?: 'kesictrs_admin'; // CPanel'de düzenlenmeli
$password = getenv('DB_PASSWORD') ?: 'Atik3777??'; // CPanel'de düzenlenmeli

// Eğer CPanel'de ortam değişkenleri ayarlanamıyorsa, aşağıdaki satırları kullanın
// ve bu dosyayı public_html dışındaki bir dizine taşıyarak include edin.
// Veya bu dosyayı public_html içinde bırakıp kimlik bilgilerini manuel olarak doldurun.
/*
$host = 'YOUR_DB_HOST';
$database = 'YOUR_DB_NAME';
$user = 'YOUR_DB_USER';
$password = 'YOUR_DB_PASSWORD';
*/

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Karakter setini ayarla
$conn->set_charset("utf8mb4");

?>
