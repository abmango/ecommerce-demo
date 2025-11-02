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
        Schema::table('users', function (Blueprint $table) {
            $table->string('cuit', 20)->nullable()->after('email');
            $table->string('phone', 20)->nullable()->after('cuit');
            $table->enum('preferred_contact_method', ['Correo electrónico', 'Teléfono', 'Whatsapp'])
                ->nullable()
                ->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['cuit', 'phone', 'preferred_contact_method']);
            });
        });
    }
};
