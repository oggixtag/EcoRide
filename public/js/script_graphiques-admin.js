// script_graphiques-admin.js

document.addEventListener("DOMContentLoaded", function () {
    // Graphique Covoiturages
    if (document.getElementById('graphiqueCovoiturages')) {
        const ctxCovoit = document.getElementById('graphiqueCovoiturages').getContext('2d');

        const labelsCovoit = donneesCovoiturages.map(item => item.date);
        const dataCovoit = donneesCovoiturages.map(item => item.total);

        new Chart(ctxCovoit, {
            type: 'bar',
            data: {
                labels: labelsCovoit,
                datasets: [{
                    label: 'Nombre de covoiturages',
                    data: dataCovoit,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Graphique Crédits
    if (document.getElementById('graphiqueCredits')) {
        const ctxCredits = document.getElementById('graphiqueCredits').getContext('2d');

        const labelsCredits = donneesCredits.map(item => item.date);
        const dataCredits = donneesCredits.map(item => item.total_credits);

        new Chart(ctxCredits, {
            type: 'line',
            data: {
                labels: labelsCredits,
                datasets: [{
                    label: 'Crédits gagnés',
                    data: dataCredits,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
