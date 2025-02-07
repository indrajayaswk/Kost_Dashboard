<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Complaint;

class Sidebar2 extends Component
{
    public $pendingComplaints;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Fetch the count of pending complaints
        $this->pendingComplaints = Complaint::where('status', 'pending')->count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar2', [
            'pendingComplaints' => $this->pendingComplaints
        ]);
    }
}
