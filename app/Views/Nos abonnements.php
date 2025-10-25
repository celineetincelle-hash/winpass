<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Notre Boutique</h1>
    <p class="text-center text-gray-600 mb-8 max-w-2xl mx-auto">Choisissez l'abonnement qui vous convient pour profiter de toutes nos offres partout en Tunisie.</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white rounded-lg shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
            <h3 class="text-xl font-semibold mb-2">Découverte</h3>
            <p class="text-gray-600 mb-4">1 Semaine d'accès</p>
            <p class="text-4xl font-bold text-winpass-blue mb-4">15 TND</p>
            <ul class="text-left mb-6 space-y-2">
                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Accès à tous les partenaires</li>
                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Réductions jusqu'à 50%</li>
                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Application mobile incluse</li>
            </ul>
            <button class="buy-card w-full bg-winpass-blue text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition" data-type="semaine" data-price="15">Acheter</button>
        </div>
        <div class="bg-winpass-blue text-white rounded-lg shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
            <span class="bg-white text-winpass-blue text-xs font-bold px-3 py-1 rounded-full">POPULAIRE</span>
            <h3 class="text-xl font-semibold mb-2 mt-4">Classique</h3>
            <p class="text-blue-100 mb-4">1 Mois d'accès</p>
            <p class="text-4xl font-bold mb-4">40 TND</p>
            <ul class="text-left mb-6 space-y-2">
                <li class="flex items-center"><i class="fas fa-check text-white mr-2"></i> Accès à tous les partenaires</li>
                <li class="flex items-center"><i class="fas fa-check text-white mr-2"></i> Réductions jusqu'à 50%</li>
                <li class="flex items-center"><i class="fas fa-check text-white mr-2"></i> Application mobile incluse</li>
                <li class="flex items-center"><i class="fas fa-check text-white mr-2"></i> Support prioritaire</li>
            </ul>
            <button class="buy-card w-full bg-white text-winpass-blue font-bold py-2 px-4 rounded-lg hover:bg-gray-100 transition" data-type="mois" data-price="40">Acheter</button>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
            <h3 class="text-xl font-semibold mb-2">Premium</h3>
            <p class="text-gray-600 mb-4">1 An d'accès</p>
            <p class="text-4xl font-bold text-winpass-green mb-4">250 TND</p>
            <ul class="text-left mb-6 space-y-2">
                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Accès à tous les partenaires</li>
                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Réductions jusqu'à 50%</li>
                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Application mobile incluse</li>
                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Support prioritaire</li>
                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Accès aux événements exclusifs</li>
            </ul>
            <button class="buy-card w-full bg-winpass-green text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition" data-type="annee" data-price="250">Acheter</button>
        </div>
    </div>
</div>

<!-- Modal de paiement -->
<div id="payment-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <h2 class="text-2xl font-bold mb-4">Finaliser l'achat</h2>
        <p class="mb-4">Vous achetez une carte <span id="card-type" class="font-bold"></span> pour <span id="card-price" class="font-bold"></span> TND.</p>
        <p class="text-sm text-gray-600 mb-4">Ceci est une simulation. Le paiement ne sera pas débité.</p>
        <div class="flex justify-end space-x-4">
            <button id="cancel-payment" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
            <button id="confirm-payment" class="px-4 py-2 bg-winpass-green text-white rounded hover:bg-green-700">Confirmer et Payer</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('payment-modal');
    const cardTypeSpan = document.getElementById('card-type');
    const cardPriceSpan = document.getElementById('card-price');
    let selectedCard = {};

    document.querySelectorAll('.buy-card').forEach(button => {
        button.addEventListener('click', () => {
            if (!<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>) {
                showToast('Veuillez vous connecter pour acheter une carte.', 'error');
                window.location.href = '/index.php?route=login';
                return;
            }
            selectedCard = { type: button.dataset.type, price: button.dataset.price };
            cardTypeSpan.textContent = button.dataset.type;
            cardPriceSpan.textContent = button.dataset.price;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    document.getElementById('cancel-payment').addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    document.getElementById('confirm-payment').addEventListener('click', async () => {
        try {
            const response = await fetch('/api/paiement.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(selectedCard)
            });
            const result = await response.json();
            if (result.success) {
                showToast('Paiement réussi ! Votre QR code vous a été envoyé par email.', 'success');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            } else {
                showToast('Erreur lors du paiement: ' + result.message, 'error');
            }
        } catch (error) {
            showToast('Erreur serveur.', 'error');
        }
    });
});
</script>
