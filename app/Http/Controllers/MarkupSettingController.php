<?php


namespace App\Http\Controllers;

use App\Models\MarkupSetting;
use Illuminate\Http\Request;

class MarkupSettingController extends Controller
{
    public function index()
    {
        $markup = MarkupSetting::first();
        return view('markup.index', compact('markup'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'percentage' => 'required|numeric|min:0'
        ]);

        $markup = MarkupSetting::findOrFail($id);
        $markup->update([
            'percentage' => $request->percentage
        ]);

        return redirect()->back()->with('success', 'Markup berhasil diperbarui.');
    }
}
