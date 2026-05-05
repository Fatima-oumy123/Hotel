<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $defaults = [
            'hotel_name' => config('hotel.name'),
            'hotel_phone' => config('hotel.phone'),
            'hotel_email' => config('hotel.email'),
            'hotel_address' => config('hotel.address'),
            'currency' => config('hotel.currency'),
            'tax_rate' => (string) config('hotel.tax_rate'),
            'stay_tax_per_night' => (string) config('hotel.stay_tax_per_night'),
            'default_lang' => config('hotel.lang_default', 'fr'),
            'mobile_money_enabled' => '1',
            'offline_sync_hint' => 'Synchronisation differée activee',
        ];

        $settings = collect($defaults)->mapWithKeys(function ($value, $key) {
            return [$key => HotelSetting::getValue($key, $value)];
        });

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hotel_name' => 'required|string|max:120',
            'hotel_phone' => 'required|string|max:40',
            'hotel_email' => 'required|email|max:160',
            'hotel_address' => 'required|string|max:500',
            'currency' => 'required|string|max:20',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'stay_tax_per_night' => 'required|numeric|min:0',
            'default_lang' => 'required|in:fr,en',
            'mobile_money_enabled' => 'nullable|boolean',
            'offline_sync_hint' => 'nullable|string|max:255',
        ]);

        foreach ($data as $key => $value) {
            HotelSetting::setValue($key, is_bool($value) ? ($value ? '1' : '0') : $value);
        }

        return back()->with('success', 'Parametres mis a jour.');
    }
}
