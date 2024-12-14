<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function create() {
        return view('admin.news.create');
    }
    public function store(Request $request) {
        $image = $request->file('featured_image');
        $imageFileName = rand(1111, 9999) . "_" . $image->getClientOriginalName();
        $myData = me();

        $saveData = News::create([
            'admin_id' => $myData->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'featured_image' => $imageFileName,
            'hit' => 0,
            'status' => "PUBLISHED"
        ]);

        $image->move(
            public_path('storage/news_images'),
            $imageFileName,
        );

        return redirect()->route('admin.news')->with([
            'message' => "Berhasil menerbitkan berita"
        ]);
    }
    public function edit($id) {
        $post = News::where('id', $id)->first();

        return view('admin.news.edit', [
            'post' => $post
        ]);
    }
    public function update($id, Request $request) {
        $data = News::where('id', $request->id);
        $news = $data->first();
        $toUpdate = [
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'content' => $request->content,
        ];

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imageFileName = rand(1111, 9999) . "_" . $image->getClientOriginalName();
            $toUpdate['featured_image'] = $imageFileName;
            $deleteImage = Storage::delete('public/storage/news_images/' . $news->featured_image);
            $image->move(
                public_path('storage/news_images'),
                $imageFileName,
            );
        }

        $data->update($toUpdate);

        return redirect()->route('admin.news')->with([
            'message' => "Berhasil mengubah berita"
        ]);
    }
    public function delete(Request $request) {
        $data = News::where('id', $request->id);
        $news = $data->first();

        $deleteData = $data->delete();
        $deleteImage = Storage::delete('public/storage/news_images/' . $news->featured_image);
        
        return redirect()->route('admin.news')->with([
            'message' => "Berhasil menghapus berita"
        ]);
    }
    public function read($slug) {
        $data = News::where('slug', $slug);
        $news = $data->with(['admin'])->first();

        return view('read', [
            'news' => $news,
        ]);
    }
}
