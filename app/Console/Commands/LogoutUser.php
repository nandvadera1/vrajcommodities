<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
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
            ->where('subcription_end', '<', $currentDate)
            ->inRandomOrder()
            ->limit(10)
            ->get();

        foreach ($users as $user) {
            
            $token = $user->token;
             
            if (!$token || count(explode('.', $token)) !== 3) {
                $this->error("Invalid token for user ID: {$user->id}");
                continue;
            }
             
            try {
                JWTAuth::setToken($token)->invalidate();
                $this->info("Token invalidated for user ID: {$user->id}");
            } catch (TokenExpiredException $e) {
                $this->warn("Token already expired for user ID: {$user->id}");
                // Optionally, you can log the error or perform other actions here
            } catch (\Exception $e) {
                $this->error("Failed to invalidate token for user ID: {$user->id}: {$e->getMessage()}");
            }
            // JWTAuth::setToken($token)->invalidate();
        }
    }
}
