<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing promos that might have null or 0 values
        DB::table('promos')->get()->each(function ($promo) {
            $updates = [];
            
            // If name is empty, use code as name
            if (empty($promo->name)) {
                $updates['name'] = $promo->code;
            }
            
            // If value is 0 or null, set default based on type
            if ($promo->value == 0 || is_null($promo->value)) {
                $updates['value'] = $promo->type === 'percentage' ? 10 : 50000;
            }
            
            // Update if there are changes
            if (!empty($updates)) {
                DB::table('promos')
                    ->where('id', $promo->id)
                    ->update($updates);
            }
        });
    }

    public function down(): void
    {
        // No rollback needed
    }
};
