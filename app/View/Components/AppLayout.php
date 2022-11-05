<?php
declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Class AppLayout
 * @package App\View\Components
 */
class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return Factory|View
     */
    public function render()
    {
        return view('layouts.app');
    }
}
