<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public $colortext;
    public $colorbg;

    public function __construct($colortext = 'blue', $colorbg = 'green')
    {
        $this->colortext = $colortext;
        $this->colorbg = $colorbg;
    }

    public function render(): View|Closure|string
    {
        return view('components.alert');
    }

    public function peligro()
    {
        if ($this->colorbg == 'red') {
            return "!!!!!!!!!!!!!PELIGRO!!!!!!!!!!!!!!!";
        }

        return "";
    }
}
