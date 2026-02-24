
document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelector('.nav-links');
    const burger = document.querySelector('.burger');
    const ctaButtons = document.querySelectorAll('.open-quote-modal');
    const quoteModal = document.getElementById('quote-modal');
    const closeButton = document.querySelector('.close-button');
    const body = document.body;

    // Burger menu toggle
    if (burger && navLinks) {
        burger.addEventListener('click', () => {
            navLinks.classList.toggle('nav-active');
            burger.classList.toggle('toggle');
        });
    }

    // Teklif al modalini ac
    if (ctaButtons.length > 0 && quoteModal) {
        ctaButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                quoteModal.style.display = 'flex';
                body.style.overflow = 'hidden';
            });
        });
    }

    // Teklif al modalini kapat
    if (closeButton && quoteModal) {
        closeButton.addEventListener('click', () => {
            quoteModal.style.display = 'none';
            body.style.overflow = 'auto';
        });
    }

    // Modal disina tikla kapat
    if (quoteModal) {
        window.addEventListener('click', (e) => {
            if (e.target === quoteModal) {
                quoteModal.style.display = 'none';
                body.style.overflow = 'auto';
            }
        });
    }

    // Slider fonksiyonu
    const slider = document.querySelector('#projects-slider .slider');
    const prevBtn = document.querySelector('#projects-slider .prev');
    const nextBtn = document.querySelector('#projects-slider .next');

    if (slider && prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
            slider.scrollBy({
                left: -slider.offsetWidth,
                behavior: 'smooth'
            });
        });

        nextBtn.addEventListener('click', () => {
            slider.scrollBy({
                left: slider.offsetWidth,
                behavior: 'smooth'
            });
        });
    }

    // Dark/Light tema gecisi
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme) {
        document.body.classList.add(currentTheme);
    }

    const themeToggleBtn = document.getElementById('theme-toggle');
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('dark-theme');
            let theme = 'light-theme';
            if (document.body.classList.contains('dark-theme')) {
                theme = 'dark-theme';
            }
            localStorage.setItem('theme', theme);
        });
    }

    // Form status mesajlarini goster (index.php icin)
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const msg = urlParams.get('msg');

    if (status && msg) {
        let alertMsg = '';
        if (status === 'success' && msg === 'contact_sent') {
            alertMsg = 'Mesajiniz basariyla gonderildi. En kisa surede sizinle iletisime gececegiz.';
        } else if (status === 'success' && msg === 'quote_sent') {
            alertMsg = 'Teklif talebiniz basariyla gonderildi. En kisa surede sizinle iletisime gececegiz.';
        } else if (status === 'error' && msg === 'missing_fields') {
            alertMsg = 'Lutfen tum zorunlu alanlari doldurun.';
        } else if (status === 'error' && msg === 'send_failed') {
            alertMsg = 'Mesaj gonderilirken bir hata olustu. Lutfen tekrar deneyin.';
        }

        if (alertMsg) {
            // Basit bir bildirim goster
            const notification = document.createElement('div');
            notification.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);padding:15px 30px;border-radius:8px;z-index:9999;font-weight:bold;box-shadow:0 4px 10px rgba(0,0,0,0.2);';
            if (status === 'success') {
                notification.style.backgroundColor = '#28a745';
                notification.style.color = 'white';
            } else {
                notification.style.backgroundColor = '#dc3545';
                notification.style.color = 'white';
            }
            notification.textContent = alertMsg;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 5000);

            // URL'den parametreleri temizle
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }
});
