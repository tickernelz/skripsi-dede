<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $berita = Post::paginate(5);
        $pengaturan = Pengaturan::first();
        return view('home.index', compact('user', 'berita', 'pengaturan'));
    }

    public function detail($slug, $id)
    {
        $user = Auth::user();
        $berita = Post::findOrFail($id);
        return view('home.detail', compact('user', 'berita'));
    }
}
