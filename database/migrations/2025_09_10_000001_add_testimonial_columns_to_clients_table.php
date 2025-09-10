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
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'testimonial_author')) {
                $table->string('testimonial_author')->nullable()->after('testimonial');
            }
            if (!Schema::hasColumn('clients', 'testimonial_position')) {
                $table->string('testimonial_position')->nullable()->after('testimonial_author');
            }
            if (!Schema::hasColumn('clients', 'rating')) {
                $table->integer('rating')->nullable()->after('testimonial_position');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'rating')) {
                $table->dropColumn('rating');
            }
            if (Schema::hasColumn('clients', 'testimonial_position')) {
                $table->dropColumn('testimonial_position');
            }
            if (Schema::hasColumn('clients', 'testimonial_author')) {
                $table->dropColumn('testimonial_author');
            }
        });
    }
};
