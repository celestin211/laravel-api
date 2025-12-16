<?php

namespace App\Http\Controllers;

use App\DTOs\ProductData;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\OfferRepository;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private OfferRepository $offerRepository,
        private ProductRepository $productRepository
    ) {}

    public function index(int $offerId, \Illuminate\Http\Request $request): View
    {
        $offer = $this->offerRepository->findOrFail($offerId);
        $this->authorize('viewAny', \App\Models\Product::class);

        $perPage = min((int) $request->input('per_page', 10), 50); // Max 50 per page
        $page = max((int) $request->input('page', 1), 1);

        $products = \App\Models\Product::where('offer_id', $offerId)
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        return view('products.index', compact('offer', 'products'));
    }

    public function create(int $offerId): View
    {
        $offer = $this->offerRepository->findOrFail($offerId);
        $this->authorize('create', \App\Models\Product::class);

        $product = new Product();

        return view('products.create', compact('offer', 'product'));
    }

    public function store(StoreProductRequest $request, int $offerId): RedirectResponse
    {
        $offer = $this->offerRepository->findOrFail($offerId);
        $this->authorize('create', \App\Models\Product::class);

        $validated = $request->validated();
        $validated['offer_id'] = $offer->id;

        $data = ProductData::fromArray($validated);
        $image = $request->file('image');
        $imageFile = is_array($image) ? ($image[0] ?? null) : $image;
        $this->productService->create($data, $imageFile instanceof UploadedFile ? $imageFile : null);

        return redirect()
            ->route('offers.products.index', $offer->id)
            ->with('status', 'Produit créé avec succès.');
    }

    public function edit(int $offerId, int $productId): View
    {
        $offer = $this->offerRepository->findOrFail($offerId);
        $product = $this->productRepository->findOrFail($productId);

        // Verify product belongs to offer
        if ($product->offer_id !== $offer->id) {
            abort(404);
        }

        $this->authorize('update', $product);

        return view('products.edit', compact('offer', 'product'));
    }

    public function update(UpdateProductRequest $request, int $offerId, int $productId): RedirectResponse
    {
        $offer = $this->offerRepository->findOrFail($offerId);
        $product = $this->productRepository->findOrFail($productId);

        // Verify product belongs to offer
        if ($product->offer_id !== $offer->id) {
            abort(404);
        }

        $this->authorize('update', $product);

        $validated = $request->validated();
        $validated['offer_id'] = $offer->id;

        $data = ProductData::fromArray($validated);
        $image = $request->file('image');
        $imageFile = is_array($image) ? ($image[0] ?? null) : $image;
        $this->productService->update($productId, $data, $imageFile instanceof UploadedFile ? $imageFile : null);

        return redirect()
            ->route('offers.products.index', $offer->id)
            ->with('status', 'Produit mis à jour avec succès.');
    }

    public function destroy(int $offerId, int $productId): RedirectResponse
    {
        $offer = $this->offerRepository->findOrFail($offerId);
        $product = $this->productRepository->findOrFail($productId);

        // Verify product belongs to offer
        if ($product->offer_id !== $offer->id) {
            abort(404);
        }

        $this->authorize('delete', $product);

        $this->productService->delete($productId);

        return redirect()
            ->route('offers.products.index', $offer->id)
            ->with('status', 'Produit supprimé avec succès.');
    }
}
