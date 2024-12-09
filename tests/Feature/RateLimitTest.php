<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class RateLimitTest extends TestCase
{
    public function test_rate_limit_allows_initial_requests()
    {
        RateLimiter::clear('api');

        for ($i = 0; $i < 100; $i++) {
            $response = $this->get('/api/user');
            $response->assertStatus(200);
        }
    }

    public function test_rate_limit_blocks_after_exceeding_limit()
    {
        RateLimiter::clear('api');

        for ($i = 0; $i < 50; $i++) {
            $response = $this->get('/api/user');
        }

        $response->assertStatus(429)
            ->assertJson([
                'success' => false,
                'message' => 'Too many requests. You are blocked for 1 minutes.',
            ]);
    }
}
