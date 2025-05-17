<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public $currentRoute;
    public $headerTitle;

    public function __construct()
    {
        $this->currentRoute = request()->route()->getName();

        $titles = [
            'landingpage' => 'LandingPage',
            'dashboard' => 'Selamat Datang di Sistem Admnistrasi Desa Adat Jimbaran',
            'statistics' => 'Statistik',
            'settings' => 'Pengaturan',
            'input' => 'Input Data',
            'reports' => 'Reports',
            'database' => 'Database',
            'hosting' => 'Hosting',
            'statistics.krama-adat' => 'Data Statistik Krama Adat',
            'KKQ.index' => 'QUERY DATA KK-Q'
        ];

        $this->headerTitle = $titles[$this->currentRoute] ?? '';
    }

    public function isActive($route)
    {
        return $this->currentRoute === $route;
    }

    public function render(): View|Closure|string
    {
        return view('components.header');
    }
}
