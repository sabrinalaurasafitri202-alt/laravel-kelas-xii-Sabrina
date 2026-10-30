<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('password');
            }
            $table->unsignedBigInteger('profile_id')->nullable()->change();
            $table->unsignedBigInteger('role_id')->nullable()->default(1)->change();
        });

        if (Schema::hasColumn('users', 'role_id') && Schema::hasColumn('users', 'role')) {
            DB::table('users')->where('role_id', 2)->update(['role' => 'admin']);
            DB::table('users')->where(function ($q) {
                $q->where('role_id', '!=', 2)->orWhereNull('role_id');
            })->update(['role' => 'user']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
