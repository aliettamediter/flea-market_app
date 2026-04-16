<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePaymentMethodToStringInPurchasesTable extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('payment_method')->change();
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->integer('payment_method')->change();
        });
    }
}