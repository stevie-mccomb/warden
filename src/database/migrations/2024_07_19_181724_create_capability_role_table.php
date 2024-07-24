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
        Schema::create(config('warden.tables.capability_role'), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('capability_id');
            $table->unsignedBigInteger('role_id');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });

        Schema::table(config('warden.tables.capability_role'), function (Blueprint $table) {
            $table->unique([ 'capability_id', 'role_id' ]);

            $table->foreign('capability_id')->references('id')->on('capabilities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('warden.tables.capability_role'), function (Blueprint $table) {
            $table->dropForeign(config('warden.tables.capability_role') . '_capability_id_foreign');
            $table->dropForeign(config('warden.tables.capability_role') . '_role_id_foreign');
        });

        Schema::dropIfExists(config('warden.tables.capability_role'));
    }
};
