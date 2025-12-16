<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Offres publiées (visibles dans l'API publique)
        $publishedOffers = [
            [
                'name' => 'Offre Spéciale Été',
                'slug' => 'offre-speciale-ete',
                'description' => 'Profitez de nos meilleures offres pour l\'été ! Des produits frais et de qualité à prix réduits.',
                'state' => 'published',
                'image' => 'offers/ete-2024.jpg', // Image simulée
            ],
            [
                'name' => 'Promotion Black Friday',
                'slug' => 'promotion-black-friday',
                'description' => 'Ne manquez pas notre promotion exceptionnelle du Black Friday. Des réductions jusqu\'à -50% sur une sélection de produits.',
                'state' => 'published',
                'image' => 'offers/black-friday.jpg', // Image simulée
            ],
            [
                'name' => 'Offre Premium',
                'slug' => 'offre-premium',
                'description' => 'Découvrez notre gamme premium avec des produits haut de gamme et un service client dédié.',
                'state' => 'published',
                'image' => 'offers/premium.jpg', // Image simulée
            ],
        ];

        foreach ($publishedOffers as $offerData) {
            Offer::factory()->create($offerData);
        }

        // Offres en brouillon (non visibles publiquement)
        Offer::factory()->count(2)->create([
            'state' => 'draft',
        ]);

        // Offres masquées
        Offer::factory()->count(1)->hidden()->create([
            'name' => 'Offre Archivée',
            'slug' => 'offre-archivee',
            'description' => 'Cette offre n\'est plus disponible.',
            'image' => 'offers/archived.jpg',
        ]);

        // Quelques offres supplémentaires avec images simulées
        Offer::factory()->count(3)->published()->withImage()->create();

        // Offres en brouillon avec images simulées
        /** @var \Illuminate\Database\Eloquent\Collection<int, Offer> $draftOffers */
        $draftOffers = Offer::factory()->count(2)->create([
            'state' => 'draft',
        ]);
        foreach ($draftOffers as $offer) {
            $offer->update(['image' => 'offers/draft-'.$offer->id.'.jpg']);
        }
    }
}
