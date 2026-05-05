<?php

namespace App\View\Composers;

use App\Models\MaintenanceTicket;
use App\Models\Reservation;
use Illuminate\View\View;

class NotificationBadgeComposer
{
    public function compose(View $view): void
    {
        $view->with([
            'pendingReservationsCount' => Reservation::where('status', 'pending')->count(),
            'urgentMaintenanceCount' => MaintenanceTicket::where('priority', 'urgent')
                ->whereIn('status', ['pending', 'in_progress'])
                ->count(),
        ]);
    }
}
