<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WebsiteProjectRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteProjectRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_page_can_be_viewed_without_changing_the_welcome_page(): void
    {
        $this->get('/formulir')
            ->assertOk()
            ->assertSee('Form Kebutuhan Pembuatan Website');

        $this->get('/')
            ->assertOk()
            ->assertSee('Make Your Brief');
    }

    public function test_customer_can_submit_a_valid_website_project_request(): void
    {
        $response = $this->post('/formulir', [
            'customer_name' => 'Ayu Lestari',
            'business_name' => 'Ayu Florist',
            'whatsapp_number' => '081234567890',
            'website_type' => 'Landing page',
            'website_goals' => ['Mendapatkan calon pelanggan'],
            'target_users' => ['Pelanggan'],
            'desired_workflow' => 'Pelanggan melihat layanan, mengisi form, lalu admin menghubungi melalui WhatsApp.',
            'required_features' => ['Tombol WhatsApp', 'Formulir kontak'],
            'available_materials' => ['Logo', 'Foto'],
            'design_reference' => 'Minimalis dan modern.',
            'target_completion_date' => now()->addDays(14)->toDateString(),
            'budget_range' => 'Rp3.000.000-Rp5.000.000',
            'additional_information' => 'Butuh halaman promo musiman.',
        ]);

        $response
            ->assertRedirect(route('website-project-requests.create'))
            ->assertSessionHas('success');

        $request = WebsiteProjectRequest::query()->first();

        $this->assertNotNull($request);
        $this->assertSame('Ayu Lestari', $request->customer_name);
        $this->assertSame('new', $request->status);
        $this->assertSame(['Mendapatkan calon pelanggan'], $request->website_goals);
        $this->assertSame(['Tombol WhatsApp', 'Formulir kontak'], $request->required_features);
    }

    public function test_required_fields_and_past_target_date_are_rejected(): void
    {
        $response = $this->from('/formulir')->post('/formulir', [
            'customer_name' => '',
            'business_name' => '',
            'whatsapp_number' => 'abc',
            'website_type' => '',
            'website_goals' => [],
            'desired_workflow' => '',
            'target_completion_date' => now()->subDay()->toDateString(),
            'budget_range' => '',
        ]);

        $response
            ->assertRedirect('/formulir')
            ->assertSessionHasErrors([
                'customer_name',
                'business_name',
                'whatsapp_number',
                'website_type',
                'website_goals',
                'desired_workflow',
                'target_completion_date',
                'budget_range',
            ]);

        $this->assertDatabaseCount('website_project_requests', 0);
    }

    public function test_other_fields_are_required_when_other_option_is_selected(): void
    {
        $response = $this->from('/formulir')->post('/formulir', [
            'customer_name' => 'Bima Santoso',
            'business_name' => 'Bima Studio',
            'whatsapp_number' => '081234567891',
            'website_type' => 'Lainnya',
            'website_goals' => ['Lainnya'],
            'target_users' => ['Lainnya'],
            'desired_workflow' => 'Pengguna memilih paket, mengisi kebutuhan, lalu admin membuat penawaran.',
            'required_features' => ['Lainnya'],
            'target_completion_date' => now()->addWeek()->toDateString(),
            'budget_range' => 'Belum menentukan anggaran',
        ]);

        $response
            ->assertRedirect('/formulir')
            ->assertSessionHasErrors([
                'website_type_other',
                'website_goal_other',
                'target_user_other',
                'required_feature_other',
            ]);

        $this->assertDatabaseCount('website_project_requests', 0);
    }

    public function test_admin_can_view_response_dashboard_and_detail(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'secret-password',
            'is_admin' => true,
        ]);

        $request = WebsiteProjectRequest::query()->create([
            'customer_name' => 'Citra Dewi',
            'business_name' => 'Citra Bakery',
            'whatsapp_number' => '081234567892',
            'website_type' => 'Katalog produk atau layanan',
            'website_goals' => ['Menampilkan produk atau layanan'],
            'target_users' => ['Pelanggan'],
            'desired_workflow' => 'Pelanggan membuka katalog, memilih produk, lalu menghubungi admin.',
            'required_features' => ['Katalog produk atau layanan', 'Tombol WhatsApp'],
            'available_materials' => ['Logo', 'Daftar produk atau layanan'],
            'target_completion_date' => now()->addDays(21)->toDateString(),
            'budget_range' => 'Rp1.000.000-Rp3.000.000',
            'status' => 'new',
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Dashboard Respon Form')
            ->assertSee('Citra Dewi')
            ->assertSee('Citra Bakery');

        $this->actingAs($admin)
            ->get(route('admin.website-project-requests.show', $request))
            ->assertOk()
            ->assertSee('Detail')
            ->assertSee('Citra Dewi')
            ->assertSee('Katalog produk atau layanan')
            ->assertSee('Pelanggan membuka katalog');
    }

    public function test_admin_pages_require_login_and_admin_can_login(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'secret-password',
            'is_admin' => true,
        ]);

        $this->get('/admin')
            ->assertRedirect(route('admin.login'));

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'secret-password',
        ])
            ->assertRedirect(route('admin.website-project-requests.index'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_can_update_response_status(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'secret-password',
            'is_admin' => true,
        ]);

        $request = WebsiteProjectRequest::query()->create([
            'customer_name' => 'Dian Putri',
            'business_name' => 'Dian Studio',
            'whatsapp_number' => '081234567893',
            'website_type' => 'Company profile',
            'website_goals' => ['Memperkenalkan bisnis atau perusahaan'],
            'desired_workflow' => 'Pengunjung membaca profil bisnis lalu menghubungi admin.',
            'target_completion_date' => now()->addDays(10)->toDateString(),
            'budget_range' => 'Rp1.000.000-Rp3.000.000',
            'status' => WebsiteProjectRequest::STATUS_NEW,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.website-project-requests.update-status', $request), [
                'status' => WebsiteProjectRequest::STATUS_CONTACTED,
            ])
            ->assertRedirect();

        $this->assertSame(
            WebsiteProjectRequest::STATUS_CONTACTED,
            $request->fresh()->status,
        );
    }
}
