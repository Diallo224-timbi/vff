<?php

namespace Tests\Feature;

use Tests\TestCase;

class GlobalPlatformTest extends TestCase
{
    /**
     * Test des pages publiques.
     */
    public function test_public_pages_are_accessible(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
        $this->get('/forgot-password')->assertStatus(200);
        $this->get('/charte')->assertStatus(200);
        $this->get('/sitemap.xml')->assertStatus(200);
    }

    /**
     * Test des pages protégées.
     */
    public function test_protected_pages_require_authentication(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/dashboardUser')->assertRedirect('/login');

        $this->get('/annuaire')->assertRedirect('/login');
        $this->get('/organismes')->assertRedirect('/login');
        $this->get('/structures')->assertRedirect('/login');

        $this->get('/forum')->assertRedirect('/login');
        $this->get('/ressources')->assertRedirect('/login');
        $this->get('/events')->assertRedirect('/login');

        $this->get('/categories')->assertRedirect('/login');
        $this->get('/profile')->assertRedirect('/login');
    }
} 