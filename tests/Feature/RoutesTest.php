<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_types_show_route_is_not_registered(): void
    {
        $this->assertFalse(Route::has('room-types.show'));
    }

    public function test_homepage_is_accessible(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Réserver maintenant');
    }

    public function test_guest_reservation_entry_page_is_accessible(): void
    {
        $response = $this->get(route('guest.step1'));

        $response->assertOk();
    }
}
