<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PengaturanController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Pengaturan', [
            'settings' => Setting::allAsArray(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'community_name'      => 'sometimes|required|string|max:255',
            'email'               => 'sometimes|required|email',
            'phone'               => 'sometimes|required|string|max:20',
            'address'             => 'nullable|string|max:500',
            'membership_fee'      => 'sometimes|required|numeric|min:0',
            'membership_duration' => 'sometimes|required|integer|min:1',
            'invoice_countdown'   => 'sometimes|required|integer|min:1',
            'account_deletion_duration' => 'sometimes|required|integer|min:1',
            'primary_color'       => 'sometimes|required|string|max:10',
            'surface_color'       => 'sometimes|required|string|max:10',
            'hero_title'          => 'nullable|string|max:255',
            'hero_description'    => 'nullable|string',
            'about_title'         => 'nullable|string|max:255',
            'about_description'   => 'nullable|string',
            'stat_member_aktif'   => 'nullable|integer|min:0',
            'stat_member_pasif'   => 'nullable|integer|min:0',
            'stat_member_company' => 'nullable|integer|min:0',
            'stat_member_personal'=> 'nullable|integer|min:0',
        ]);

        $fields = [
            'community_name', 'email', 'phone', 'address',
            'social_x', 'social_facebook', 'social_linkedin',
            'social_skype', 'social_instagram', 'social_youtube',
            'bank_account_name', 'bank_account_number', 'bank_name',
            'membership_fee', 'membership_duration', 'invoice_countdown',
            'account_deletion_duration',
            'primary_color', 'surface_color',
            'hero_title', 'hero_description',
            'about_title', 'about_description',
            'stat_member_aktif', 'stat_member_pasif',
            'stat_member_company', 'stat_member_personal',
        ];

        foreach ($fields as $field) {
            Setting::set($field, $request->input($field));
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|mimes:jpg,jpeg,png|max:1024']);
            $old = Setting::get('community_logo');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('logo')->store('logos', 'public');
            Setting::set('community_logo', $path);
        }

        if ($request->boolean('delete_logo')) {
            $old = Setting::get('community_logo');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            Setting::set('community_logo', null);
        }

        // Handle bg image upload
        if ($request->hasFile('bg_image')) {
            $request->validate(['bg_image' => 'image|mimes:jpg,jpeg,png|max:1024']);
            $old = Setting::get('bg_image');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('bg_image')->store('backgrounds', 'public');
            Setting::set('bg_image', $path);
        }

        if ($request->boolean('delete_bg_image')) {
            $old = Setting::get('bg_image');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            Setting::set('bg_image', null);
        }

        // Handle card background image upload
        if ($request->hasFile('card_background')) {
            $request->validate(['card_background' => 'image|mimes:jpg,jpeg,png|max:1024']);
            $old = Setting::get('card_background');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('card_background')->store('cards', 'public');
            Setting::set('card_background', $path);
        }

        if ($request->boolean('delete_card_background')) {
            $old = Setting::get('card_background');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            Setting::set('card_background', null);
        }

        // Handle about image upload
        if ($request->hasFile('about_image')) {
            $request->validate(['about_image' => 'image|mimes:jpg,jpeg,png|max:1024']);
            $old = Setting::get('about_image');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('about_image')->store('about', 'public');
            Setting::set('about_image', $path);
        }

        if ($request->boolean('delete_about_image')) {
            $old = Setting::get('about_image');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            Setting::set('about_image', null);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
