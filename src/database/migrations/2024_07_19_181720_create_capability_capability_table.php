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
        Schema::create('capability_capability', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dependency_id');
            $table->unsignedBigInteger('dependent_id');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });

        Schema::table('capability_capability', function (Blueprint $table) {
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
        Schema::table('capability_capability', function (Blueprint $table) {
            $table->dropForeign('capability_capability_dependency_id_foreign');
            $table->dropForeign('capability_capability_dependent_id_foreign');
        });

        Schema::dropIfExists('capability_capability');
    }
};
