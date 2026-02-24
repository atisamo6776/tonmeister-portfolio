
<?php

// Veritabanı kimlik bilgilerini ortam değişkenlerinden veya güvenli bir yapılandırma dosyasından alın.
// Güvenlik nedeniyle bu bilgileri doğrudan Git deposunda tutmaktan kaçının.
// CPanel ortamında genellikle manuel olarak doldurmanız veya public_html dışında bir dosyada tutmanız gerekir.

$host = getenv('DB_HOST') ?: 'localhost';
$database = getenv('DB_NAME') ?: 'your_database';
$user = getenv('DB_USER') ?: 'your_user';
$password = getenv('DB_PASSWORD') ?: 'your_password';

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
