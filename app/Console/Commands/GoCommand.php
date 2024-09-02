<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class GoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'go';

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
        Product::create([

                'name' => 'tomato',
                'price' => 35,
              ]);
    }
}
