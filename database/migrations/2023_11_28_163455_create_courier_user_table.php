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
        Schema::create('courier_user', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->nullable();
            $table->foreignId('courier_id')->index();
            $table->foreignId('recipient_id')
            ->index();
            $table->foreignId('contact_id')->nullable();

            $table->text('comment')->nullable();
            $table->string('type');
            $table->integer('order_column')->nullable();

            $table->timestamp('assignment_date')->nullable();
            $table->timestamp('pickup_date')->nullable();
            $table->timestamp('deposit_date')->nullable();

            $table->string('status')->nullable();
            $table->string('receipt_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->text('rejection_motive')->nullable();

            $table->string('lat')->nullable();
            $table->string('lng')->nullable();

            $table->boolean('notified')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courier_user');
    }
};
