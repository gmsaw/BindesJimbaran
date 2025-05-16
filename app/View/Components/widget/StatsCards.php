<?php

namespace App\View\Components\widget;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatsCards extends Component
{
    /**
     * Create a new component instance.
     */
     public $totalPenduduk;
     public $kramaAdat;
     public $kramaTamiu;
     public $tamiu;

    public function __construct($totalPenduduk, $kramaAdat, $kramaTamiu, $tamiu)
    {
        $this->totalPenduduk = $totalPenduduk;
        $this->kramaAdat = $kramaAdat;
        $this->kramaTamiu= $kramaTamiu;
        $this->tamiu= $tamiu;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.widget.stats-cards');
    }
}
