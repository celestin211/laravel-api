<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Service for handling file operations.
 * Encapsulates file storage logic and provides a clean interface.
 * Includes security measures for file uploads.
 */
class FileService
{
    /**
     * Allowed MIME types for images.
     *
     * @var array<string>
     */
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /**
     * Allowed file extensions.
     *
     * @var array<string>
     */
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    /**
     * Maximum file size in bytes (2 MB).
     */
    private const MAX_FILE_SIZE = 2 * 1024 * 1024;

    /**
     * Store an uploaded image file with security validations.
     *
     * @param  UploadedFile  $file  The uploaded file
     * @param  string  $directory  The directory to store the file in (e.g., 'offers', 'products')
     * @param  string  $disk  The storage disk to use
     * @return string The stored file path
     *
     * @throws \RuntimeException If file validation fails or storage fails
     */
    public function storeImage(UploadedFile $file, string $directory, string $disk = 'public'): string
    {
        $this->validateImageFile($file);

        // Sanitize directory name to prevent directory traversal
        $directory = $this->sanitizeDirectory($directory);

        // Generate a secure filename
        $filename = $this->generateSecureFilename($file);

        // Store the file with the sanitized path
        $path = $file->storeAs($directory, $filename, ['disk' => $disk]);

        if ($path === false) {
            throw new \RuntimeException('Failed to store image file.');
        }

        return $path;
    }

    /**
     * Delete a file from storage.
     *
     * @param  string|null  $filePath  The file path to delete
     * @param  string  $disk  The storage disk
     * @return bool True if file was deleted or didn't exist, false on error
     */
    public function deleteFile(?string $filePath, string $disk = 'public'): bool
    {
        if (empty($filePath)) {
            return true;
        }

        return Storage::disk($disk)->delete($filePath);
    }

    /**
     * Update an image file, deleting the old one if it exists.
     *
     * @param  UploadedFile|null  $newFile  The new file to store
     * @param  string|null  $oldFilePath  The old file path to delete
     * @param  string  $directory  The directory to store the new file in
     * @param  string  $disk  The storage disk
     * @return string|null The new file path, or null if no new file was provided
     */
    public function updateImage(
        ?UploadedFile $newFile,
        ?string $oldFilePath,
        string $directory,
        string $disk = 'public'
    ): ?string {
        if ($newFile === null) {
            return null;
        }

        // Delete old file if it exists
        $this->deleteFile($oldFilePath, $disk);

        // Store new file
        return $this->storeImage($newFile, $directory, $disk);
    }

    /**
     * Validate that the uploaded file is a valid image.
     *
     * @param  UploadedFile  $file  The file to validate
     *
     * @throws \RuntimeException If validation fails
     */
    private function validateImageFile(UploadedFile $file): void
    {
        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \RuntimeException('Le fichier est trop volumineux. Taille maximale : '.(self::MAX_FILE_SIZE / 1024 / 1024).' Mo.');
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        if (! in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new \RuntimeException('Type de fichier non autorisé. Types autorisés : '.implode(', ', self::ALLOWED_MIME_TYPES).'.');
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (! in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new \RuntimeException('Extension de fichier non autorisée. Extensions autorisées : '.implode(', ', self::ALLOWED_EXTENSIONS).'.');
        }

        // Verify it's actually an image (additional security check)
        if (! @getimagesize($file->getRealPath())) {
            throw new \RuntimeException('Le fichier n\'est pas une image valide.');
        }
    }

    /**
     * Sanitize directory name to prevent directory traversal attacks.
     *
     * @param  string  $directory  The directory name to sanitize
     * @return string The sanitized directory name
     */
    private function sanitizeDirectory(string $directory): string
    {
        // Remove any path separators and dangerous characters
        $directory = str_replace(['/', '\\', '..', "\0"], '', $directory);

        // Remove any non-alphanumeric characters except hyphens and underscores
        $sanitized = preg_replace('/[^a-zA-Z0-9_-]/', '', $directory);

        // Ensure we always return a string (preg_replace can return null on error)
        return $sanitized ?? '';
    }

    /**
     * Generate a secure filename to prevent filename-based attacks.
     *
     * @param  UploadedFile  $file  The uploaded file
     * @return string A secure filename
     */
    private function generateSecureFilename(UploadedFile $file): string
    {
        // Get the original extension (already validated)
        $extension = strtolower($file->getClientOriginalExtension());

        // Generate a unique, secure filename
        // Using UUID-like string + timestamp for uniqueness and security
        $filename = Str::uuid()->toString().'_'.time().'.'.$extension;

        return $filename;
    }
}
