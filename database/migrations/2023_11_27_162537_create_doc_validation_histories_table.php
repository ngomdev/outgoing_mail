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
        Schema::create('doc_validation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')
            ->index();
            $table->foreignId('document_id')
            ->index();
            $table->timestamp('validation_date')->useCurrent();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doc_validation_histories');
    }
};
