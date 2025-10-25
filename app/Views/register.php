<div class="container mx-auto px-4 py-8 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-3xl font-bold mb-6 text-center text-winpass-green">Inscription</h1>
        <form id="register-form" class="space-y-4">
            <div><label for="nom" class="block text-sm font-medium mb-2">Nom</label><input type="text" id="nom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-green" required></div>
            <div><label for="prenom" class="block text-sm font-medium mb-2">Prénom</label><input type="text" id="prenom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-green" required></div>
            <div><label for="email" class="block text-sm font-medium mb-2">Email</label><input type="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-green" required></div>
            <div><label for="password" class="block text-sm font-medium mb-2">Mot de passe</label><input type="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-green" required></div>
            <div><label for="type" class="block text-sm font-medium mb-2">Je suis un</label><select id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-green"><option value="user">Utilisateur</option><option value="partner">Partenaire</option></select></div>
            <input type="hidden" id="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
            <button type="submit" class="w-full bg-winpass-green text-white py-2 rounded-md hover:bg-green-700 transition">S'inscrire</button>
        </form>
        <p class="text-center mt-4 text-sm">Déjà un compte? <a href="/index.php?route=login" class="text-winpass-blue hover:underline">Se connecter</a></p>
    </div>
</div>
<script>
document.getElementById('register-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = { 
        nom: document.getElementById('nom').value, 
        prenom: document.getElementById('prenom').value, 
        email: document.getElementById('email').value, 
        password: document.getElementById('password').value, 
        type: document.getElementById('type').value, 
        csrf_token: document.getElementById('csrf_token').value 
    };
    const response = await fetch('/api/auth.php?action=register', { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify(data) 
    });
    const result = await response.json();
    if (result.success) { 
        showToast(result.message);
        setTimeout(() => window.location.href = '/index.php?route=login', 1500);
    }
    else { showToast(result.message, 'error'); }
});
</script>
