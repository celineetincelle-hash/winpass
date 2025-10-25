<?php
// app/Views/layout/footer.php
?>
    </main>
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">WinPass Advanced</h3>
                    <p class="text-gray-300">Votre plateforme de fidélité locale en Tunisie.</p>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Liens utiles</h4>
                    <ul class="space-y-2">
                        <li><a href="/index.php?route=partenaires" class="text-gray-300 hover:text-white">Partenaires</a></li>
                        <li><a href="/index.php?route=boutique" class="text-gray-300 hover:text-white">Boutique</a></li>
                        <li><a href="/index.php?route=about" class="text-gray-300 hover:text-white">À propos</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="/index.php?route=contact" class="text-gray-300 hover:text-white">Contact</a></li>
                        <li><a href="/index.php?route=faq" class="text-gray-300 hover:text-white">FAQ</a></li>
                        <li><a href="/index.php?route=terms" class="text-gray-300 hover:text-white">Conditions</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Suivez-nous</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>