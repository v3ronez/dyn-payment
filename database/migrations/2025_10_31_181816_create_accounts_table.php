<?php

declare(strict_types=1);

use App\Domain\Account\Enums\AccountStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('number');
            $table->bigInteger('balance')->default(0);
            $table->enum('status', AccountStatus::values())->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign('accounts_user_id_foreign');
        });

        Schema::dropIfExists('accounts');
    }
};
