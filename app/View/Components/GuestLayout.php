<?php
declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Class GuestLayout
 * @package App\View\Components
 */
class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return Factory|View
     */
    public function render()
    {
        return view('layouts.guest');
    }
}
