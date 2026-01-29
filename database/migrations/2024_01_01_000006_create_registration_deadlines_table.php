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
        Schema::create('registration_deadlines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kindergarten_id')->constrained()->onDelete('cascade');
            $table->string('academic_year')->comment('e.g., 2025-2026');
            $table->enum('event_type', [
                'application_start',    // 申請開始
                'application_deadline', // 申請截止
                'interview',            // 面試
                'result_announcement',  // 結果公佈
                'registration',         // 註冊
                'open_day',             // 開放日
                'briefing_session',     // 簡介會
                'other'                 // 其他
            ]);
            $table->date('deadline_date');
            $table->time('deadline_time')->nullable();
            $table->text('notes_zh_tw')->nullable();
            $table->text('notes_zh_cn')->nullable();
            $table->text('notes_en')->nullable();
            $table->string('source_url')->nullable()->comment('URL where information was found');
            $table->boolean('is_scraped')->default(false)->comment('Whether this was auto-scraped');
            $table->boolean('is_verified')->default(false)->comment('Admin verified the information');
            $table->timestamps();

            $table->index(['kindergarten_id', 'academic_year']);
            $table->index('deadline_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_deadlines');
    }
};
