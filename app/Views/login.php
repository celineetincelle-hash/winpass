<div class="container mx-auto px-4 py-8 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-3xl font-bold mb-6 text-center text-winpass-blue">Connexion</h1>
        <form id="login-form" class="space-y-4">
            <div><label for="email" class="block text-sm font-medium mb-2">Email</label><input type="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required></div>
            <div><label for="password" class="block text-sm font-medium mb-2">Mot de passe</label><input type="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-winpass-blue" required></div>
            <input type="hidden" id="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
            <button type="submit" class="w-full bg-winpass-blue text-white py-2 rounded-md hover:bg-blue-700 transition">Se connecter</button>
        </form>
        <p class="text-center mt-4 text-sm">Pas encore de compte? <a href="/index.php?route=register" class="text-winpass-blue hover:underline">S'inscrire</a></p>
    </div>
</div>
<script>
document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const csrfToken = document.getElementById('csrf_token').value;
    const response = await fetch('/api/auth.php?action=login', { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ email, password, csrf_token: csrfToken }) 
    });
    const result = await response.json();
    if (result.success) { 
        showToast(result.message);
        setTimeout(() => window.location.href = '/', 1000);
    }
    else { showToast(result.message, 'error'); }
});
</script>
