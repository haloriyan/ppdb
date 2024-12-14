<?php

namespace App\Http\Controllers;

use App\Models\Wave;
use Illuminate\Http\Request;

class WaveController extends Controller
{
    public function create() {
        return view('admin.wave.create');
    }
    public function store(Request $request) {
        $saveData = Wave::create([
            'name' => $request->name,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_quantity' => $request->quantity,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('admin.wave')->with([
            'message' => "Berhasil menambahkan gelombang baru"
        ]);
    }
    public function edit($id) {
        $wave = Wave::where('id', $id)->first();

        return view('admin.wave.edit', [
            'wave' => $wave
        ]);
    }
    public function update($id, Request $request) {
        $data = Wave::where('id', $id);
        $data->update([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'start_quantity' => $request->quantity,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('admin.wave')->with([
            'message' => "Berhasil mengubah data gelombang"
        ]);
    }
    public function delete(Request $request) {
        $data = Wave::where('id', $request->id);
        $deleteData = $data->delete();

        return redirect()->route('admin.wave')->with([
            'message' => "Berhasil menghapus gelombang"
        ]);
    }
}
