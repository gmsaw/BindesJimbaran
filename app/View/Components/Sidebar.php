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
            'Cetakkartu' => ['icon' => 'chart-line', 'label' => 'Cetak Kartu'],
            'input' => ['icon' => 'users', 'label' => 'Input Data'],
            'reports' => ['icon' => 'file-alt', 'label' => 'Reports'],
            'settings' => ['icon' => 'cog', 'label' => 'Settings'],
        ];

        $this->toolsItems = [
            'database' => ['icon' => 'database', 'label' => 'Database'],
            'hosting' => ['icon' => 'server', 'label' => 'Hosting']
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
