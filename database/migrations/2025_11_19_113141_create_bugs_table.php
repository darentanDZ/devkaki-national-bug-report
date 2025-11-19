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
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('app_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('steps_to_reproduce')->nullable();
            $table->text('expected_behavior')->nullable();
            $table->text('actual_behavior')->nullable();
            $table->string('device_info')->nullable();
            $table->string('os_version')->nullable();
            $table->string('app_version')->nullable();
            $table->json('attachments')->nullable(); // Store file URLs as JSON array
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'investigating', 'confirmed', 'fixed', 'closed', 'wont-fix'])->default('open');
            $table->integer('upvotes')->default(0);
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bugs');
    }
};
