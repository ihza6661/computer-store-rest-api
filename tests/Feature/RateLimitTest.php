<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RateLimitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Prevent actual emails from being sent during tests
        Mail::fake();
    }

    /**
     * Test that public API endpoints have a 60 requests per minute limit.
     */
    public function test_public_api_has_rate_limit(): void
    {
        // Make 60 requests (should all succeed)
        for ($i = 0; $i < 60; $i++) {
            $response = $this->getJson('/api/products');
            $response->assertStatus(200);
            
            // Check rate limit headers are present
            $response->assertHeader('X-RateLimit-Limit');
            $this->assertEquals(60, $response->headers->get('X-RateLimit-Limit'));
        }

        // 61st request should be rate limited
        $response = $this->getJson('/api/products');
        $response->assertStatus(429);
        $response->assertHeader('Retry-After');
    }

    /**
     * Test that contact form has a strict 3 requests per minute limit.
     * This is the primary test case requested by the user.
     */
    public function test_contact_form_rate_limit_blocks_after_3_requests(): void
    {
        $contactData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'category' => 'general',
            'message' => 'This is a test message for rate limiting.',
        ];

        // Make 3 requests (should all succeed)
        for ($i = 1; $i <= 3; $i++) {
            $response = $this->postJson('/api/contacts', $contactData);
            $response->assertStatus(201);
            
            // Verify rate limit headers
            $this->assertEquals(3, $response->headers->get('X-RateLimit-Limit'));
            $this->assertEquals(3 - $i, $response->headers->get('X-RateLimit-Remaining'));
        }

        // 4th request should be blocked with 429 status
        $response = $this->postJson('/api/contacts', $contactData);
        $response->assertStatus(429);
        $response->assertJson([
            'message' => 'Too many contact form submissions. Please try again later.',
        ]);
        $response->assertHeader('Retry-After');
        
        // Verify remaining is 0
        $this->assertEquals(0, $response->headers->get('X-RateLimit-Remaining'));
    }

    /**
     * Test that login endpoint has rate limiting with dual protection (email + IP).
     * This test verifies the email-based rate limit (5 attempts per email).
     */
    public function test_login_rate_limit_blocks_after_5_attempts_per_email(): void
    {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ];

        // Make 5 login attempts (should all be processed, but fail authentication)
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->post('/login', $credentials);
            
            // Should redirect back with error (not blocked yet)
            $response->assertStatus(302);
            $response->assertSessionHasErrors(['email']);
            
            // Should not have rate limit error yet
            $errors = session('errors');
            if ($errors) {
                $emailErrors = $errors->get('email');
                $this->assertNotContains('Too many login attempts', $emailErrors);
            }
        }

        // 6th attempt should be rate limited
        $response = $this->post('/login', $credentials);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
        
        // Verify it's a rate limit error
        $errors = session('errors');
        $emailErrors = $errors->get('email');
        $this->assertStringContainsString('Too many login attempts', $emailErrors[0]);
    }

    /**
     * Test that login rate limit applies per email address.
     * Different emails should have independent rate limits.
     */
    public function test_login_rate_limit_is_per_email(): void
    {
        // Use up the rate limit for first email
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/login', [
                'email' => 'first@example.com',
                'password' => 'wrongpassword',
            ]);
            $response->assertStatus(302);
        }

        // Attempting with the same email should be blocked
        $response = $this->post('/login', [
            'email' => 'first@example.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(302);
        $errors = session('errors');
        $emailErrors = $errors->get('email');
        $this->assertStringContainsString('Too many login attempts', $emailErrors[0]);

        // But a different email should still work (up to its own limit)
        $response = $this->post('/login', [
            'email' => 'second@example.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(302);
        
        // Should have authentication error, not rate limit error
        $errors = session('errors');
        $emailErrors = $errors->get('email');
        $this->assertNotContains('Too many login attempts', $emailErrors);
    }

    /**
     * Test that IP-based login rate limit prevents distributed brute-force.
     * After 10 attempts from the same IP (across different emails), should be blocked.
     */
    public function test_login_rate_limit_has_ip_based_protection(): void
    {
        // Make 10 login attempts with different emails (IP limit is 10/min)
        for ($i = 1; $i <= 10; $i++) {
            $response = $this->post('/login', [
                'email' => "user{$i}@example.com",
                'password' => 'wrongpassword',
            ]);
            $response->assertStatus(302);
        }

        // 11th attempt should be blocked by IP limit
        $response = $this->post('/login', [
            'email' => 'user11@example.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
        
        $errors = session('errors');
        $emailErrors = $errors->get('email');
        $this->assertStringContainsString('Too many login attempts', $emailErrors[0]);
    }

    /**
     * Test that authenticated admin API routes have higher rate limits (120/min).
     * This test verifies authenticated users get more generous limits.
     */
    public function test_authenticated_api_has_higher_rate_limit(): void
    {
        // Create an admin user
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $this->actingAs($admin, 'web');

        // Make 120 authenticated requests (should all succeed)
        for ($i = 0; $i < 120; $i++) {
            $response = $this->getJson('/api/admin/contacts');
            $response->assertStatus(200);
            
            // Check that rate limit is 120 (not 60 like public API)
            $this->assertEquals(120, $response->headers->get('X-RateLimit-Limit'));
        }

        // 121st request should be rate limited
        $response = $this->getJson('/api/admin/contacts');
        $response->assertStatus(429);
        $response->assertHeader('Retry-After');
    }

    /**
     * Test that product import endpoints have very high rate limits (600/min).
     * This is critical for bulk operations and admin workflows.
     */
    public function test_product_import_has_high_rate_limit(): void
    {
        // Create an admin user
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $this->actingAs($admin, 'web');

        // The import status endpoint is easy to test repeatedly
        // We'll test with a non-existent job ID (it will return an error but won't be rate limited)
        
        // Make 600 requests (should all be processed without hitting rate limit)
        for ($i = 0; $i < 600; $i++) {
            $response = $this->getJson('/admin/products/import/status/test-job-id');
            
            // Should not be rate limited (status code should be 200 or 404, not 429)
            $this->assertNotEquals(429, $response->status(), 
                "Request {$i} was rate limited unexpectedly at {$i} requests");
                
            // Verify rate limit headers show high limit
            if ($i === 0) {
                $this->assertEquals(600, $response->headers->get('X-RateLimit-Limit'),
                    'Import endpoint should have 600/min rate limit');
            }
        }

        // 601st request should be rate limited (proving the limit exists and is 600)
        $response = $this->getJson('/admin/products/import/status/test-job-id');
        $this->assertEquals(429, $response->status(), 
            'Import endpoint should be rate limited after 600 requests');
    }

    /**
     * Test that rate limit headers are properly set on all API responses.
     */
    public function test_rate_limit_headers_are_present(): void
    {
        $response = $this->getJson('/api/products');
        
        $response->assertStatus(200);
        $response->assertHeader('X-RateLimit-Limit');
        $response->assertHeader('X-RateLimit-Remaining');
        
        // Initial request should show full limit available
        $limit = $response->headers->get('X-RateLimit-Limit');
        $remaining = $response->headers->get('X-RateLimit-Remaining');
        
        $this->assertEquals(60, $limit);
        $this->assertEquals(59, $remaining); // One request consumed
    }

    /**
     * Test that different IPs have independent rate limits for public endpoints.
     */
    public function test_rate_limits_are_per_ip_for_public_endpoints(): void
    {
        // Make 60 requests from first IP
        for ($i = 0; $i < 60; $i++) {
            $response = $this->getJson('/api/products');
            $response->assertStatus(200);
        }

        // 61st request from same IP should be blocked
        $response = $this->getJson('/api/products');
        $response->assertStatus(429);

        // But a request from a different IP should work
        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.100'])
            ->getJson('/api/products');
        $response->assertStatus(200);
    }

    /**
     * Test that authenticated users' rate limits are based on user ID, not IP.
     * This ensures multiple admins on the same network don't share limits.
     */
    public function test_authenticated_rate_limits_are_per_user(): void
    {
        $admin1 = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);

        // Admin 1 uses their full rate limit
        $this->actingAs($admin1, 'web');
        for ($i = 0; $i < 120; $i++) {
            $response = $this->getJson('/api/admin/contacts');
            $response->assertStatus(200);
        }

        // Admin 1's next request should be blocked
        $response = $this->getJson('/api/admin/contacts');
        $response->assertStatus(429);

        // But Admin 2 should have their own independent rate limit
        $this->actingAs($admin2, 'web');
        $response = $this->getJson('/api/admin/contacts');
        $response->assertStatus(200);
        
        // Verify Admin 2 has full rate limit available
        $this->assertEquals(120, $response->headers->get('X-RateLimit-Limit'));
        $this->assertEquals(119, $response->headers->get('X-RateLimit-Remaining'));
    }

    /**
     * Test that 429 responses include proper error messages and headers.
     */
    public function test_rate_limit_exceeded_response_format(): void
    {
        $contactData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'category' => 'general',
            'message' => 'This is a test message.',
        ];

        // Exhaust the rate limit
        for ($i = 0; $i < 3; $i++) {
            $this->postJson('/api/contacts', $contactData);
        }

        // Get rate limited response
        $response = $this->postJson('/api/contacts', $contactData);
        
        $response->assertStatus(429);
        $response->assertHeader('X-RateLimit-Limit', '3');
        $response->assertHeader('X-RateLimit-Remaining', '0');
        $response->assertHeader('Retry-After');
        
        // Verify retry-after is a reasonable number (between 1 and 60 seconds)
        $retryAfter = $response->headers->get('Retry-After');
        $this->assertGreaterThan(0, $retryAfter);
        $this->assertLessThanOrEqual(60, $retryAfter);
    }

    /**
     * Test that categories endpoint also respects the public API rate limit.
     */
    public function test_categories_endpoint_has_rate_limit(): void
    {
        // Make 60 requests
        for ($i = 0; $i < 60; $i++) {
            $response = $this->getJson('/api/categories');
            $response->assertStatus(200);
        }

        // 61st should be limited
        $response = $this->getJson('/api/categories');
        $response->assertStatus(429);
    }

    /**
     * Test that product detail endpoint respects rate limits.
     */
    public function test_product_detail_endpoint_has_rate_limit(): void
    {
        // Make 60 requests (product doesn't need to exist for rate limit test)
        for ($i = 0; $i < 60; $i++) {
            $response = $this->getJson('/api/products/999');
            // Will return 404 but still counts against rate limit
            $this->assertContains($response->status(), [200, 404]);
        }

        // 61st should be rate limited
        $response = $this->getJson('/api/products/999');
        $response->assertStatus(429);
    }
}
