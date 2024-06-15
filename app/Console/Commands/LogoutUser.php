<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class LogoutUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:logout-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = Carbon::now()->format('Y-m-d');

        $users = User::where('role_id', 2)
            ->whereDate('subscription_end', '<', $currentDate)
            ->limit(10)
            ->get();

        foreach ($users as $user) {
            // Invalidate the user's token
            JWTAuth::invalidate(JWTAuth::fromUser($user));
        }
        
    }
}
