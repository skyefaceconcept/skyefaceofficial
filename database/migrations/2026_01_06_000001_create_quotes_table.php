<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('package')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('details')->nullable();
            $table->string('status')->default('new');
            $table->boolean('notified')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotes');
    }
};
