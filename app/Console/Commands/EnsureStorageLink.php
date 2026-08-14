<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class EnsureStorageLink extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:ensure-storage-link';

    /**
     * The console command description.
     */
    protected $description = 'Ensure Laravel public storage link exists';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $link = public_path('storage');

        // ถ้ามี path อยู่แล้ว ให้ถือว่า storage link มีอยู่แล้ว
        if (file_exists($link) || is_link($link)) {
            $this->info('Storage link already exists.');

            return self::SUCCESS;
        }

        Artisan::call('storage:link');

        $this->info('Storage link created successfully.');

        return self::SUCCESS;
    }
}