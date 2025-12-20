<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class DeleteProductByName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-product-by-name {productName : The name of the product to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a product from the database by its name.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productName = $this->argument('productName');

        $product = Product::where('name', $productName)->first();

        if ($product) {
            $product->delete();
            $this->info("Product '{$productName}' deleted successfully.");
        } else {
            $this->error("Product '{$productName}' not found.");
        }
    }
}
