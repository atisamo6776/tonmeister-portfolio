
document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelector('.nav-links');
    const burger = document.querySelector('.burger');
    const ctaButtons = document.querySelectorAll('.open-quote-modal');
    const quoteModal = document.getElementById('quote-modal');
    const closeButton = document.querySelector('.close-button');
    const body = document.body;

    // Burger menü toggle
    burger.addEventListener('click', () => {
        navLinks.classList.toggle('nav-active');
        burger.classList.toggle('toggle');
    });

    // Teklif al modalını aç
    ctaButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            quoteModal.style.display = 'flex';
            body.style.overflow = 'hidden'; // Arka planı kaydırmayı engelle
        });
    });

    // Teklif al modalını kapat
    closeButton.addEventListener('click', () => {
        quoteModal.style.display = 'none';
        body.style.overflow = 'auto'; // Arka plan kaydırmayı etkinleştir
    });

    // Modal dışına tıklayınca kapat
    window.addEventListener('click', (e) => {
        if (e.target === quoteModal) {
            quoteModal.style.display = 'none';
            body.style.overflow = 'auto';
        }
    });

    // Slider fonksiyonu
    const slider = document.querySelector('#projects-slider .slider');
    const prevBtn = document.querySelector('#projects-slider .prev');
    const nextBtn = document.querySelector('#projects-slider .next');

    if (slider && prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
            slider.scrollBy({
                left: -slider.offsetWidth, // Bir slayt genişliği kadar kaydır
                behavior: 'smooth'
            });
        });

        nextBtn.addEventListener('click', () => {
            slider.scrollBy({
                left: slider.offsetWidth, // Bir slayt genişliği kadar kaydır
                behavior: 'smooth'
            });
        });
    }

    // Temel Dark/Light Tema geçişi (manuel olarak veya bir toggle butonu ile entegre edilebilir)
    // Örnek: Varsayılan olarak açık tema, ancak localStorage'dan okuyabiliriz.
    // const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;
    // if (currentTheme) {
    //     document.body.classList.add(currentTheme);
    // }

    // const themeToggleBtn = document.getElementById('theme-toggle'); // Eğer böyle bir buton eklenirse
    // if (themeToggleBtn) {
    //     themeToggleBtn.addEventListener('click', () => {
    //         document.body.classList.toggle('dark-theme');
    //         let theme = 'light-theme';
    //         if (document.body.classList.contains('dark-theme')) {
    //             theme = 'dark-theme';
    //         }
    //         localStorage.setItem('theme', theme);
    //     });
    // }

});
