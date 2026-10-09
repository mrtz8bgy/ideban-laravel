<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReadTrackingToTicketMessages extends Migration
{
    public function up()
    {
        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->dateTime('read_at')->nullable()->after('is_staff');
            $table->index(['ticket_id', 'is_staff', 'read_at'], 'ticket_messages_inbox_idx');
        });
    }

    public function down()
    {
        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->dropIndex('ticket_messages_inbox_idx');
            $table->dropColumn('read_at');
        });
    }
}
