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

        $this->headerTitle = [
            'dashboard' => ['title' => 'Dashboard Overview'],
            'analytics' => ['title' => 'Analytics'],
            'settings' => ['title' => 'Settings'],
            'users' => ['title' => 'Users'],
            'reports' => ['title' => 'Reports'],
            'database' => ['title' => 'Database'],
            'hosting' => ['title' => 'Hosting']
        ];
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
