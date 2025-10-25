<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">Devenir Partenaire</h1>
        <p class="text-gray-600 mb-8 text-center">Rejoignez notre réseau et bénéficiez d'une visibilité accrue auprès de milliers de clients potentiels</p>
        
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form id="partner-form" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nom" class="block text-sm font-medium mb-2">Nom</label>
                        <input type="text" id="nom" name="nom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required>
                    </div>
                    <div>
                        <label for="prenom" class="block text-sm font-medium mb-2">Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required>
                    </div>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium mb-2">Mot de passe</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required>
                </div>
                
                <div>
                    <label for="nom_entreprise" class="block text-sm font-medium mb-2">Nom de l'entreprise</label>
                    <input type="text" id="nom_entreprise" name="nom_entreprise" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required>
                </div>
                
                <div>
                    <label for="siege_social" class="block text-sm font-medium mb-2">Siège social</label>
                    <input type="text" id="siege_social" name="siege_social" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required>
                </div>
                
                <div>
                    <label for="adresse" class="block text-sm font-medium mb-2">Adresse complète</label>
                    <textarea id="adresse" name="adresse" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required></textarea>
                </div>
                
                <div>
                    <label for="secteur_activite" class="block text-sm font-medium mb-2">Secteur d'activité</label>
                    <select id="secteur_activite" name="secteur_activite" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required>
                        <option value="">Sélectionnez un secteur</option>
                        <option value="Restaurant">Restaurant</option>
                        <option value="Hôtel">Hôtel</option>
                        <option value="Shopping">Shopping</option>
                        <option value="Loisirs">Loisirs</option>
                        <option value="Services">Services</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                
                <div>
                    <label for="registre_commerce" class="block text-sm font-medium mb-2">Registre de commerce</label>
                    <input type="text" id="registre_commerce" name="registre_commerce" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required>
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" id="conditions" class="mr-2" required>
                        <span>J'accepte les conditions générales et la politique de confidentialité</span>
                    </label>
                </div>
                
                <input type="hidden" id="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                
                <button type="submit" class="w-full bg-winpass-blue text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition">Soumettre la demande</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('partner-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    try {
        const response = await fetch('/api/auth.php?action=registerPartner', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message);
            setTimeout(() => window.location.href = '/', 2000);
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        showToast('Erreur serveur.', 'error');
    }
});
</script>
