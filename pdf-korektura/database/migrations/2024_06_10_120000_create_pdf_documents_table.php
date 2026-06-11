<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pdf_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('title_id')->constrained('titles')->onDelete('restrict');
            $table->foreignId('uploaded_by_user_id')->constrained('users')->onDelete('restrict');
            $table->string('name')->nullable();
            $table->integer('page_number')->nullable();
            $table->string('issue_title')->nullable();
            $table->dateTime('deadline_date');
            $table->enum('status', ['uploaded', 'in_progress', 'returned', 'completed'])->default('uploaded');
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('current_version_number')->default(1);
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pdf_documents');
    }
};
