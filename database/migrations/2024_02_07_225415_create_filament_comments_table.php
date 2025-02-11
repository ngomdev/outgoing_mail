<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('filament_comments', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')
            ->index();
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id');
            $table->longText('comment');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('filament_comments');
    }
};
