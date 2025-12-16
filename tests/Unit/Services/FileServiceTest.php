<?php

namespace Tests\Unit\Services;

use App\Services\FileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileServiceTest extends TestCase
{
    private FileService $fileService;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->fileService = new FileService();
    }

    public function test_can_store_image(): void
    {
        $file = UploadedFile::fake()->image('test.jpg');
        $directory = 'test';

        $path = $this->fileService->storeImage($file, $directory);

        $this->assertNotEmpty($path);
        $this->assertStringStartsWith($directory.'/', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_can_delete_file(): void
    {
        Storage::disk('public')->put('test/file.jpg', 'content');

        $result = $this->fileService->deleteFile('test/file.jpg');

        $this->assertTrue($result);
        Storage::disk('public')->assertMissing('test/file.jpg');
    }

    public function test_delete_file_returns_true_when_file_empty(): void
    {
        $result = $this->fileService->deleteFile(null);

        $this->assertTrue($result);
    }

    public function test_delete_file_returns_true_when_file_not_exists(): void
    {
        $result = $this->fileService->deleteFile('non-existent.jpg');

        $this->assertTrue($result);
    }

    public function test_can_update_image(): void
    {
        $oldFile = 'old/file.jpg';
        Storage::disk('public')->put($oldFile, 'old content');

        $newFile = UploadedFile::fake()->image('new.jpg');
        $directory = 'test';

        $path = $this->fileService->updateImage($newFile, $oldFile, $directory);

        $this->assertNotEmpty($path);
        Storage::disk('public')->assertExists($path);
        Storage::disk('public')->assertMissing($oldFile);
    }

    public function test_update_image_returns_null_when_no_new_file(): void
    {
        $oldFile = 'old/file.jpg';
        Storage::disk('public')->put($oldFile, 'old content');

        $path = $this->fileService->updateImage(null, $oldFile, 'test');

        $this->assertNull($path);
        Storage::disk('public')->assertExists($oldFile);
    }

    public function test_update_image_deletes_old_file_when_new_file_provided(): void
    {
        $oldFile = 'old/file.jpg';
        Storage::disk('public')->put($oldFile, 'old content');

        $newFile = UploadedFile::fake()->image('new.jpg');

        $this->fileService->updateImage($newFile, $oldFile, 'test');

        Storage::disk('public')->assertMissing($oldFile);
    }
}
