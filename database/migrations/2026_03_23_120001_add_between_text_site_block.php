<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $exists = DB::table('site_blocks')->where('key', 'between_text')->exists();
        if ($exists) {
            return;
        }

        DB::table('site_blocks')->insert([
            'key' => 'between_text',
            'title' => 'Текстовый блок',
            'content' => json_encode([
                'text' => 'Совсем скоро начнется важный и радостный день нашей семьи. Будем счастливы разделить его с вами.',
            ], JSON_UNESCAPED_UNICODE),
            'image_path' => null,
            'is_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('site_blocks')->where('key', 'between_text')->delete();
    }
};
