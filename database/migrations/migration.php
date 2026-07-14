<?php
// Buat dengan: php artisan make:migration add_fields_to_admins_table
// Lalu ganti isinya dengan ini

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'name')) {
                $table->string('name')->after('admin_id')->nullable();
            }
            if (!Schema::hasColumn('admins', 'email')) {
                $table->string('email')->unique()->nullable()->after('username');
            }
            if (!Schema::hasColumn('admins', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'is_active']);
        });
    }
};
