<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function edit()
    {
        $allSettings = Setting::get(['key', 'value']);
        return view('admin.settings', ['settings' => $allSettings]);
    }
    public function update(Request $request)
    {
        $inputData = $request->except('_token');
        foreach ($inputData as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }
        return redirect()->route('settings.edit')
            ->with('success', 'Successfully updated the settings.');
    }
}
