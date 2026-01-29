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
        Schema::create('school_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kindergarten_id')->constrained()->onDelete('cascade');
            $table->enum('feature_type', [
                'teaching_method',      // 教學方法
                'language',             // 教學語言
                'curriculum',           // 課程特色
                'facility',             // 設施
                'extracurricular',      // 課外活動
                'award',                // 獎項
                'strength',             // 學校優勢
                'other'                 // 其他
            ]);
            $table->string('value_zh_tw');
            $table->string('value_zh_cn');
            $table->string('value_en');
            $table->timestamps();

            $table->index(['kindergarten_id', 'feature_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_features');
    }
};
