<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function store(Request $request) {
        $saveData = Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'priority' => 0,
        ]);

        return redirect()->route('admin.faq')->with([
            'message' => "Berhasil menambahkan data FAQ"
        ]);
    }
    public function update(Request $request) {
        $data = Faq::where('id', $request->id);
        $faq = $data->first();

        $updateData = $data->update([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return redirect()->route('admin.faq')->with([
            'message' => "Berhasil mengubah data FAQ"
        ]);
    }
    public function delete(Request $request) {
        $data = Faq::where('id', $request->id);
        $faq = $data->first();

        $deleteData = $data->delete();

        return redirect()->route('admin.faq')->with([
            'message' => "Berhasil menghapus data FAQ"
        ]);
    }
    public function priority($id, $action) {
        $data = Faq::where('id', $id);
        $faq = $data->first();

        if ($action == "increase") {
            $data->increment('priority');
        } else {
            $data->decrement('priority');
        }

        return redirect()->route('admin.faq')->with([
            'message' => "Berhasil mengubah prioritas FAQ"
        ]);
    }
}
