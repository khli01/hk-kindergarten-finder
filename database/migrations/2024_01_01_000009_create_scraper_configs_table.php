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
        Schema::create('scraper_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kindergarten_id')->constrained()->onDelete('cascade');
            $table->string('target_url');
            $table->string('deadline_selector')->nullable()->comment('CSS selector for deadline info');
            $table->string('date_format')->default('Y-m-d')->comment('Expected date format on target');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_scraped_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scraper_configs');
    }
};
