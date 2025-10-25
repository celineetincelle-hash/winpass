<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Tous nos Partenaires</h1>
    
    <!-- Filtres -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <form id="filter-form" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2">Catégorie</label>
                <select name="category" class="w-full p-3 border rounded-lg">
                    <option value="">Toutes les catégories</option>
                    <option value="1">Restaurant</option>
                    <option value="2">Hôtel</option>
                    <option value="3">Shopping</option>
                    <option value="4">Loisirs</option>
                    <option value="5">Services</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Ville</label>
                <select name="city" class="w-full p-3 border rounded-lg">
                    <option value="">Toutes les villes</option>
                    <option value="Tunis">Tunis</option>
                    <option value="Sousse">Sousse</option>
                    <option value="Sfax">Sfax</option>
                    <option value="Nabeul">Nabeul</option>
                    <option value="Hammamet">Hammamet</option>
                    <option value="Djerba">Djerba</option>
                    <option value="Monastir">Monastir</option>
                    <option value="Bizerte">Bizerte</option>
                    <option value="Gabès">Gabès</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Rayon (km)</label>
                <div class="flex items-center">
                    <input type="checkbox" id="geolocation" class="mr-2">
                    <input type="number" name="radius" min="1" max="25" value="10" class="w-full p-3 border rounded-lg" disabled>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Recherche</label>
                <input type="text" name="search" placeholder="Rechercher un partenaire..." class="w-full p-3 border rounded-lg">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-winpass-blue text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Sélections -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Nos Sélections</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <?php for ($i = 0; $i < 4; $i++): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                <img src="/assets/images/selection<?php echo $i+1; ?>.jpg" alt="Sélection" class="h-48 w-full object-cover">
                <div class="p-4">
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full mb-2 bg-winpass-blue text-white">Sélection</span>
                    <h3 class="font-bold text-lg mb-1">Meilleurs restaurants de Tunis</h3>
                    <p class="text-sm text-gray-600 mb-2">Découvrez notre sélection des meilleurs restaurants de la capitale</p>
                    <a href="#" class="text-winpass-blue hover:underline font-semibold">Voir la sélection</a>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Liste des partenaires -->
        <div id="partners-list" class="lg:col-span-2 space-y-6">
            <!-- Skeleton screens -->
            <?php for ($i = 0; $i < 6; $i++): ?>
                <div class="animate-pulse bg-gray-200 h-48 rounded-lg"></div>
            <?php endfor; ?>
        </div>
        
        <!-- Carte -->
        <div class="lg:col-span-1">
            <div id="map" class="bg-gray-300 rounded-lg h-96 sticky top-24"></div>
        </div>
    </div>

    <div id="pagination-controls" class="flex justify-center items-center space-x-2 mt-8"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const partnersList = document.getElementById('partners-list');
    const paginationControls = document.getElementById('pagination-controls');
    const mapContainer = document.getElementById('map');
    let map, markersLayer, userLocation = null;
    
    function initMap() {
        map = L.map('map').setView([33.8869, 9.5375], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        markersLayer = L.layerGroup().addTo(map);
    }
    initMap();

    // Gestion de la géolocalisation
    const geolocationCheckbox = document.getElementById('geolocation');
    const radiusInput = document.querySelector('input[name="radius"]');
    
    geolocationCheckbox.addEventListener('change', () => {
        radiusInput.disabled = !geolocationCheckbox.checked;
        if (geolocationCheckbox.checked) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        console.log('Position obtenue:', userLocation);
                        
                        // Ajouter un marqueur pour la position de l'utilisateur
                        L.marker([userLocation.lat, userLocation.lng])
                            .addTo(map)
                            .bindPopup('Votre position')
                            .openPopup();
                            
                        // Centrer la carte sur la position de l'utilisateur
                        map.setView([userLocation.lat, userLocation.lng], 12);
                    },
                    (error) => {
                        console.error('Erreur de géolocalisation:', error);
                        showToast('Impossible d'obtenir votre position', 'error');
                        geolocationCheckbox.checked = false;
                        radiusInput.disabled = true;
                    }
                );
            } else {
                showToast('La géolocalisation n'est pas supportée par votre navigateur', 'error');
                geolocationCheckbox.checked = false;
                radiusInput.disabled = true;
            }
        } else {
            userLocation = null;
            // Recharger la page pour réinitialiser les résultats
            fetchAndRenderPartners(1);
        }
    });

    const fetchAndRenderPartners = async (page = 1) => {
        const formData = new FormData(document.getElementById('filter-form'));
        const params = new URLSearchParams(formData);
        params.set('page', page);
        
        // Ajouter les coordonnées de l'utilisateur si la géolocalisation est activée
        if (userLocation) {
            params.set('lat', userLocation.lat);
            params.set('lng', userLocation.lng);
        }

        try {
            const response = await fetch(`/api/annonces.php?${params.toString()}`);
            const result = await response.json();
            
            if (result.success) {
                const newHtml = result.data.annonces.map(p => `
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex">
                        <img src="/assets/images/default-partner.jpg" alt="${p.titre}" class="h-40 w-40 object-cover">
                        <div class="p-4 flex-1">
                            ${p.promu ? '<span class="inline-block px-2 py-1 text-xs font-semibold rounded-full mb-2 bg-winpass-red text-white">Promu</span>' : ''}
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full mb-2" style="background-color: ${p.categorie_couleur}20; color: ${p.categorie_couleur};">
                                ${p.categorie_nom}
                            </span>
                            <h3 class="font-bold text-lg">${p.titre}</h3>
                            <p class="text-sm text-gray-600 mb-2">${p.nom_entreprise} - ${p.ville}</p>
                            <p class="text-gray-700 my-2">${p.description.substring(0, 150)}...</p>
                            ${p.distance ? `<p class="text-sm text-gray-500">À ${Math.round(p.distance * 10) / 10} km</p>` : ''}
                            <div class="flex justify-between items-center">
                                <span class="text-winpass-green font-bold text-xl">-${p.reduction}%</span>
                                <a href="/index.php?route=annonce-detail&id=${p.id}" class="text-winpass-blue hover:underline font-semibold">Voir l'offre</a>
                            </div>
                        </div>
                    </div>
                `).join('');

                partnersList.innerHTML = newHtml;
                renderPagination(result.data.pagination);
                updateMap(result.data.annonces);
            }
        } catch (error) {
            console.error('Failed to fetch partners:', error);
            partnersList.innerHTML = '<p class="col-span-3 text-center text-red-500">Erreur lors du chargement.</p>';
        }
    };

    const updateMap = (partners) => {
        markersLayer.clearLayers();
        const bounds = [];
        
        // Ajouter la position de l'utilisateur si disponible
        if (userLocation) {
            bounds.push([userLocation.lat, userLocation.lng]);
        }
        
        partners.forEach(p => {
            if (p.latitude && p.longitude) {
                const marker = L.marker([p.latitude, p.longitude]).addTo(markersLayer);
                marker.bindPopup(`<b>${p.titre}</b><br>${p.nom_entreprise}<br>-${p.reduction}%`);
                bounds.push([p.latitude, p.longitude]);
            }
        });
        if (bounds.length > 0) {
            map.fitBounds(bounds);
        }
    };

    const renderPagination = (pagination) => {
        let paginationHTML = '';
        if (pagination.currentPage > 1) {
            paginationHTML += `<button onclick="fetchAndRenderPartners(${pagination.currentPage - 1})" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Précédent</button>`;
        }
        for (let i = 1; i <= pagination.totalPages; i++) {
            const isActive = i === pagination.currentPage;
            paginationHTML += `<button onclick="fetchAndRenderPartners(${i})" class="px-3 py-1 ${isActive ? 'bg-winpass-blue text-white' : 'bg-gray-200'} rounded hover:bg-gray-300">${i}</button>`;
        }
        if (pagination.currentPage < pagination.totalPages) {
            paginationHTML += `<button onclick="fetchAndRenderPartners(${pagination.currentPage + 1})" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Suivant</button>`;
        }
        paginationControls.innerHTML = paginationHTML;
    };

    document.getElementById('filter-form').addEventListener('submit', (e) => {
        e.preventDefault();
        fetchAndRenderPartners(1);
    });

    await fetchAndRenderPartners(1);
});
</script>
