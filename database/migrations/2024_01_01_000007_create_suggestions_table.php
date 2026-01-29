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
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kindergarten_id')->nullable()->constrained()->onDelete('set null');
            $table->text('content');
            $table->enum('category', [
                'school_info',      // 學校資訊
                'ranking_feedback', // 排名反饋
                'feature_request',  // 功能建議
                'data_correction',  // 資料更正
                'general',          // 一般建議
                'other'             // 其他
            ])->default('general');
            $table->enum('status', [
                'pending',          // 待處理
                'reviewed',         // 已審閱
                'processed',        // 已處理
                'archived'          // 已存檔
            ])->default('pending');
            $table->text('admin_notes')->nullable()->comment('Internal notes from admin');
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['kindergarten_id', 'status']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};
