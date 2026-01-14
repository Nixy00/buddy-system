
// On attend que la page soit chargée
document.addEventListener('DOMContentLoaded', function () {

    // On récupère les éléments
    const burgerBtn = document.getElementById('burger-toggle');
    const mainNav = document.getElementById('main-nav');

    // Si les éléments existent
    if (burgerBtn && mainNav) {

        // Quand on clique sur le burger
        burgerBtn.addEventListener('click', function () {

            // On ajoute/enlève la classe "active" et "open"
            burgerBtn.classList.toggle('active');
            mainNav.classList.toggle('open');

            // On met à jour l'attribut aria-expanded pour l'accessibilité
            const isOpen = mainNav.classList.contains('open');
            burgerBtn.setAttribute('aria-expanded', isOpen);
        });

        // Quand on clique sur un lien, on ferme le menu
        const navLinks = mainNav.querySelectorAll('.nav__link');
        navLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                burgerBtn.classList.remove('active');
                mainNav.classList.remove('open');
                burgerBtn.setAttribute('aria-expanded', 'false');
            });
        });
    }
});
