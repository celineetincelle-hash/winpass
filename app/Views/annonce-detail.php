<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <img src="/assets/images/default-partner.jpg" alt="<?php echo htmlspecialchars($annonce['titre']); ?>" class="w-full h-64 object-cover">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($annonce['titre']); ?></h1>
                    <p class="text-lg text-gray-600"><?php echo htmlspecialchars($annonce['nom_entreprise']); ?></p>
                    <p class="text-gray-500"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($annonce['adresse'] . ', ' . $annonce['ville']); ?></p>
                </div>
                <div class="text-right">
                    <span class="text-4xl font-bold text-winpass-green">-<?php echo htmlspecialchars($annonce['reduction']); ?>%</span>
                    <p class="text-sm text-gray-500">de réduction</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <h2 class="text-2xl font-semibold mb-4">Description de l'offre</h2>
                    <p class="text-gray-700 mb-6"><?php echo nl2br(htmlspecialchars($annonce['description'])); ?></p>
                    
                    <h3 class="text-xl font-semibold mb-2">Conditions</h3>
                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($annonce['conditions'] ?? 'Valable sur présentation de la carte WinPass active.')); ?></p>
                </div>
                
                <div>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Profitez de l'offre</h3>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button id="generate-qr-btn" class="w-full bg-winpass-blue text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-qrcode"></i> Générer mon QR Code
                            </button>
                            <div id="qr-code-container" class="mt-4 text-center hidden">
                                <p class="text-sm text-gray-600 mb-2">Présentez ce QR Code chez le partenaire :</p>
                                <div id="qr-code-image"></div>
                                <p class="text-xs text-gray-500 mt-2">Valable 10 minutes</p>
                            </div>
                        <?php else: ?>
                            <a href="/index.php?route=login" class="block w-full bg-winpass-blue text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition text-center">
                                <i class="fas fa-sign-in-alt"></i> Connectez-vous pour profiter de l'offre
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2">Contact</h3>
                        <p class="text-gray-700"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($annonce['telephone']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="map" class="mt-8 bg-gray-300 rounded-lg h-96"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const map = L.map('map').setView([<?php echo $annonce['latitude'] ?? 33.8869; ?>, <?php echo $annonce['longitude'] ?? 9.5375; ?>], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    const marker = L.marker([<?php echo $annonce['latitude'] ?? 33.8869; ?>, <?php echo $annonce['longitude'] ?? 9.5375; ?>]).addTo(map);
    marker.bindPopup('<b><?php echo htmlspecialchars($annonce['nom_entreprise']); ?></b>').openPopup();

    const generateBtn = document.getElementById('generate-qr-btn');
    const qrContainer = document.getElementById('qr-code-container');
    const qrImageDiv = document.getElementById('qr-code-image');
    
    if (generateBtn) {
        generateBtn.addEventListener('click', async () => {
            generateBtn.disabled = true;
            generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
            
            try {
                const response = await fetch('/api/generate-qr.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ annonce_id: <?php echo $annonce['id']; ?> })
                });
                const result = await response.json();
                
                if (result.success) {
                    qrImageDiv.innerHTML = '';
                    new QRCode(qrImageDiv, {
                        text: result.qr_data,
                        width: 200,
                        height: 200,
                    });
                    qrContainer.classList.remove('hidden');
                    showToast('QR Code généré avec succès !');
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('Erreur lors de la génération du QR Code.', 'error');
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = '<i class="fas fa-qrcode"></i> Générer mon QR Code';
            }
        });
    }
});
</script>
