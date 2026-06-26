<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PengaturanController extends Controller
{
    public function index(): Response
    {
        $settings = Setting::allAsArray();
        if (isset($settings['available_benefits'])) {
            $settings['available_benefits'] = json_decode($settings['available_benefits'], true);
        } else {
            $settings['available_benefits'] = [];
        }

        return Inertia::render('Admin/Pengaturan', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'community_name'      => 'sometimes|required|string|max:255',
            'email'               => 'sometimes|required|email',
            'phone'               => 'sometimes|required|string|max:20',
            'address'             => 'nullable|string|max:500',
            'membership_alert_days' => 'sometimes|required|integer|min:1',
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
            'available_benefits'  => 'nullable|array',
            'available_benefits.*'=> 'string|max:255',
        ]);

        $fields = [
            'community_name', 'email', 'phone', 'address',
            'social_x', 'social_facebook', 'social_linkedin',
            'social_skype', 'social_instagram', 'social_youtube',
            'bank_account_name', 'bank_account_number', 'bank_name',
            'membership_alert_days',
            'invoice_countdown', 'account_deletion_duration',
            'primary_color', 'surface_color',
            'hero_title', 'hero_description',
            'about_title', 'about_description',
            'stat_member_aktif', 'stat_member_pasif',
            'stat_member_company', 'stat_member_personal',
        ];

        $changes = [];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $oldValue = Setting::get($field);
                $newValue = $request->input($field);
                if ((string)$oldValue !== (string)$newValue) {
                    $changes[$field] = ['old' => $oldValue, 'new' => $newValue];
                    Setting::set($field, $newValue);
                }
            }
        }

        if ($request->has('available_benefits')) {
            $oldValue = Setting::get('available_benefits');
            $oldValueArray = $oldValue ? json_decode($oldValue, true) : [];
            $newValueArray = array_values(array_filter($request->input('available_benefits', [])));
            $newValue = json_encode($newValueArray);
            
            if ($oldValue !== $newValue) {
                $changes['available_benefits'] = ['old' => $oldValueArray, 'new' => $newValueArray];
                Setting::set('available_benefits', $newValue);
            }
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
            $changes['community_logo'] = 'Diperbarui';
        }

        if ($request->boolean('delete_logo')) {
            $old = Setting::get('community_logo');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            if (Setting::get('community_logo')) {
                Setting::set('community_logo', null);
                $changes['community_logo'] = 'Dihapus';
            }
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
            $changes['bg_image'] = 'Diperbarui';
        }

        if ($request->boolean('delete_bg_image')) {
            $old = Setting::get('bg_image');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            if (Setting::get('bg_image')) {
                Setting::set('bg_image', null);
                $changes['bg_image'] = 'Dihapus';
            }
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
            $changes['card_background'] = 'Diperbarui';
        }

        if ($request->boolean('delete_card_background')) {
            $old = Setting::get('card_background');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            if (Setting::get('card_background')) {
                Setting::set('card_background', null);
                $changes['card_background'] = 'Dihapus';
            }
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
            $changes['about_image'] = 'Diperbarui';
        }

        if ($request->boolean('delete_about_image')) {
            $old = Setting::get('about_image');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            if (Setting::get('about_image')) {
                Setting::set('about_image', null);
                $changes['about_image'] = 'Dihapus';
            }
        }

        if (!empty($changes)) {
            ActivityLog::record(
                'Ubah Pengaturan',
                'Setting',
                null,
                'Pengaturan Sistem',
                $changes
            );
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
