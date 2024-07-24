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
        Schema::create(config('warden.tables.role_user'), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_active')->default(1);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });

        Schema::table(config('warden.tables.role_user'), function (Blueprint $table) {
            $table->unique([ 'role_id', 'user_id' ]);

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('warden.tables.role_user'), function (Blueprint $table) {
            $table->dropForeign(config('warden.tables.role_user') . '_role_id_foreign');
            $table->dropForeign(config('warden.tables.role_user') . '_user_id_foreign');
        });

        Schema::dropIfExists(config('warden.tables.role_user'));
    }
};
