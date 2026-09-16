<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Product prices (unit_price) are stored VAT-inclusive, but both VAT
     * labels read "Tax excluded" / "غير شامل الضريبه" on the storefront.
     * Point them at the truth on the product card, product page and cart total.
     */
    private array $labels = [
        'including_vat' => ['sa' => 'شامل الضريبة', 'en' => 'VAT included'],
        'inclusive_of_vat' => ['sa' => 'شامل الضريبة', 'en' => 'VAT included'],
    ];

    public function up(): void
    {
        $now = now();

        foreach ($this->labels as $key => $values) {
            foreach ($values as $lang => $value) {
                $exists = DB::table('translations')->where('lang', $lang)->where('lang_key', $key)->exists();

                if ($exists) {
                    DB::table('translations')->where('lang', $lang)->where('lang_key', $key)
                        ->update(['lang_value' => $value, 'updated_at' => $now]);
                } else {
                    DB::table('translations')->insert([
                        'lang' => $lang,
                        'lang_key' => $key,
                        'lang_value' => $value,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }

        foreach (['sa', 'en'] as $lang) {
            Cache::forget('translations-' . $lang);
        }
    }

    public function down(): void
    {
        // The previous values were wrong; nothing to restore.
    }
};
