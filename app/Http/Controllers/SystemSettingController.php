<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function edit()
    {
        return view('system-settings.edit', [
            'settings' => SystemSetting::current(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'point_redeem_value' => ['required', 'numeric', 'gt:0'],
            'point_earn_spend' => ['required', 'numeric', 'gt:0'],
            'default_max_redeem_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $settings = SystemSetting::current();
        $settings->update($data);

        return redirect()->route('system-settings.edit')->with('success', 'System settings berhasil diperbarui.');
    }
}
