
<footer>
    <div class="container">
        <p>&copy; <?php echo date("Y"); ?> Serkan Olcer. Tum Haklari Saklidir.</p>
        <div class="social-links">
            <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</footer>

<!-- WhatsApp Sabit Iletisim Bari -->
<div class="whatsapp-fixed-bar">
    <a href="https://wa.me/905000000000" target="_blank">
        <i class="fab fa-whatsapp"></i> WhatsApp'tan Yazin
    </a>
</div>

<?php
if (isset($conn)) {
    $conn->close();
}
?>
