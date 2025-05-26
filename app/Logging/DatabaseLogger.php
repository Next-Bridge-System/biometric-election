<?php

namespace App\Logging;

use Illuminate\Support\Facades\Auth;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Illuminate\Support\Facades\DB;

class DatabaseLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('database');
        $logger->pushHandler(new DatabaseHandler());
        return $logger;
    }
}

class DatabaseHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {        
        $admin_id = 0;
        $user_id = 0;

        if (Auth::guard('admin')->user()) {
            $admin_id = Auth::guard('admin')->user()->id;
        }
        
        if (Auth::guard('frontend')->user()) {
            $user_id = Auth::guard('frontend')->user()->id;
        }

        DB::table('error_logs')->insert([
            'admin_id' => $admin_id,
            'user_id' => $user_id,
            'channel' => $record['channel'],
            'message' => $record['message'],
            'level' => $record['level'],
            'level_name' => $record['level_name'],
            'unix_time' => $record['datetime']->format('U'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
