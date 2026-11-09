<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ebook;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminEbookTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_admin_can_view_ebook_index_page(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.ebooks.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen E-Book Resmi');
    }

    public function test_admin_can_upload_and_manage_ebook(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $fakePdf = UploadedFile::fake()->create('panduan_riset.pdf', 1024, 'application/pdf');
        $fakeCover = UploadedFile::fake()->image('cover_riset.jpg', 600, 800);

        $storeResponse = $this->actingAs($admin)->post(route('admin.ebooks.store'), [
            'title' => 'Buku Riset Komprehensif 2026',
            'author' => 'Prof. Dr. Ir. Cendekia',
            'editor' => 'Tim Redaksi Pena',
            'publisher' => 'PT. Pena Cendekia Pustaka',
            'category' => 'Sains & Teknologi',
            'isbn' => '978-623-9999-11-2',
            'edition' => 'Edisi Pertama',
            'publication_year' => 2026,
            'pages' => 320,
            'language' => 'Indonesia',
            'synopsis' => 'Buku rujukan utama metodologi riset modern.',
            'cover_image' => $fakeCover,
            'ebook_file' => $fakePdf,
            'access_type' => 'public',
            'is_free' => '1',
            'is_active' => '1',
            'order' => 1,
        ]);

        $storeResponse->assertRedirect(route('admin.ebooks.index'));
        $storeResponse->assertSessionHas('success');

        $ebook = Ebook::where('title', 'Buku Riset Komprehensif 2026')->first();
        $this->assertNotNull($ebook);
        $this->assertEquals('Prof. Dr. Ir. Cendekia', $ebook->author);
        $this->assertEquals('pdf', $ebook->file_format);
        $this->assertTrue($ebook->is_free);
        $this->assertTrue($ebook->is_active);

        // Verify Storage has the files
        Storage::disk('public')->assertExists($ebook->file_path);
        Storage::disk('public')->assertExists($ebook->cover_image);

        // Toggle Status
        $toggleResponse = $this->actingAs($admin)->post(route('admin.ebooks.toggleStatus', $ebook));
        $toggleResponse->assertStatus(302);
        $ebook->refresh();
        $this->assertFalse($ebook->is_active);

        // Download Endpoint
        $downloadResponse = $this->actingAs($admin)->get(route('admin.ebooks.download', $ebook));
        $downloadResponse->assertStatus(200);
        $ebook->refresh();
        $this->assertEquals(1, $ebook->download_count);

        // Preview Endpoint
        $previewResponse = $this->actingAs($admin)->get(route('admin.ebooks.preview', $ebook));
        $previewResponse->assertStatus(200);
        $ebook->refresh();
        $this->assertEquals(1, $ebook->view_count);

        // Delete
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.ebooks.destroy', $ebook));
        $deleteResponse->assertRedirect(route('admin.ebooks.index'));
        $this->assertDatabaseMissing('ebooks', ['id' => $ebook->id]);
    }
}
