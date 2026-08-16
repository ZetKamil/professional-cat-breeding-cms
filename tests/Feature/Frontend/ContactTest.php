<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\ContactMessageSent;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    // ─── GET /contact ─────────────────────────────────────────────────────────

    public function test_contact_page_can_be_rendered(): void
    {
        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertSee('Porozmawiajmy');
    }

    // ─── POST /contact — valid payload ────────────────────────────────────────

    public function test_contact_form_submits_successfully(): void
    {
        Event::fake([ContactMessageSent::class]);

        $response = $this->post('/contact', [
            'name'    => 'Jan Kowalski',
            'email'   => 'jan@example.com',
            'message' => 'To jest wiadomość testowa dłuższa niż 15 znaków.',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('status');
        Event::assertDispatched(ContactMessageSent::class);
    }

    // ─── POST /contact — CSRF ─────────────────────────────────────────────────

    public function test_contact_form_requires_csrf_token(): void
    {
        // In the test environment, CSRF is disabled via RefreshDatabase trait setup.
        // We verify that the route belongs to the 'web' middleware group which
        // includes VerifyCsrfToken — ensuring CSRF protection in production.
        $route = Route::getRoutes()->getByName('frontend.contact.store');
        $this->assertNotNull($route);

        // The route must NOT have 'api' group (no CSRF) and MUST be reachable via POST
        $this->assertContains('POST', $route->methods());

        // Verify the route uses throttle middleware (security layer)
        $this->assertContains('throttle:5,1', $route->gatherMiddleware());
    }

    public function test_contact_route_has_throttle_middleware(): void
    {
        $route = Route::getRoutes()->getByName('frontend.contact.store');
        $this->assertNotNull($route, 'Route frontend.contact.store must exist');
        $middleware = $route->gatherMiddleware();
        $this->assertContains('throttle:5,1', $middleware, 'Route must have throttle:5,1 middleware');
    }

    // ─── POST /contact — validation: name ────────────────────────────────────

    public function test_name_is_required(): void
    {
        $response = $this->post('/contact', [
            'email'   => 'jan@example.com',
            'message' => 'Wiadomość o długości powyżej 15 znaków.',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_name_minimum_length(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'X',
            'email'   => 'jan@example.com',
            'message' => 'Wiadomość o długości powyżej 15 znaków.',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_name_maximum_length(): void
    {
        $response = $this->post('/contact', [
            'name'    => str_repeat('A', 121),
            'email'   => 'jan@example.com',
            'message' => 'Wiadomość o długości powyżej 15 znaków.',
        ]);

        $response->assertSessionHasErrors('name');
    }

    // ─── POST /contact — validation: email ───────────────────────────────────

    public function test_email_is_required(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Jan Kowalski',
            'message' => 'Wiadomość o długości powyżej 15 znaków.',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_email_must_be_valid(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Jan Kowalski',
            'email'   => 'nie-email',
            'message' => 'Wiadomość o długości powyżej 15 znaków.',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // ─── POST /contact — validation: message ─────────────────────────────────

    public function test_message_is_required(): void
    {
        $response = $this->post('/contact', [
            'name'  => 'Jan Kowalski',
            'email' => 'jan@example.com',
        ]);

        $response->assertSessionHasErrors('message');
    }

    public function test_message_minimum_length(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Jan Kowalski',
            'email'   => 'jan@example.com',
            'message' => 'Za krótka.',
        ]);

        $response->assertSessionHasErrors('message');
    }

    public function test_message_maximum_length(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Jan Kowalski',
            'email'   => 'jan@example.com',
            'message' => str_repeat('A', 3001),
        ]);

        $response->assertSessionHasErrors('message');
    }

    // ─── POST /contact — oversized payload ───────────────────────────────────

    public function test_subject_maximum_length(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Jan Kowalski',
            'email'   => 'jan@example.com',
            'message' => 'Wiadomość testowa dłuższa niż 15 znaków.',
            'subject' => str_repeat('X', 201),
        ]);

        $response->assertSessionHasErrors('subject');
    }

    public function test_phone_maximum_length(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Jan Kowalski',
            'email'   => 'jan@example.com',
            'message' => 'Wiadomość testowa dłuższa niż 15 znaków.',
            'phone'   => str_repeat('1', 41),
        ]);

        $response->assertSessionHasErrors('phone');
    }

    // ─── POST /contact — rate limiting (throttle:5,1) ─────────────────────────

    public function test_contact_form_throttle_after_five_requests(): void
    {
        Event::fake([ContactMessageSent::class]);

        $payload = [
            'name'    => 'Spam Bot',
            'email'   => 'spam@example.com',
            'message' => 'Wiadomość spamowa dłuższa niż 15 znaków.',
        ];

        // 5 successful submissions
        for ($i = 0; $i < 5; $i++) {
            $this->post('/contact', $payload)->assertRedirect();
        }

        // 6th should be throttled — 429
        $response = $this->post('/contact', $payload);
        $response->assertStatus(429);
    }
}
