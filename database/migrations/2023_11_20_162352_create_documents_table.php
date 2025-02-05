<?php

use App\Enums\DocStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('created_by');
            $table->foreignId('external_doc_initiator_id')->nullable();
            $table->boolean('should_be_expedited')->default(true);
            $table->string('doc_type')->index();
            $table->string('doc_urgency');
            $table->string('name')->unique()->nullable()->index();
            $table->text('object')->nullable();
            $table->string('status')->default(DocStatus::DRAFT);
            $table->text('doc_content')->nullable();
            $table->string('doc_path');
            $table->timestamp('doc_created_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
