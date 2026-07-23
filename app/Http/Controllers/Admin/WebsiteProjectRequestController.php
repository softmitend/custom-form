<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteProjectRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WebsiteProjectRequestController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = trim((string) $request->query('status', ''));
        $statusLabels = WebsiteProjectRequest::statusLabels();

        if ($status !== '' && ! array_key_exists($status, $statusLabels)) {
            $status = '';
        }

        $responses = WebsiteProjectRequest::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('business_name', 'like', "%{$search}%")
                        ->orWhere('whatsapp_number', 'like', "%{$search}%")
                        ->orWhere('website_type', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => WebsiteProjectRequest::query()->count(),
            'new' => WebsiteProjectRequest::query()->where('status', 'new')->count(),
            'today' => WebsiteProjectRequest::query()->whereDate('created_at', today())->count(),
            'this_month' => WebsiteProjectRequest::query()
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),
        ];

        return view('admin.website-project-requests.index', [
            'responses' => $responses,
            'stats' => $stats,
            'statusLabels' => $statusLabels,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function show(WebsiteProjectRequest $websiteProjectRequest): View
    {
        return view('admin.website-project-requests.show', [
            'response' => $websiteProjectRequest,
            'sections' => $this->detailSections($websiteProjectRequest),
            'statusLabels' => WebsiteProjectRequest::statusLabels(),
        ]);
    }

    public function updateStatus(Request $request, WebsiteProjectRequest $websiteProjectRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(array_keys(WebsiteProjectRequest::statusLabels()))],
        ]);

        $websiteProjectRequest->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status respon berhasil diperbarui.');
    }

    /**
     * @return array<string, array<int, array{label: string, value: mixed}>>
     */
    private function detailSections(WebsiteProjectRequest $response): array
    {
        return [
            'Informasi Customer' => [
                ['label' => 'Nama lengkap', 'value' => $response->customer_name],
                ['label' => 'Nama bisnis, usaha, atau organisasi', 'value' => $response->business_name],
                ['label' => 'Nomor WhatsApp', 'value' => $response->whatsapp_number],
                ['label' => 'Status', 'value' => $response->statusLabel()],
                ['label' => 'Dikirim pada', 'value' => $response->created_at?->format('d M Y, H:i')],
            ],
            'Kebutuhan Website' => [
                ['label' => 'Jenis website yang dibutuhkan', 'value' => $response->website_type],
                ['label' => 'Jenis website lainnya', 'value' => $response->website_type_other],
                ['label' => 'Tujuan utama website', 'value' => $response->website_goals],
                ['label' => 'Tujuan website lainnya', 'value' => $response->website_goal_other],
                ['label' => 'Pengguna utama website', 'value' => $response->target_users],
                ['label' => 'Pengguna utama lainnya', 'value' => $response->target_user_other],
                ['label' => 'Alur penggunaan website', 'value' => $response->desired_workflow],
                ['label' => 'Fitur utama yang dibutuhkan', 'value' => $response->required_features],
                ['label' => 'Fitur lainnya', 'value' => $response->required_feature_other],
            ],
            'Desain dan Materi' => [
                ['label' => 'Materi website yang sudah tersedia', 'value' => $response->available_materials],
                ['label' => 'Contoh website atau gaya desain', 'value' => $response->design_reference],
            ],
            'Waktu dan Anggaran' => [
                ['label' => 'Target website selesai', 'value' => $response->target_completion_date?->format('d M Y')],
                ['label' => 'Kisaran anggaran', 'value' => $response->budget_range],
                ['label' => 'Informasi atau kebutuhan khusus lainnya', 'value' => $response->additional_information],
                ['label' => 'Terakhir diperbarui', 'value' => $response->updated_at?->format('d M Y, H:i')],
            ],
        ];
    }
}
