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
        Schema::create('kindergartens', function (Blueprint $table) {
            $table->id();
            $table->string('name_zh_tw');
            $table->string('name_zh_cn');
            $table->string('name_en');
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->string('address_zh_tw');
            $table->string('address_zh_cn');
            $table->string('address_en');
            $table->string('website_url')->nullable();
            $table->boolean('has_pn_class')->default(false);
            $table->boolean('has_k1')->default(true);
            $table->boolean('has_k2')->default(true);
            $table->boolean('has_k3')->default(true);
            $table->decimal('primary_success_rate', 5, 2)->nullable()->comment('Percentage of students going to good primary schools');
            $table->integer('ranking_score')->default(0)->comment('Overall ranking score 0-100');
            $table->text('description_zh_tw')->nullable();
            $table->text('description_zh_cn')->nullable();
            $table->text('description_en')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('principal_name')->nullable();
            $table->integer('established_year')->nullable();
            $table->enum('school_type', ['private', 'non_profit', 'government'])->default('private');
            $table->decimal('monthly_fee_min', 10, 2)->nullable();
            $table->decimal('monthly_fee_max', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for search optimization
            $table->index('ranking_score');
            $table->index('primary_success_rate');
            $table->index(['has_pn_class', 'has_k1', 'has_k2', 'has_k3']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kindergartens');
    }
};
