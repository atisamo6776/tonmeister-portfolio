# Tonmeister Serkan Ölçer - Profesyonel Ses Sistemleri Portföy Sitesi

Bu proje, İstanbul merkezli profesyonel bir ses sistemleri uzmanı / tonmaister olan Serkan Ölçer için hazırlanmış kurumsal bir web sitesidir.

## Proje Amacı

Potansiyel müşterilere Serkan Ölçer'in uzmanlığını, deneyimlerini, hizmetlerini ve referans projelerini sunarak güven oluşturmak ve yeni iş fırsatları yaratmaktır.

## Teknik Detaylar

*   **Backend:** PHP
*   **Frontend:** HTML5, CSS3, JavaScript
*   **Veritabanı:** MySQL
*   **Hosting Ortamı:** cPanel
*   **Kütüphaneler/Çerçeveler:** CSS için temel bir yapı (frameworksiz, custom CSS), JavaScript için vanilla JS.

## Özellikler

*   Modern ve sade kurumsal tasarım
*   Mobil uyumlu (Responsive Design)
*   Hizmetler ve detaylı açıklamalar
*   Referans projeler ve görselleri
*   İletişim ve teklif alma formları
*   Yönetilebilir içerik için Admin Paneli
*   SEO dostu yapı
*   Koyu/Açık Tema desteği (CSS ile temel yapı mevcut, JS entegrasyonu eklenebilir)

## Kurulum

1.  **Veritabanı:**
    *   `database.sql` dosyasını MySQL veritabanınıza import edin.
    *   `includes/db_connection.php` dosyasındaki veritabanı bağlantı bilgilerini kendi hosting bilgilerinizle güncelleyin.

2.  **Web Sunucusu:**
    *   Tüm proje dosyalarını (tonmeister-portfolio klasörünün içeriğini) cPanel'deki `public_html` veya ilgili web kök dizininize yükleyin.
    *   PHP 7.4 veya üzeri bir sürüm gerekmektedir.

3.  **Gereksinimler:**
    *   PHP yüklü bir web sunucusu (Apache/Nginx)
    *   MySQL veritabanı sunucusu

## Admin Paneli

Admin paneli (`/admin` dizini altında yer alacaktır) üzerinden site içeriğini dinamik olarak yönetebileceksiniz:

*   Ana sayfa metinleri ve slider görselleri
*   Hizmetler ekleme/düzenleme/silme
*   Proje ekleme/düzenleme/silme ve fotoğraf yönetimi
*   Galeri yönetimi
*   Blog yazıları ekleme/düzenleme/silme
*   Gelen iletişim ve teklif formlarını görüntüleme

## Katkıda Bulunma

Her türlü katkı ve öneriye açığız. Lütfen bir issue açarak veya pull request göndererek iletişime geçin.

## Lisans

Bu proje için herhangi bir açık kaynak lisansı belirtilmemiştir. Ticari kullanım için Serkan Ölçer ile iletişime geçiniz.
