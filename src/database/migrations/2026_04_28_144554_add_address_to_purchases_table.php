<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressToPurchasesTable extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('postal_code')->nullable()->after('buyer_id');
            $table->string('address')->nullable()->after('postal_code');
            $table->string('building')->nullable()->after('address');
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['postal_code', 'address', 'building']);
        });
    }
}
