<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('feedback');
    }

    public function down(): void
    {
        // Feedback feature has been removed — no rollback
    }
};
