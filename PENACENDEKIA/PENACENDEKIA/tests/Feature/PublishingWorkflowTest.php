<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\BookCategory;
use App\Models\PaymentAccount;
use App\Models\Submission;
use App\Models\Payment;
use App\Models\DesignTask;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PublishingWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_full_publishing_workflow_step_1_to_completed(): void
    {
        $uniqueEmail = 'penulis_' . time() . '_' . rand(100, 999) . '@gmail.com';

        // 1. Author Registration
        $registerResponse = $this->post('/register', [
            'name' => 'Budi Penulis',
            'email' => $uniqueEmail,
            'phone' => '081234567890',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);
        $registerResponse->assertRedirect(route('author.profile.edit'));
        $author = User::where('email', $uniqueEmail)->first();
        $this->assertNotNull($author);

        // 2. Author Profile Completion (NIK & Address - no KTP required)
        $profileResponse = $this->actingAs($author)->post('/author/profile', [
            'nik' => '3201' . rand(100000000000, 999999999999),
            'address' => 'Jl. Merdeka No. 10, Jakarta Pusat',
        ]);
        $profileResponse->assertRedirect(route('author.dashboard'));
        $author = $author->fresh();
        $this->assertTrue($author->profile->isComplete());

        // 3. Author Submits Manuscript
        $category = BookCategory::first();
        $pdfContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R >>\nendobj\nxref\n0 4\n0000000000 65535 f\n0000000009 00000 n\n0000000058 00000 n\n0000000115 00000 n\ntrailer\n<< /Size 4 /Root 1 0 R >>\nstartxref\n164\n%%EOF";
        $manuscriptFile = UploadedFile::fake()->createWithContent('naskah_budi.pdf', $pdfContent);

        $submitResponse = $this->actingAs($author)->post('/author/submissions', [
            'title' => 'Cahaya di Balik Bukit',
            'editor_name' => 'Siti Editor',
            'category_id' => $category->id,
            'target_reader' => 'general',
            'synopsis' => 'Kisah perjuangan seorang guru muda yang mengabdi di pelosok nusantara dengan penuh cinta dan dedikasi serta harapan cerah.',
            'manuscript_file' => $manuscriptFile,
            'estimated_pages' => 120,
            'quantity' => 50,
            'cover_type' => 'soft',
        ]);
        $submitResponse->assertSessionHasNoErrors();

        $submission = Submission::where('user_id', $author->id)->first();
        $this->assertNotNull($submission);
        $this->assertEquals('pending_review', $submission->status);
        $this->assertStringStartsWith('PNC-', $submission->code);

        // 4. Admin Reviews & Approves with Total Price
        $admin = User::where('role', 'admin')->first();
        $reviewResponse = $this->actingAs($admin)->post("/admin/submissions/{$submission->id}/review", [
            'action' => 'approve',
            'total_price' => 3000000,
            'admin_notes' => 'Naskah sangat menarik dan layak terbit!',
        ]);
        $submission->refresh();
        $this->assertEquals('waiting_payment', $submission->status);
        $this->assertEquals(1500000, $submission->dp_amount);
        $this->assertEquals(1500000, $submission->final_amount);

        // 5. Author Uploads DP 50% Proof
        $bankAccount = PaymentAccount::first();
        $proofFile = UploadedFile::fake()->image('bukti_tf_dp.jpg');
        $uploadDpResponse = $this->actingAs($author)->post("/author/submissions/{$submission->id}/payment", [
            'payment_account_id' => $bankAccount->id,
            'proof_file' => $proofFile,
        ]);
        $uploadDpResponse->assertSessionHasNoErrors();
        $submission->refresh();
        $this->assertEquals('payment_verification', $submission->status);
        $dpPayment = Payment::where('submission_id', $submission->id)->where('type', 'dp')->first();
        $this->assertNotNull($dpPayment);
        $this->assertEquals('pending', $dpPayment->status);

        // 6. Admin Verifies DP Payment -> in_design
        $verifyDpResponse = $this->actingAs($admin)->post("/admin/payments/{$dpPayment->id}/verify", [
            'action' => 'approve',
        ]);
        $submission->refresh();
        $this->assertEquals('in_design', $submission->status);
        $this->assertEquals('verified', $dpPayment->fresh()->status);

        // 7. Admin Assigns Tasks to Designer
        $designer = User::where('role', 'designer')->first();
        $this->actingAs($admin)->post("/admin/submissions/{$submission->id}/assign-task", [
            'designer_id' => $designer->id,
            'task_type' => 'layout',
        ]);
        $this->actingAs($admin)->post("/admin/submissions/{$submission->id}/assign-task", [
            'designer_id' => $designer->id,
            'task_type' => 'cover',
        ]);
        $this->assertEquals(2, $submission->designTasks()->count());

        // 8. Designer Uploads Deliverables
        $layoutTask = $submission->designTasks()->where('task_type', 'layout')->first();
        $coverTask = $submission->designTasks()->where('task_type', 'cover')->first();

        $layoutResult = UploadedFile::fake()->createWithContent('layout_final.pdf', $pdfContent);
        $this->actingAs($designer)->post("/designer/tasks/{$layoutTask->id}/upload", [
            'result_file' => $layoutResult,
            'designer_notes' => 'Layout isi buku sudah selesai sesuai format A5.',
        ]);
        $this->assertEquals('done', $layoutTask->fresh()->status);

        $coverResult = UploadedFile::fake()->image('cover_final.jpg');
        $this->actingAs($designer)->post("/designer/tasks/{$coverTask->id}/upload", [
            'result_file' => $coverResult,
            'designer_notes' => 'Cover sudah resolusi 300 DPI.',
        ]);
        $this->assertEquals('done', $coverTask->fresh()->status);

        // 9. Admin Approves Design Tasks -> ready_to_print
        $this->actingAs($admin)->post("/admin/design-tasks/{$layoutTask->id}/review", ['action' => 'approve']);
        $this->actingAs($admin)->post("/admin/design-tasks/{$coverTask->id}/review", ['action' => 'approve']);
        $submission->refresh();
        $this->assertEquals('ready_to_print', $submission->status);

        // 10. Author Uploads Final Payment 50%
        $finalProofFile = UploadedFile::fake()->image('bukti_pelunasan.jpg');
        $this->actingAs($author)->post("/author/submissions/{$submission->id}/payment", [
            'payment_account_id' => $bankAccount->id,
            'proof_file' => $finalProofFile,
        ]);
        $submission->refresh();
        $this->assertEquals('payment_verification', $submission->status);

        // 11. Admin Verifies Final Payment -> printing
        $finalPayment = Payment::where('submission_id', $submission->id)->where('type', 'final')->first();
        $this->actingAs($admin)->post("/admin/payments/{$finalPayment->id}/verify", ['action' => 'approve']);
        $submission->refresh();
        $this->assertEquals('printing', $submission->status);

        // 12. Admin Marks Completed
        $this->actingAs($admin)->post("/admin/submissions/{$submission->id}/complete");
        $submission->refresh();
        $this->assertEquals('completed', $submission->status);
        $this->assertNotNull($submission->completed_at);
    }
}
