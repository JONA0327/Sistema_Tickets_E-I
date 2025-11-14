document.addEventListener('DOMContentLoaded', () => {
    const indexLinks = document.querySelectorAll('a[href^="#section-"]');

    indexLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const targetId = link.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            }
        });
    });

    const sections = document.querySelectorAll('[id^="section-"]');
    const indexItems = document.querySelectorAll('a[href^="#section-"]');

    function highlightActiveSection() {
        let current = '';

        sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            if (window.pageYOffset >= sectionTop - 100) {
                current = section.getAttribute('id');
            }
        });

        indexItems.forEach((item) => {
            item.classList.remove('bg-blue-50', 'border-blue-200');
            if (item.getAttribute('href') === `#${current}`) {
                item.classList.add('bg-blue-50', 'border-blue-200');
            }
        });
    }

    window.addEventListener('scroll', highlightActiveSection);
});
