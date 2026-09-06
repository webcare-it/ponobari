<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ApplyOrderErrorBypass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:error-bypass {--apply-all : Apply error bypass to all order blade files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply error bypass mechanism to admin order blade files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Applying error bypass to admin order blade files...');
        
        // Get all order blade files
        $orderBladeFiles = [
            'resources/views/admin/customer/order-list.blade.php',
            'resources/views/admin/customer/order-pending.blade.php',
            'resources/views/admin/customer/deleted-orders.blade.php',
            'resources/views/admin/customer/delivery-order-list.blade.php',
            'resources/views/admin/customer/order-cancel.blade.php',
            'resources/views/admin/customer/order-website-list.blade.php',
            'resources/views/admin/customer/invoice_orders.blade.php',
            'resources/views/admin/customer/order-hold-today-list.blade.php',
            'resources/views/admin/customer/pending-payment-order-list.blade.php',
            'resources/views/admin/customer/order-manual-list.blade.php',
            'resources/views/admin/customer/order-missing.blade.php',
            'resources/views/admin/customer/order-hold.blade.php',
            'resources/views/admin/customer/order-damage.blade.php',
            'resources/views/admin/customer/user-assign-orders.blade.php',
            'resources/views/admin/customer/order-return.blade.php',
            'resources/views/admin/customer/order-today-list.blade.php',
            'resources/views/admin/user/order_list.blade.php'
        ];
        
        $updatedCount = 0;
        
        foreach ($orderBladeFiles as $filePath) {
            $fullPath = base_path($filePath);
            
            if (!File::exists($fullPath)) {
                $this->warn("File not found: {$filePath}");
                continue;
            }
            
            $content = File::get($fullPath);
            
            // Check if error bypass is already applied
            if (strpos($content, 'error-bypass') !== false) {
                $this->line("Skipping (already has error bypass): {$filePath}");
                continue;
            }
            
            // Add error bypass include after the action-css include
            $content = str_replace(
                '@include(\'admin.includes.action-css\')',
                '@include(\'admin.includes.action-css\')' . "\n" . '@include(\'admin.includes.error-bypass\')',
                $content
            );
            
            File::put($fullPath, $content);
            $this->info("Updated: {$filePath}");
            $updatedCount++;
        }
        
        $this->info("Successfully updated {$updatedCount} order blade files with error bypass mechanism.");
        $this->info("Remember to manually update the data access patterns in each file for full protection.");
        
        return 0;
    }
}