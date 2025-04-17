<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Change the language
     *
     * @param Request $request
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLang(Request $request, $locale)
    {
        // Validasi bahasa yang tersedia
        if (!in_array($locale, ['en', 'id'])) {
            $locale = 'id';
        }

        // Simpan bahasa yang dipilih dalam session
        Session::put('locale', $locale);
        
        // Redirect kembali ke halaman sebelumnya
        return redirect()->back();
    }
} 