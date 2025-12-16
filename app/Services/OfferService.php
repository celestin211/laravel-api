<?php

namespace App\Services;

use App\DTOs\OfferData;
use App\Domain\Repositories\OfferRepositoryInterface;
use App\Models\Offer;
use Illuminate\Http\UploadedFile;

/**
 * Service for Offer business logic.
 * Handles all business operations related to offers.
 */
class OfferService
{
    public function __construct(
        private OfferRepositoryInterface $repository,
        private FileService $fileService
    ) {}

    /**
     * Create a new offer.
     *
     * @param  OfferData  $data  The offer data
     * @param  UploadedFile|null  $image  The uploaded image file
     * @return Offer The created offer
     */
    public function create(OfferData $data, ?UploadedFile $image = null): Offer
    {
        $offerData = $data->toArray();

        // Handle image upload
        if ($image !== null) {
            $offerData['image'] = $this->fileService->storeImage($image, 'offers');
        }

        return $this->repository->create($offerData);
    }

    /**
     * Update an existing offer.
     *
     * @param  int  $id  The offer ID
     * @param  OfferData  $data  The updated offer data
     * @param  UploadedFile|null  $image  The new image file (optional)
     * @return Offer The updated offer
     */
    public function update(int $id, OfferData $data, ?UploadedFile $image = null): Offer
    {
        $offer = $this->repository->findOrFail($id);
        $offerData = $data->toArray();

        // Handle image update
        if ($image !== null) {
            $offerData['image'] = $this->fileService->updateImage(
                $image,
                $offer->image,
                'offers'
            );
        } else {
            // Keep existing image if no new one provided
            $offerData['image'] = $offer->image;
        }

        $this->repository->update($offer, $offerData);

        $freshOffer = $offer->fresh();
        if ($freshOffer === null) {
            throw new \RuntimeException('Failed to refresh offer after update.');
        }

        return $freshOffer;
    }

    /**
     * Delete an offer and its associated image.
     *
     * @param  int  $id  The offer ID
     * @return bool True if deleted successfully
     */
    public function delete(int $id): bool
    {
        $offer = $this->repository->findOrFail($id);

        // Delete associated image
        $this->fileService->deleteFile($offer->image);

        return $this->repository->delete($offer);
    }

    /**
     * Find an offer by ID.
     *
     * @param  int  $id  The offer ID
     * @return Offer The offer
     */
    public function find(int $id): Offer
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * Find an offer by ID with its products.
     *
     * @param  int  $id  The offer ID
     * @return Offer The offer with products loaded
     */
    public function findWithProducts(int $id): Offer
    {
        return $this->repository->findWithProducts($id);
    }
}
