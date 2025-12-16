<?php

namespace App\Http\Controllers;

use App\DTOs\OfferData;
use App\Http\Requests\Offer\StoreOfferRequest;
use App\Http\Requests\Offer\UpdateOfferRequest;
use App\Services\OfferService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\View\View;

class OfferController extends Controller
{
    public function __construct(
        private OfferService $offerService
    ) {}

    public function create(): View
    {
        $this->authorize('create', \App\Models\Offer::class);

        return view('offers.create');
    }

    public function store(StoreOfferRequest $request): RedirectResponse
    {
        $this->authorize('create', \App\Models\Offer::class);

        $data = OfferData::fromArray($request->validated());
        $image = $request->file('image');
        $imageFile = is_array($image) ? ($image[0] ?? null) : $image;
        $this->offerService->create($data, $imageFile instanceof UploadedFile ? $imageFile : null);

        return redirect()->route('dashboard')
            ->with('status', 'Offre créée avec succès.');
    }

    public function edit(int $offerId): View
    {
        $offer = $this->offerService->findWithProducts($offerId);
        $this->authorize('update', $offer);

        return view('offers.edit', compact('offer'));
    }

    public function update(UpdateOfferRequest $request, int $offerId): RedirectResponse
    {
        $offer = $this->offerService->find($offerId);
        $this->authorize('update', $offer);

        $data = OfferData::fromArray($request->validated());
        $image = $request->file('image');
        $imageFile = is_array($image) ? ($image[0] ?? null) : $image;
        $this->offerService->update($offerId, $data, $imageFile instanceof UploadedFile ? $imageFile : null);

        return redirect()->route('dashboard')
            ->with('status', 'Offre mise à jour avec succès.');
    }

    public function destroy(int $offerId): RedirectResponse
    {
        $offer = $this->offerService->find($offerId);
        $this->authorize('delete', $offer);

        $this->offerService->delete($offerId);

        return redirect()->route('dashboard')
            ->with('status', 'Offre supprimée avec succès.');
    }

    public function show(int $offerId): View
    {
        $offer = $this->offerService->findWithProducts($offerId);

        return view('offers.show', compact('offer'));
    }
}
