<?php

declare(strict_types=1);

use App\Domain\Wallet\Enums\WalletType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->bigInteger('balance')->default(0);
            $table->uuid('account_id');
            $table->enum('type', WalletType::values())->default(WalletType::Default);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropForeign('wallets_account_id_foreign');
        });
        Schema::dropIfExists('wallets');
    }
};
