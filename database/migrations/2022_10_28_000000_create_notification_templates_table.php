<?php

use DH\NotificationTemplates\Enums\NotificationLevelEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('notification');

            $table->string('level')->default(NotificationLevelEnum::INFO->value);
            $table->text('subject')->nullable();
            $table->text('greeting')->nullable();
            $table->text('salutation')->nullable();
            $table->json('intro_lines')->nullable();
            $table->json('outro_lines')->nullable();
            $table->text('action_text')->nullable();
            $table->text('action_url')->nullable();

            $table->longText('template')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_templates');
    }
};