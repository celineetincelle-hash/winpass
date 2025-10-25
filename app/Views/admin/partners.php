<?php $pageTitle = 'Gestion des Partenaires'; ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Partenaires</h1>
        <a href="/index.php?route=admin" class="text-winpass-blue hover:underline">&larr; Retour au Dashboard</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entreprise</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Secteur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscription</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="partners-table-body">
                <!-- Contenu chargé via AJAX -->
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div id="partners-pagination" class="mt-6"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const tableBody = document.getElementById('partners-table-body');
    const paginationContainer = document.getElementById('partners-pagination');
    
    const fetchPartners = async (page = 1) => {
        try {
            const response = await fetch(`/api/admin-actions.php?action=partners&page=${page}`);
            const result = await response.json();
            
            if (result.success) {
                tableBody.innerHTML = result.data.partners.map(partner => `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${partner.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${partner.nom_entreprise}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${partner.prenom} ${partner.nom}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${partner.secteur_activite}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${partner.statut === 'actif' ? 'bg-green-100 text-green-800' : partner.statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">
                                ${partner.statut}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${new Date(partner.date_inscription).toLocaleDateString('fr-FR')}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <select onchange="updatePartnerStatus(${partner.id}, this.value)" class="text-xs border rounded">
                                <option value="en_attente" ${partner.statut === 'en_attente' ? 'selected' : ''}>En attente</option>
                                <option value="actif" ${partner.statut === 'actif' ? 'selected' : ''}>Activer</option>
                                <option value="inactif" ${partner.statut === 'inactif' ? 'selected' : ''}>Désactiver</option>
                            </select>
                        </td>
                    </tr>
                `).join('');
                
                paginationContainer.innerHTML = renderPagination(result.data.pagination);
            }
        } catch (error) {
            console.error('Failed to fetch partners:', error);
        }
    };

    const renderPagination = (pagination) => {
        let html = '';
        if (pagination.currentPage > 1) {
            html += `<button onclick="fetchPartners(${pagination.currentPage - 1})" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Précédent</button>`;
        }
        for (let i = 1; i <= pagination.totalPages; i++) {
            const isActive = i === pagination.currentPage;
            html += `<button onclick="fetchPartners(${i})" class="px-3 py-1 ${isActive ? 'bg-winpass-blue text-white' : 'bg-gray-200'} rounded hover:bg-gray-300">${i}</button>`;
        }
        if (pagination.currentPage < pagination.totalPages) {
            html += `<button onclick="fetchPartners(${pagination.currentPage + 1})" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Suivant</button>`;
        }
        paginationContainer.innerHTML = html;
    };

    const updatePartnerStatus = async (partnerId, newStatus) => {
        try {
            const response = await fetch('/api/admin-actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'updatePartnerStatus', id: partnerId, status: newStatus })
            });
            const result = await response.json();
            if (result.success) {
                showToast('Statut mis à jour avec succès.');
                fetchPartners(); // Recharger la liste
            } else {
                showToast('Erreur lors de la mise à jour.', 'error');
            }
        } catch (error) {
            showToast('Erreur serveur.', 'error');
        }
    };

    window.updatePartnerStatus = updatePartnerStatus;
    await fetchPartners();
});
</script>
