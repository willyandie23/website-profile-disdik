<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        return view('frontend.news.index', [
            'submenu' => false,
            'navbar' => true,
            'footer' => true
        ]);
    }

    public function show($id)
    {
        $news = News::find($id);

        if (!$news) {
            return redirect()->route('news.index')->with('error', 'Berita tidak ditemukan.');
        }

        return view('frontend.news.show', compact('news'),[
            'submenu' => false,
            'navbar' => true,
            'footer' => true
        ]);
    }
}
