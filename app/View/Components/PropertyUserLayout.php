<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PropertyUserLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Constructor bisa digunakan untuk dependency injection jika diperlukan
    }

    /**
     * Get the view / contents that represent the component.
     *
     * PERBAIKAN: Dengan menghapus metode render(), Laravel akan secara otomatis
     * mencari dan menggunakan file view di:
     * 'resources/views/components/property-user-layout.blade.php'
     * Ini adalah cara standar dan akan menghindari konflik.
     */
    public function render(): View
    {
<<<<<<< HEAD
        // Pastikan baris ini mengarah ke 'layouts.app'
        return view('layouts.app'); 
=======
        return view('layouts.property_user');
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    }
}
