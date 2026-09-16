<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The storefront shows products.unit_price, which for most products is the
     * stock price + 15% VAT (e.g. 800 -> 920). The cart and checkout charge the
     * stock price plus the product's product_taxes rows, and those were all
     * "amount 0", so customers were charged 800 with no VAT.
     *
     * Give every product whose unit_price is its stock price x 1.15 a 15% VAT
     * row, so cart, checkout and orders total exactly the price on the page.
     * Products whose unit_price equals the stock price are left at 0.
     */
    private const VAT_PERCENT = 15;

    public function up(): void
    {
        $taxId = DB::table('taxes')->where('tax_status', 1)->orderBy('id')->value('id')
            ?? DB::table('taxes')->orderBy('id')->value('id');

        if (!$taxId) {
            return;
        }

        $now = now();
        $ratio = 1 + self::VAT_PERCENT / 100;

        $products = DB::table('products')
            ->select('products.id', 'products.unit_price')
            ->selectSub(
                DB::table('product_stocks')->select('price')->whereColumn('product_stocks.product_id', 'products.id')->orderBy('id')->limit(1),
                'stock_price'
            )
            ->get();

        foreach ($products as $product) {
            if (!$product->stock_price || $product->stock_price <= 0) {
                continue;
            }

            if (abs($product->unit_price / $product->stock_price - $ratio) > 0.002) {
                continue;
            }

            // Taxes are summed per product, so keep exactly one row or VAT would stack.
            DB::table('product_taxes')->where('product_id', $product->id)->delete();
            DB::table('product_taxes')->insert([
                'product_id' => $product->id,
                'tax_id' => $taxId,
                'tax' => self::VAT_PERCENT,
                'tax_type' => 'percent',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('product_taxes')
            ->where('tax_type', 'percent')
            ->where('tax', self::VAT_PERCENT)
            ->update(['tax' => 0, 'tax_type' => 'amount', 'updated_at' => now()]);
    }
};
