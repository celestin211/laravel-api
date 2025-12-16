<?php

namespace App\Http\Requests\Offer;

use App\Rules\ImageValidation;
use App\Rules\StateValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $offerId = $this->route('offerId');
        if (! is_string($offerId)) {
            return false;
        }
        $offer = \App\Models\Offer::findOrFail($offerId);

        return $this->user()?->can('update', $offer) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $offerId = $this->route('offerId');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('offers', 'slug')->ignore($offerId), 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'image' => ['nullable', new ImageValidation(maxSize: 2048)],
            'description' => ['nullable', 'string', 'max:1000'],
            'state' => ['required', new StateValidation(['draft', 'published', 'hidden'])],
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
            'name.required' => 'Le nom de l\'offre est obligatoire.',
            'name.max' => 'Le nom de l\'offre ne doit pas dépasser 255 caractères.',
            'slug.required' => 'Le slug est obligatoire.',
            'slug.unique' => 'Ce slug est déjà utilisé.',
            'slug.regex' => 'Le slug doit contenir uniquement des lettres minuscules, des chiffres et des tirets.',
            'slug.max' => 'Le slug ne doit pas dépasser 255 caractères.',
            'description.max' => 'La description ne doit pas dépasser 1000 caractères.',
            'state.required' => 'L\'état est obligatoire.',
        ];
    }
}
