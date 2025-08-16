<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('markup_setting', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Default Markup'); // nama pengaturan
            $table->decimal('percentage', 5, 2)->default(10.00); // persentase markup
            $table->timestamps();
        });

        // Isi default
        DB::table('markup_setting')->insert([
            'name' => 'Default Markup',
            'percentage' => 10.00,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('markup_settings');
    }
};
