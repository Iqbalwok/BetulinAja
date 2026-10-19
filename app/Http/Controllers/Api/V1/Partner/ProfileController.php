<?php

namespace App\Http\Controllers\Api\V1\Partner;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Models\PartnerSkill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends BaseApiController
{
    /**
     * Get partner profile with skills
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load(['partnerProfile.skills.category']);

        return $this->sendSuccess($user, 'Profil mitra berhasil dimuat.');
    }

    /**
     * Update partner profile & documents
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->partnerProfile;

        if (!$profile) {
            return $this->sendError('Profil mitra tidak ditemukan.', null, 404);
        }

        $request->validate([
            'description' => ['nullable', 'string', 'max:1000'],
            'id_card_number' => ['nullable', 'string', 'max:30'],
            'years_experience' => ['nullable', 'integer', 'min:0'],
            'photo' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'id_card_photo' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'certificate_photo' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:service_categories,id'],
        ]);

        $updateData = $request->only(['description', 'id_card_number', 'years_experience']);

        if ($request->hasFile('photo')) {
            $updateData['photo'] = $request->file('photo')->store('partner-docs/avatars', 'public');
        }

        if ($request->hasFile('id_card_photo')) {
            $updateData['id_card_photo'] = $request->file('id_card_photo')->store('partner-docs/id-cards', 'public');
        }

        if ($request->hasFile('certificate_photo')) {
            $updateData['certificate_photo'] = $request->file('certificate_photo')->store('partner-docs/certificates', 'public');
        }

        $profile->update($updateData);

        // Update skills if provided
        if ($request->has('category_ids')) {
            PartnerSkill::where('partner_id', $profile->id)->delete();
            foreach ($request->category_ids as $catId) {
                PartnerSkill::create([
                    'partner_id' => $profile->id,
                    'category_id' => $catId,
                    'experience_years' => $request->input('years_experience', 0),
                ]);
            }
        }

        $profile->load('skills.category');

        return $this->sendSuccess($profile, 'Profil mitra berhasil diperbarui.');
    }
}
