<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info(' Démarrage du seeding...');

        // Créer les utilisateurs
        $this->command->info(' Création des utilisateurs...');
        $this->call(UserSeeder::class);

        // Créer les offres
        $this->command->info(' Création des offres...');
        $this->call(OfferSeeder::class);

        // Créer les produits (dépend des offres)
        // TODO: Créer le fichier ProductSeeder.php
        // $this->command->info(' Création des produits...');
        // $this->call(ProductSeeder::class);

        $this->command->info('');
        $this->command->info(' Seeding terminé avec succès !');
        $this->command->info('');
        $this->command->info(' Résumé :');
        $this->command->info('- Offres publiées : '.\App\Models\Offer::where('state', 'published')->count());
        $this->command->info('- Offres en brouillon : '.\App\Models\Offer::where('state', 'draft')->count());
        $this->command->info('- Offres masquées : '.\App\Models\Offer::where('state', 'hidden')->count());
        $this->command->info('- Total produits : '.\App\Models\Product::count());
        $this->command->info('');
        $this->command->info(' Vous pouvez maintenant accéder à l\'application !');
        $this->command->info('   - Dashboard : /dashboard');
        $this->command->info('   - API publique : /api/offers');
    }
}
