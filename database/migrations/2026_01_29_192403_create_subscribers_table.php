<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("subscribers", function (Blueprint $table) {
            $table->id();
            $table->string("email")->unique();
            $table->string("preferred_language")->default("zh-TW");
            $table->boolean("is_verified")->default(false);
            $table->timestamp("verified_at")->nullable();
            $table->timestamps();
            
            $table->index("email");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("subscribers");
    }
};
