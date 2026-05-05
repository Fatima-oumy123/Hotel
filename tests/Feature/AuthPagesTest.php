<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthPagesTest extends TestCase
{
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('Connexion');
    }

    public function test_forgot_password_page_is_accessible(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
        $response->assertSee('Mot de passe oublié');
    }

    public function test_reset_password_page_is_accessible(): void
    {
        $response = $this->get(route('password.reset', ['token' => 'test-token']));

        $response->assertOk();
        $response->assertSee('nouveau mot de passe', false);
    }
}
