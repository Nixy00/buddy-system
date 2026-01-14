// Script pour le menu burger sur mobile

function initBurgerMenu() {
    const burgerBtn = document.getElementById('burger-toggle');
    const mainNav = document.getElementById('main-nav');

    if (!burgerBtn || !mainNav) return;

    // Quand on clique sur le burger
    burgerBtn.onclick = function () {
        burgerBtn.classList.toggle('active');
        mainNav.classList.toggle('open');

        const isOpen = mainNav.classList.contains('open');
        burgerBtn.setAttribute('aria-expanded', String(isOpen));
    };

    // Fermer le menu quand on clique sur un lien
    const allLinks = mainNav.querySelectorAll('a');
    allLinks.forEach(function (link) {
        link.onclick = function (e) {
            // On laisse le lien fonctionner normalement
            burgerBtn.classList.remove('active');
            mainNav.classList.remove('open');
            burgerBtn.setAttribute('aria-expanded', 'false');
        };
    });
}

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', initBurgerMenu);

// Réinitialiser après navigation Turbo
document.addEventListener('turbo:load', initBurgerMenu);
