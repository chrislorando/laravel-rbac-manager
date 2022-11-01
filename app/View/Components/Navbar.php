<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Menu;

class Navbar extends Component
{
    public $items;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($items)
    {
        if(count($items) > 0){
            $this->items = $items;
        }else{
            $this->items = Menu::get();
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.navbar');
    }
}
