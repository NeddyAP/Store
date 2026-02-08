<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Product;
use App\Services\ProductImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Mockery;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an admin user for authentication
        $this->admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_store_uses_image_service()
    {
        $file = UploadedFile::fake()->image('product.jpg');

        // Mock the service to verify interactions without touching filesystem
        $serviceMock = Mockery::mock(ProductImageService::class);
        $serviceMock->shouldReceive('handleImageUpload')
            ->once()
            ->with(Mockery::type(UploadedFile::class), 'laptop')
            ->andReturn('generated_filename.jpg');

        $this->app->instance(ProductImageService::class, $serviceMock);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('products.store'), [
                'name' => 'Test Product',
                'category' => 'laptop',
                'price' => 1000,
                'spec' => 'Specs',
                'qty' => 10,
                'desc' => 'Description',
                'color' => 'red,blue',
                'img' => $file,
            ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'img' => 'generated_filename.jpg',
        ]);
    }

    public function test_update_uses_image_service_and_deletes_old_images()
    {
        $product = Product::factory()->create([
            'category' => 'phone',
            'img' => 'old_image.jpg',
        ]);

        $file = UploadedFile::fake()->image('new_product.jpg');

        // Mock the service
        $serviceMock = Mockery::mock(ProductImageService::class);

        // Expect deleteImages to be called with old category and image
        $serviceMock->shouldReceive('deleteImages')
            ->once()
            ->with('phone', 'old_image.jpg');

        // Expect handleImageUpload to be called with new image
        $serviceMock->shouldReceive('handleImageUpload')
            ->once()
            ->with(Mockery::type(UploadedFile::class), 'phone')
            ->andReturn('new_generated_filename.jpg');

        $this->app->instance(ProductImageService::class, $serviceMock);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('products.update', $product->id), [
                'name' => 'Updated Product',
                'category' => 'phone',
                'price' => 1200,
                'spec' => 'Updated Specs',
                'qty' => 5,
                'desc' => 'Updated Description',
                'color' => 'green',
                'img' => $file,
            ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'img' => 'new_generated_filename.jpg',
        ]);
    }
}
