document.addEventListener('DOMContentLoaded', function () {
    // Gestion de la sidebar responsive (si nécessaire, pas explicitement demandé mais bon pour UX)
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });
    }

    // Gestion des onglets de la sidebar pour afficher les sections correspondantes
    // (Pourrait être une amélioration future, ici on garde le scroll ou l'affichage en bloc)
});
