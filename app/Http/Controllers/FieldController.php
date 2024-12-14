<?php

namespace App\Http\Controllers;

use App\Models\StudentField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FieldController extends Controller
{
    public function store(Request $request) {
        $options = "";
        if ($request->type == "SELECT") {
            $options = implode("||", $request->options);
        }

        $saveData = StudentField::create([
            'key' => Str::slug($request->label, '_'),
            'label' => $request->label,
            'type' => $request->type,
            'required' => $request->required,
            'options' => $options,
            'priority' => 0,
        ]);

        return redirect()->route('admin.settings.field')->with([
            'message' => "Berhasil menambahkan kolom isian baru"
        ]);
    }
    public function required($id) {
        $data = StudentField::where('id', $id);
        $field = $data->first();

        $data->update([
            'required' => !$field->required,
        ]);

        return redirect()->route('admin.settings.field');
    }
}
