<div class="container mx-auto px-4 py-8">
    <!-- Slider -->
    <div class="relative rounded-xl overflow-hidden mb-12 h-96">
        <div class="slider-container">
            <div class="slide active">
                <img src="/assets/images/slider1.jpg" alt="WinPass Tunisia" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h1 class="text-4xl md:text-5xl font-bold mb-4">Découvrez la Tunisie avec WinPass</h1>
                        <p class="text-xl mb-6">Des avantages exclusifs dans tout le pays</p>
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <a href="/index.php?route=register" class="bg-winpass-blue text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition">S'inscrire</a>
                            <a href="/index.php?route=boutique" class="bg-transparent border-2 border-white text-white font-bold py-3 px-6 rounded-lg hover:bg-white hover:text-winpass-blue transition">Acheter une carte</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <button class="w-3 h-3 rounded-full bg-white opacity-70"></button>
            <button class="w-3 h-3 rounded-full bg-white opacity-30"></button>
            <button class="w-3 h-3 rounded-full bg-white opacity-30"></button>
        </div>
    </div>

    <!-- Section de recherche -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-12">
        <h2 class="text-2xl font-bold mb-6 text-center">Trouvez des offres près de chez vous</h2>
        <form id="search-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
            <div class="flex items-end">
                <button type="submit" class="w-full bg-winpass-blue text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition">
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    <!-- Découvrez la Tunisie -->
    <div class="mb-16">
        <h2 class="text-3xl font-bold mb-8 text-center">Découvrez la Tunisie</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                <img src="/assets/images/tunis.jpg" alt="Tunis" class="h-32 w-full object-cover">
                <div class="p-4 text-center">
                    <h3 class="font-bold">Tunis</h3>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                <img src="/assets/images/sousse.jpg" alt="Sousse" class="h-32 w-full object-cover">
                <div class="p-4 text-center">
                    <h3 class="font-bold">Sousse</h3>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                <img src="/assets/images/djerba.jpg" alt="Djerba" class="h-32 w-full object-cover">
                <div class="p-4 text-center">
                    <h3 class="font-bold">Djerba</h3>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                <img src="/assets/images/hammamet.jpg" alt="Hammamet" class="h-32 w-full object-cover">
                <div class="p-4 text-center">
                    <h3 class="font-bold">Hammamet</h3>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                <img src="/assets/images/nabeul.jpg" alt="Nabeul" class="h-32 w-full object-cover">
                <div class="p-4 text-center">
                    <h3 class="font-bold">Nabeul</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Pourquoi choisir WinPass Tunisia -->
    <div class="bg-gradient-to-r from-winpass-blue to-winpass-green text-white rounded-xl p-8 mb-16">
        <h2 class="text-3xl font-bold mb-8 text-center">Pourquoi choisir WinPass Tunisia?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tag text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Réductions exclusives</h3>
                <p>Jusqu'à 50% de réduction chez nos partenaires sélectionnés</p>
            </div>
            <div class="text-center">
                <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Partout en Tunisie</h3>
                <p>Plus de 500 partenaires dans 24 villes tunisiennes</p>
            </div>
            <div class="text-center">
                <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-mobile-alt text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Application mobile</h3>
                <p>Scannez des QR codes et accédez à vos réductions instantanément</p>
            </div>
        </div>
    </div>

    <!-- Nos partenaires de confiance -->
    <div class="mb-16">
        <h2 class="text-3xl font-bold mb-8 text-center">Nos partenaires de confiance</h2>
        <p class="text-center text-gray-600 mb-8 max-w-2xl mx-auto">Découvrez les établissements qui vous fontent bénéficier d'avantages exclusifs</p>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bed text-winpass-blue text-2xl"></i>
                </div>
                <h3 class="font-bold">Hôtels</h3>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-utensils text-winpass-green text-2xl"></i>
                </div>
                <h3 class="font-bold">Restaurants</h3>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                <div class="bg-yellow-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shopping-bag text-yellow-500 text-2xl"></i>
                </div>
                <h3 class="font-bold">Shopping</h3>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-swimming-pool text-purple-500 text-2xl"></i>
                </div>
                <h3 class="font-bold">Loisirs</h3>
            </div>
        </div>
        
        <div class="text-center">
            <a href="/index.php?route=partenaires" class="bg-winpass-blue text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition inline-block">
                Voir tous les partenaires
            </a>
        </div>
    </div>

    <!-- Ce que disent nos utilisateurs -->
    <div class="mb-16">
        <h2 class="text-3xl font-bold mb-8 text-center">Ce que disent nos utilisateurs</h2>
        <p class="text-center text-gray-600 mb-8 max-w-2xl mx-auto">Découvrez les expériences de nos membres</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <img src="/assets/images/user1.jpg" alt="Sophie Martin" class="w-16 h-16 rounded-full mr-4">
                    <div>
                        <h3 class="font-bold">Sophie Martin</h3>
                        <p class="text-gray-600 text-sm">Touriste française</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">"WinPass Tunisia m'a fait économiser plus de 200DT pendant mes vacances. L'application est facile à utiliser et les réductions sont réelles!"</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <img src="/assets/images/user2.jpg" alt="Youssef Ben Ali" class="w-16 h-16 rounded-full mr-4">
                    <div>
                        <h3 class="font-bold">Youssef Ben Ali</h3>
                        <p class="text-gray-600 text-sm">Résident Tunis</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">"En tant que résident en Tunisie, j'utilise WinPass tous les week-ends. Les restaurants et hôtels partenaires offrent d'excellents services."</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <img src="/assets/images/user3.jpg" alt="Marco Rossi" class="w-16 h-16 rounded-full mr-4">
                    <div>
                        <h3 class="font-bold">Marco Rossi</h3>
                        <p class="text-gray-600 text-sm">Touriste italien</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">"L'application mobile est très pratique. J'ai pu scanner des QR codes et obtenir des réductions immédiates dans plusieurs magasins à Sousse."</p>
            </div>
        </div>
    </div>

    <!-- Appel à l'action -->
    <div class="bg-gradient-to-r from-winpass-blue to-winpass-green text-white rounded-xl p-12 text-center">
        <h2 class="text-3xl font-bold mb-4">Prêt à économiser sur vos prochaines visites en Tunisie?</h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto">Rejoignez des milliers d'utilisateurs qui profitent déjà des avantages WinPass Tunisia</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="/index.php?route=register" class="bg-white text-winpass-blue font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition">S'inscrire maintenant</a>
            <a href="/index.php?route=boutique" class="bg-transparent border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-winpass-blue transition">Acheter une carte</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Gestion du slider
    const slides = document.querySelectorAll('.slide');
    const indicators = document.querySelectorAll('.bottom-4 button');
    let currentSlide = 0;
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('opacity-70', i === index);
            indicator.classList.toggle('opacity-30', i !== index);
        });
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    // Auto-avancement du slider
    setInterval(nextSlide, 5000);
    
    // Gestion des indicateurs
    indicators.forEach((indicator, i) => {
        indicator.addEventListener('click', () => {
            currentSlide = i;
            showSlide(currentSlide);
        });
    });
    
    // Gestion de la géolocalisation
    const geolocationCheckbox = document.getElementById('geolocation');
    const radiusInput = document.querySelector('input[name="radius"]');
    
    geolocationCheckbox.addEventListener('change', () => {
        radiusInput.disabled = !geolocationCheckbox.checked;
        if (geolocationCheckbox.checked) {
            // Demander la position de l'utilisateur
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        console.log('Position obtenue:', position.coords.latitude, position.coords.longitude);
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
        }
    });
});
</script>
