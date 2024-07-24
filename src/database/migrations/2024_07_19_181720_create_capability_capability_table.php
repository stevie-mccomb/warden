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
        Schema::create(config('warden.tables.capability_capability'), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dependency_id');
            $table->unsignedBigInteger('dependent_id');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });

        Schema::table(config('warden.tables.capability_capability'), function (Blueprint $table) {
            $table->unique([ 'dependency_id', 'dependent_id' ]);

            $table->foreign('dependency_id')->references('id')->on('capabilities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('dependent_id')->references('id')->on('capabilities')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('warden.tables.capability_capability'), function (Blueprint $table) {
            $table->dropForeign(config('warden.tables.capability_capability') . '_dependency_id_foreign');
            $table->dropForeign(config('warden.tables.capability_capability') . '_dependent_id_foreign');
        });

        Schema::dropIfExists(config('warden.tables.capability_capability'));
    }
};
