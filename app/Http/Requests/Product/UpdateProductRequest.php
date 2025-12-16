<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use App\Rules\ImageValidation;
use App\Rules\StateValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $productId = $this->route('productId');
        if (! is_string($productId)) {
            return false;
        }
        $product = \App\Models\Product::findOrFail($productId);

        return $this->user()?->can('update', $product) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('productId');

        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($productId), 'regex:/^[A-Z0-9-]+$/'],
            'image' => ['nullable', new ImageValidation(maxSize: 2048)],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'state' => ['required', new StateValidation(array_keys(Product::$states))],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du produit est obligatoire.',
            'name.max' => 'Le nom du produit ne doit pas dépasser 255 caractères.',
            'sku.required' => 'Le SKU est obligatoire.',
            'sku.unique' => 'Ce SKU est déjà utilisé.',
            'sku.regex' => 'Le SKU doit contenir uniquement des lettres majuscules, des chiffres et des tirets.',
            'sku.max' => 'Le SKU ne doit pas dépasser 255 caractères.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix ne peut pas être négatif.',
            'price.max' => 'Le prix ne doit pas dépasser 999 999,99.',
            'state.required' => 'L\'état est obligatoire.',
        ];
    }
}
