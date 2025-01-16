<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Security\LogService;

class LogLoginLogoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $action;

    public function __construct($action)
    {
        $this->action = $action;
    }

    public function handle()
    {
        dispatch(new LogLoginLogoutJob('Login'));
        ;
    }
}

