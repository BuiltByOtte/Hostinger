<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_pages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            
            $table->enum('visibility', [
                'everyone',
                'customers',
                'admins',
                'guests',
                'logged-in'
            ])->default('everyone');
            
            $table->boolean('visible_in_menu')->default(false);
            $table->boolean('is_active')->default(true);
            
            $table->timestamp('expires_at')->nullable();
            
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_image')->nullable();
            $table->string('meta_color')->nullable();
            $table->string('meta_favicon')->nullable();

            $table->text('htmlcontent')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_pages');
    }
};
