<?php

namespace Tests\Feature;

use App\Models\security\RateLimitBlock;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RateLimitBlockTest extends TestCase
{
    public function test_can_create_block()
    {
        $block = RateLimitBlock::create([
            'key' => '192.168.1.1',
            'level' => 1,
            'blocked_until' => Carbon::now()->addMinutes(60),
            'active' => true,
        ]);

        $this->assertDatabaseHas('rate_limit_blocks', [
            'key' => '192.168.1.1',
            'level' => 1,
            'active' => true,
        ]);
    }

    public function test_can_deactivate_block()
    {
        $block = RateLimitBlock::create([
            'key' => '192.168.1.1',
            'active' => true,
        ]);

        $block->update(['active' => false]);

        $this->assertDatabaseHas('rate_limit_blocks', [
            'key' => '192.168.1.1',
            'active' => false,
        ]);
    }
}
