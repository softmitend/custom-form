<?php

namespace App\Http\Controllers;

use App\Models\WebsiteProjectRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WebsiteProjectRequestController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'business_name' => ['required', 'string', 'max:160'],
            'whatsapp_number' => ['required', 'string', 'max:30', 'regex:/^\+?[0-9\s().-]{8,20}$/'],
            'website_type' => ['required', 'string', Rule::in([
                'Company profile',
                'Landing page',
                'Katalog produk atau layanan',
                'Toko online',
                'Website reservasi atau booking',
                'Sistem informasi atau dashboard',
                'Belum tahu, perlu konsultasi',
                'Lainnya',
            ])],
            'website_type_other' => ['nullable', 'required_if:website_type,Lainnya', 'string', 'max:160'],
            'website_goals' => ['required', 'array', 'min:1'],
            'website_goals.*' => ['string', 'max:160'],
            'website_goal_other' => ['nullable', Rule::requiredIf($this->hasSelectedOther($request, 'website_goals')), 'string', 'max:160'],
            'target_users' => ['nullable', 'array'],
            'target_users.*' => ['string', 'max:120'],
            'target_user_other' => ['nullable', Rule::requiredIf($this->hasSelectedOther($request, 'target_users')), 'string', 'max:160'],
            'desired_workflow' => ['required', 'string', 'max:2000'],
            'required_features' => ['nullable', 'array'],
            'required_features.*' => ['string', 'max:160'],
            'required_feature_other' => ['nullable', Rule::requiredIf($this->hasSelectedOther($request, 'required_features')), 'string', 'max:160'],
            'available_materials' => ['nullable', 'array'],
            'available_materials.*' => ['string', 'max:160'],
            'design_reference' => ['nullable', 'string', 'max:2000'],
            'target_completion_date' => ['required', 'date', 'after_or_equal:today'],
            'budget_range' => ['required', 'string', Rule::in([
                'Di bawah Rp1.000.000',
                'Rp1.000.000-Rp3.000.000',
                'Rp3.000.000-Rp5.000.000',
                'Rp5.000.000-Rp10.000.000',
                'Di atas Rp10.000.000',
                'Belum menentukan anggaran',
            ])],
            'additional_information' => ['nullable', 'string', 'max:2000'],
        ], [
            'whatsapp_number.regex' => 'Nomor WhatsApp harus menggunakan format nomor telepon yang valid.',
            'website_goals.required' => 'Pilih minimal satu tujuan website.',
            'website_goals.min' => 'Pilih minimal satu tujuan website.',
            'target_completion_date.after_or_equal' => 'Target selesai tidak boleh lebih awal dari hari ini.',
            '*.required' => 'Bagian ini wajib diisi.',
            '*.required_if' => 'Bagian ini wajib diisi ketika memilih Lainnya.',
        ]);

        try {
            DB::transaction(function () use ($validated): void {
                WebsiteProjectRequest::create($validated + ['status' => WebsiteProjectRequest::STATUS_NEW]);
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors([
                    'submission' => 'Form belum bisa dikirim. Silakan coba beberapa saat lagi.',
                ]);
        }

        return redirect()
            ->route('website-project-requests.create')
            ->with('success', 'Form berhasil dikirim. Terima kasih telah menjelaskan kebutuhan website Anda. Kami akan meninjau informasi tersebut dan menghubungi Anda melalui WhatsApp.');
    }

    private function hasSelectedOther(Request $request, string $field): bool
    {
        return in_array('Lainnya', (array) $request->input($field, []), true);
    }
}
