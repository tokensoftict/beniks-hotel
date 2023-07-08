<?php

namespace App\View\Components;

use Illuminate\View\Component;

class laundryReportComponent extends Component
{
    public $invoices;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $invoices = $this->invoices;
        return view('components.laundry-report-component', compact('invoices'));
    }
}
