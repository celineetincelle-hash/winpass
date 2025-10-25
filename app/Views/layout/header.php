<?php
use Core\Session;
use Core\CSRF;

Session::start();
if (file_exists(__DIR__ . '/../../includes/config.php')) {
    require_once __DIR__ . '/../../includes/config.php';
} else {
    define('SITE_NAME', 'WinPass Tunisia');
    define('SITE_URL', 'http://localhost');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'WinPass Tunisia'); ?> - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script>
        tailwind.config = { 
            theme: { 
                extend: { 
                    colors: { 
                        'winpass-blue': '#0066cc', 
                        'winpass-green': '#00a651',
                        'winpass-red': '#e74c3c'
                    } 
                } 
            } 
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800">
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-winpass-blue"><?php echo SITE_NAME; ?></a>
            <button id="mobile-menu-button" class="md:hidden" aria-controls="mobile-menu" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <nav id="desktop-menu" class="hidden md:flex space-x-6">
                <a href="/" class="hover:text-winpass-blue transition">Accueil</a>
                <a href="/index.php?route=partenaires" class="hover:text-winpass-blue transition">Partenaires</a>
                <a href="/index.php?route=boutique" class="hover:text-winpass-blue transition">Boutique</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/index.php?route=profile" class="hover:text-winpass-blue transition">Profil</a>
                    <?php if ($_SESSION['user_type'] === 'admin'): ?>
                        <a href="/index.php?route=admin" class="hover:text-winpass-blue transition">Admin</a>
                    <?php endif; ?>
                    <a href="/index.php?route=logout" class="hover:text-winpass-blue transition">Déconnexion</a>
                <?php else: ?>
                    <a href="/index.php?route=login" class="hover:text-winpass-blue transition">Connexion</a>
                <?php endif; ?>
            </nav>
        </div>
        <nav id="mobile-menu" class="hidden md:hidden bg-white border-t" role="navigation" aria-label="Menu principal mobile">
            <div class="container mx-auto px-4 py-2 space-y-2">
                <a href="/" class="block py-2 hover:text-winpass-blue transition">Accueil</a>
                <a href="/index.php?route=partenaires" class="block py-2 hover:text-winpass-blue transition">Partenaires</a>
                <a href="/index.php?route=boutique" class="block py-2 hover:text-winpass-blue transition">Boutique</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/index.php?route=profile" class="block py-2 hover:text-winpass-blue transition">Profil</a>
                    <?php if ($_SESSION['user_type'] === 'admin'): ?>
                        <a href="/index.php?route=admin" class="block py-2 hover:text-winpass-blue transition">Admin</a>
                    <?php endif; ?>
                    <a href="/index.php?route=logout" class="block py-2 hover:text-winpass-blue transition">Déconnexion</a>
                <?php else: ?>
                    <a href="/index.php?route=login" class="block py-2 hover:text-winpass-blue transition">Connexion</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <div id="toast-container" class="fixed top-20 right-4 z-50 space-y-2"></div>
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            menu.classList.toggle('hidden');
            this.setAttribute('aria-expanded', !isExpanded);
        });
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            toast.className = `${bgColor} text-white px-4 py-2 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            toast.textContent = message;
            container.appendChild(toast);
            setTimeout(() => toast.classList.remove('translate-x-full'), 100);
            setTimeout(() => { 
                toast.classList.add('translate-x-full'); 
                setTimeout(() => container.removeChild(toast), 300); 
            }, 3000);
        }
    </script>
    <main>
