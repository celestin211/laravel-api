<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Shared validation rule for image files.
 * Ensures consistent validation across the application.
 */
class ImageValidation implements ValidationRule
{
    /**
     * Maximum file size in kilobytes (default: 2048 KB = 2 MB).
     */
    private int $maxSize;

    /**
     * Allowed MIME types.
     *
     * @var array<string>
     */
    /** @var array<string> */
    private array $allowedMimes;

    /**
     * @param  array<string>  $allowedMimes
     */
    public function __construct(int $maxSize = 2048, array $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
    {
        $this->maxSize = $maxSize;
        $this->allowedMimes = $allowedMimes;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, string|null=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof \Illuminate\Http\UploadedFile) {
            $fail('Le fichier :attribute doit être un fichier image valide.', null);

            return;
        }

        // Check file size
        $fileSizeInKB = $value->getSize() / 1024;
        if ($fileSizeInKB > $this->maxSize) {
            $fail("Le fichier :attribute ne doit pas dépasser {$this->maxSize} Ko (".round($fileSizeInKB, 2).' Ko reçu).', null);

            return;
        }

        // Check MIME type
        $mimeType = $value->getMimeType();
        if (! in_array($mimeType, $this->allowedMimes, true)) {
            $fail('Le fichier :attribute doit être de type : '.implode(', ', $this->allowedMimes)." (type reçu : {$mimeType}).", null);

            return;
        }

        // Verify it's actually an image by checking extension
        $extension = strtolower($value->getClientOriginalExtension());
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (! in_array($extension, $allowedExtensions, true)) {
            $fail("L'extension du fichier :attribute n'est pas autorisée. Extensions autorisées : ".implode(', ', $allowedExtensions).'.', null);
        }
    }
}
