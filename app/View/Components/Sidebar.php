<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public $currentRoute;
    public $menuItems;
    public $toolsItems;
    public $searchPlaceholder;

    public function __construct()
    {
        $this->currentRoute = request()->route()->getName();
        $this->searchPlaceholder = 'Search menu...';

        $this->menuItems = [
            'dashboard' => ['icon' => 'home', 'label' => 'Dashboard'],
            'statistics' => ['icon' => 'chart-line', 'label' => 'Statistics'],
            'cetakkartu' => ['icon' => 'print', 'label' => 'Cetak Kartu'],
            'kependudukan.create.options' => ['icon' => 'users', 'label' => 'Input Data'],
            'surat.indexMain' => ['icon' => 'file-text', 'label' => 'Surat Menyurat'],
//            'reports' => ['icon' => 'file-alt', 'label' => 'Reports'],
//            'settings' => ['icon' => 'cog', 'label' => 'Settings'],
        ];

        $this->toolsItems = [
            'master-data.index' => ['icon' => 'database', 'label' => 'Manajemen Data'],
            'statistik.index' => ['icon' => 'chart-bar', 'label' => 'Statistik Penduduk'],
            'export.index' => ['icon' => 'file-export', 'label' => 'Export Data Penduduk'],
//            'hosting' => ['icon' => 'server', 'label' => 'Hosting']
        ];
    }

    public function isActive($route)
    {
        return $this->currentRoute === $route;
    }

    public function render()
    {
        return view('components.sidebar');
    }
}
