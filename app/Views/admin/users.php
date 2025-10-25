<?php $pageTitle = 'Gestion des Utilisateurs'; ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Utilisateurs</h1>
        <a href="/index.php?route=admin" class="text-winpass-blue hover:underline">&larr; Retour au Dashboard</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscription</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="users-table-body">
                <!-- Contenu chargé via AJAX -->
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div id="users-pagination" class="mt-6"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const tableBody = document.getElementById('users-table-body');
    const paginationContainer = document.getElementById('users-pagination');
    
    const fetchUsers = async (page = 1) => {
        try {
            const response = await fetch(`/api/admin-actions.php?action=users&page=${page}`);
            const result = await response.json();
            
            if (result.success) {
                tableBody.innerHTML = result.data.users.map(user => `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.prenom} ${user.nom}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.email}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${user.type === 'admin' ? 'bg-purple-100 text-purple-800' : user.type === 'partner' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}">
                                ${user.type}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${user.statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${user.statut}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${new Date(user.date_inscription).toLocaleDateString('fr-FR')}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="updateUserStatus(${user.id}, '${user.statut === 'actif' ? 'inactif' : 'actif'}')" class="text-indigo-600 hover:text-indigo-900">
                                ${user.statut === 'actif' ? 'Désactiver' : 'Activer'}
                            </button>
                        </td>
                    </tr>
                `).join('');
                
                paginationContainer.innerHTML = renderPagination(result.data.pagination);
            }
        } catch (error) {
            console.error('Failed to fetch users:', error);
        }
    };

    const renderPagination = (pagination) => {
        let html = '';
        if (pagination.currentPage > 1) {
            html += `<button onclick="fetchUsers(${pagination.currentPage - 1})" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Précédent</button>`;
        }
        for (let i = 1; i <= pagination.totalPages; i++) {
            const isActive = i === pagination.currentPage;
            html += `<button onclick="fetchUsers(${i})" class="px-3 py-1 ${isActive ? 'bg-winpass-blue text-white' : 'bg-gray-200'} rounded hover:bg-gray-300">${i}</button>`;
        }
        if (pagination.currentPage < pagination.totalPages) {
            html += `<button onclick="fetchUsers(${pagination.currentPage + 1})" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Suivant</button>`;
        }
        paginationContainer.innerHTML = html;
    };

    const updateUserStatus = async (userId, newStatus) => {
        try {
            const response = await fetch('/api/admin-actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'updateUserStatus', id: userId, status: newStatus })
            });
            const result = await response.json();
            if (result.success) {
                showToast('Statut mis à jour avec succès.');
                fetchUsers(); // Recharger la liste
            } else {
                showToast('Erreur lors de la mise à jour.', 'error');
            }
        } catch (error) {
            showToast('Erreur serveur.', 'error');
        }
    };

    window.updateUserStatus = updateUserStatus;
    await fetchUsers();
});
</script>
