<?php

namespace App\Console\Commands;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Items older than 15 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateThreshold = Carbon::now()->subDays(15);

        $items = Item::where('created_at', '<', $dateThreshold)->limit(10)->get();

        if (!empty($items) && count($items) > 0) {
            foreach ($items as $item) {
                // Check and delete the image file if it exists
                if (!empty($item->image)) {
                    $imagePath = public_path('image/' . $item->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                // Check and delete the pdf file if it exists
                if (!empty($item->pdf)) {
                    $pdfPath = public_path('pdf/' . $item->pdf);
                    if (file_exists($pdfPath)) {
                        unlink($pdfPath);
                    }
                }

                // Check and delete the excel file if it exists
                if (!empty($item->excel)) {
                    $excelPath = public_path('excel/' . $item->excel);
                    if (file_exists($excelPath)) {
                        unlink($excelPath);
                    }
                }

                // Delete the item from the database
                $item->delete();
            }
        }
    }
}
