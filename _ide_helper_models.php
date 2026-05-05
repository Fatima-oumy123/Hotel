<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property-read string $action_icon
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog query()
 */
	class AuditLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read string $full_name
 * @property-read string $status_badge
 * @property-read int $years_of_service
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee withoutTrashed()
 */
	class Employee extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\User|null $approver
 * @property-read string $formatted_amount
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense query()
 */
	class Expense extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read string|null $download_url
 * @property-read \App\Models\Reservation|null $reservation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 */
	class Invoice extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\User|null $assignee
 * @property-read string $priority_color
 * @property-read \App\Models\User|null $reporter
 * @property-read \App\Models\Room|null $room
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceTicket query()
 */
	class MaintenanceTicket extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read string $method_label
 * @property-read string $status_badge
 * @property-read \App\Models\Reservation|null $reservation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read mixed $guest_full_name
 * @property-read mixed $nights
 * @property-read string $status_badge
 * @property-read \App\Models\Invoice|null $invoice
 * @property-read \App\Models\Payment|null $payment
 * @property-read \App\Models\Room|null $room
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation withoutTrashed()
 */
	class Reservation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\User|null $cashier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantSale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantSale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantSale query()
 */
	class RestaurantSale extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaintenanceTicket> $maintenanceTickets
 * @property-read int|null $maintenance_tickets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reservation> $reservations
 * @property-read int|null $reservations_count
 * @property-read \App\Models\RoomType|null $roomType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room query()
 */
	class Room extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read string $formatted_price
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Room> $rooms
 * @property-read int|null $rooms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SeasonalRate> $seasonalRates
 * @property-read int|null $seasonal_rates_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType query()
 */
	class RoomType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\RoomType|null $roomType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeasonalRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeasonalRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeasonalRate query()
 */
	class SeasonalRate extends \Eloquent {}
}

