<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function store(Request $request) {
        $saveData = Counter::create([
            'label' => $request->label,
            'value' => $request->value,
        ]);

        return redirect()->route('admin.settings.counter')->with([
            'message' => "Berhasil menambahkan counter"
        ]);
    }
    public function update(Request $request) {
        $updateData = Counter::where('id', $request->id)->update([
            'label' => $request->label,
            'value' => $request->value,
        ]);

        return redirect()->route('admin.settings.counter')->with([
            'message' => "Berhasil mengubah counter"
        ]);
    }
    public function delete(Request $request) {
        $deleteData = Counter::where('id', $request->id)->delete();

        return redirect()->route('admin.settings.counter')->with([
            'message' => "Berhasil menghapus counter"
        ]);
    }
}
