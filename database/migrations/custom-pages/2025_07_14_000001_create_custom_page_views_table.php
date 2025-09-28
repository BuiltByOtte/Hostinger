<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('custom_pages')->onDelete('cascade');
            $table->ipAddress('ip_address');
            $table->string('user_agent')->nullable();
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_page_views');
    }
};
