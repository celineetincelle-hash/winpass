<?php $pageTitle = 'Dashboard Admin'; ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Tableau de Bord Administrateur</h1>
    
    <!-- Statistiques en Cartes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Utilisateurs</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['totalUsers']; ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-handshake text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Partenaires</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['totalPartners']; ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-bullhorn text-yellow-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Annonces</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['totalAnnonces']; ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-check-circle text-purple-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Annonces Actives</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['activeAnnonces']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique des inscriptions -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-xl font-semibold mb-4">Évolution des inscriptions (6 derniers mois)</h2>
        <canvas id="registrationsChart" width="400" height="150"></canvas>
    </div>

    <!-- Actions Rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="/index.php?route=admin&subpage=users" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow block">
            <h3 class="text-lg font-semibold mb-2">Gérer les Utilisateurs</h3>
            <p class="text-gray-600">Voir, modifier le statut des utilisateurs.</p>
        </a>
        <a href="/index.php?route=admin&subpage=partners" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow block">
            <h3 class="text-lg font-semibold mb-2">Gérer les Partenaires</h3>
            <p class="text-gray-600">Valider ou modérer les partenaires.</p>
        </a>
        <a href="/index.php?route=admin&subpage=annonces" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow block">
            <h3 class="text-lg font-semibold mb-2">Gérer les Annonces</h3>
            <p class="text-gray-600">Valider ou modérer les offres soumises.</p>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('registrationsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin'],
            datasets: [{
                label: 'Nouveaux Utilisateurs',
                data: [12, 19, 15, 25, 22, 30],
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                tension: 0.1
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
});
</script>
