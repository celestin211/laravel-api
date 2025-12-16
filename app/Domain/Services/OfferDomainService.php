<?php

namespace App\Domain\Services;

use App\Domain\Repositories\OfferRepositoryInterface;
use App\Domain\Specifications\Offer\PublishableOfferSpecification;
use App\Models\Offer;

/**
 * Domain Service for Offer business rules.
 * Uses Specifications to validate complex business rules.
 */
class OfferDomainService
{
    public function __construct(
        private readonly OfferRepositoryInterface $repository,
        private readonly PublishableOfferSpecification $publishableSpecification
    ) {}

    /**
     * Check if an offer can be published.
     */
    public function canPublish(Offer $offer): bool
    {
        return $this->publishableSpecification->isSatisfiedBy($offer);
    }

    /**
     * Validate that an offer can be published, throw exception if not.
     *
     * @throws \DomainException If offer cannot be published
     */
    public function validatePublishable(Offer $offer): void
    {
        if (! $this->canPublish($offer)) {
            throw new \DomainException('Offer cannot be published. It must have at least one product, a name, and a slug.');
        }
    }
}
