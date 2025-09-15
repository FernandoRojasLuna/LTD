<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            // add new nullable subtitle column if not exists
            if (!Schema::hasColumn('contents', 'subtitle')) {
                $table->string('subtitle')->nullable()->after('title');
            }
        });

    // copy slug values into subtitle using a single query
    \DB::table('contents')->whereNotNull('slug')->update(['subtitle' => \DB::raw('slug')]);

        Schema::table('contents', function (Blueprint $table) {
            // drop unique index on slug and remove slug column if present
            if (Schema::hasColumn('contents', 'slug')) {
                try {
                    $table->dropUnique('contents_slug_unique');
                } catch (\Throwable $_) {
                    // ignore if index missing
                }
                $table->dropColumn('slug');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            // restore slug column (nullable to avoid conflicts)
            $table->string('slug')->nullable()->after('title');
        });

    // copy subtitle back to slug using a single query
    \DB::table('contents')->whereNotNull('subtitle')->update(['slug' => \DB::raw('subtitle')]);

        Schema::table('contents', function (Blueprint $table) {
            // restore unique index if desired
            try {
                $table->unique('slug');
            } catch (\Throwable $_) {
                // ignore
            }
            $table->dropColumn('subtitle');
        });
    }
};
