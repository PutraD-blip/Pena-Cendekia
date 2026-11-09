<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BookCategory;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Halaman Utama (Home)
     */
    public function index()
    {
        $categories = BookCategory::where('is_active', true)->get();
        return view('public.home', compact('categories'));
    }

    /**
     * Halaman Unduhan / Syarat Ketentuan
     */
    public function terms()
    {
        return view('public.terms');
    }

    /**
     * Halaman Tentang Kami
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Halaman Kontak & Rekening Resmi
     */
    public function contact()
    {
        $accounts = PaymentAccount::where('is_active', true)->get();
        return view('public.contact', compact('accounts'));
    }

    public function templateBuku()
    {
        return view('public.template-buku');
    }

    public function templateHaki()
    {
        return view('public.template-haki');
    }

    public function portofolio()
    {
        $books = \App\Http\Controllers\PublicController::getPortfolioBooksList();
        $categories = BookCategory::where('is_active', true)->get();
        return view('public.portofolio', compact('books', 'categories'));
    }
}
