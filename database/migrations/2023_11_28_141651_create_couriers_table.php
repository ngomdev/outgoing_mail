<?php

use App\Enums\CourierStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id');
            $table->foreignUuid('created_by');
            $table->string('courier_number')->unique()->nullable()->index();
            $table->text('object')->nullable();
            $table->string('status')->default(CourierStatus::DRAFT);
            $table->string('comment')->nullable();
            $table->text('attachments')->nullable();
            $table->timestamp('closure_date')->nullable();
            $table->string('deposit_location_name')->nullable();
            $table->timestamp('courier_created_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};
