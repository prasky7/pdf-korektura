<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pdf_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pdf_document_id')->constrained('pdf_documents')->onDelete('cascade');
            $table->integer('version_number');
            $table->string('file_path');
            $table->string('original_filename')->nullable();
            $table->foreignId('uploaded_by_user_id')->constrained('users')->onDelete('restrict');
            $table->text('change_summary')->nullable();
            $table->timestamps();

            $table->unique(['pdf_document_id', 'version_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pdf_versions');
    }
};
