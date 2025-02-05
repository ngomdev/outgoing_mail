<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doc_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id');
            $table->foreignId('document_id')->index();
            $table->decimal('version')->default(0.1);
            $table->string('action');
            $table->longText('content')->nullable();
            $table->string('doc_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doc_histories');
    }
};
