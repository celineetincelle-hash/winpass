<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Mon Profil</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Mes Informations</h2>
                <form id="profile-form" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nom" class="block text-sm font-medium mb-2">Nom</label>
                            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required>
                        </div>
                        <div>
                            <label for="prenom" class="block text-sm font-medium mb-2">Prénom</label>
                            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required>
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" disabled>
                    </div>
                    <div>
                        <label for="telephone" class="block text-sm font-medium mb-2">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($user['telephone'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue">
                    </div>
                    <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                    <button type="submit" class="bg-winpass-blue text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">Mettre à jour</button>
                </form>
            </div>
        </div>
        
        <div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Ma Carte WinPass</h2>
                <?php if ($activeCard): ?>
                    <div class="text-center">
                        <div class="bg-gradient-to-r from-winpass-blue to-winpass-green text-white p-4 rounded-lg">
                            <p class="text-sm">Type: <?php echo ucfirst(htmlspecialchars($activeCard['type_abonnement'])); ?></p>
                            <p class="text-lg font-bold">Valide jusqu'au <?php echo date('d/m/Y', strtotime($activeCard['date_fin'])); ?></p>
                        </div>
                        <div id="profile-qr-code" class="mt-4"></div>
                        <p class="text-xs text-gray-500 mt-2">QR Code permanent de votre carte</p>
                    </div>
                <?php else: ?>
                    <p class="text-gray-600 text-center">Vous n'avez pas de carte active.</p>
                    <a href="/index.php?route=boutique" class="block w-full bg-winpass-green text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition text-center mt-4">Acheter une carte</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <h2 class="text-xl font-semibold mb-4">Mon Historique d'Utilisation</h2>
        <div id="qr-timeline" class="space-y-4">
            <p class="text-gray-500 text-center">Chargement de l'historique...</p>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    document.getElementById('profile-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch('/api/user-actions.php?action=updateProfile', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                showToast(result.message);
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('Erreur serveur.', 'error');
        }
    });

    <?php if ($activeCard): ?>
    new QRCode(document.getElementById('profile-qr-code'), {
        text: 'CARTE-<?php echo $activeCard['id']; ?>-<?php echo $_SESSION['user_id']; ?>',
        width: 150,
        height: 150,
    });
    <?php endif; ?>

    try {
        const response = await fetch('/api/user-actions.php?action=getQrHistory');
        const result = await response.json();
        const timeline = document.getElementById('qr-timeline');
        
        if (result.success && result.data.length > 0) {
            timeline.innerHTML = result.data.map(item => `
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-winpass-blue text-white rounded-full flex items-center justify-center">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <div class="flex-grow bg-gray-100 p-3 rounded-lg">
                        <p class="font-semibold">${item.annonce_titre}</p>
                        <p class="text-sm text-gray-600">Utilisé le ${new Date(item.date_utilisation).toLocaleString('fr-FR')}</p>
                    </div>
                </div>
            `).join('');
        } else {
            timeline.innerHTML = '<p class="text-gray-500 text-center">Vous n'avez pas encore utilisé d'offres.</p>';
        }
    } catch (error) {
        console.error('Failed to fetch QR history:', error);
    }
});
</script>
