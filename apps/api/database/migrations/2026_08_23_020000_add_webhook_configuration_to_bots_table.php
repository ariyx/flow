<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bots', function (Blueprint $table): void {
            $table->string('webhook_url')->nullable()->unique();
            $table->text('webhook_secret')->nullable();
            $table->string('webhook_status')->default('not_configured');
            $table->text('webhook_error')->nullable();
            $table->timestamp('webhook_checked_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bots', function (Blueprint $table): void {
            $table->dropUnique(['webhook_url']);
            $table->dropColumn(['webhook_url', 'webhook_secret', 'webhook_status', 'webhook_error', 'webhook_checked_at']);
        });
    }
};
