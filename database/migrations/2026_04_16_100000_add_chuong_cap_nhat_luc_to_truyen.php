<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('truyen', function (Blueprint $table) {
            $table->timestamp('chuong_cap_nhat_luc')->nullable()->after('published_at');
            $table->index('chuong_cap_nhat_luc');
        });

        // Khởi tạo giá trị từ chương mới nhất hoặc updated_at
        DB::statement("
            UPDATE truyen t
            LEFT JOIN (
                SELECT truyen_id, MAX(created_at) as latest
                FROM chuong
                WHERE is_published = 1
                GROUP BY truyen_id
            ) c ON c.truyen_id = t.id
            SET t.chuong_cap_nhat_luc = COALESCE(c.latest, t.updated_at)
        ");
    }

    public function down(): void
    {
        Schema::table('truyen', function (Blueprint $table) {
            $table->dropIndex(['chuong_cap_nhat_luc']);
            $table->dropColumn('chuong_cap_nhat_luc');
        });
    }
};
