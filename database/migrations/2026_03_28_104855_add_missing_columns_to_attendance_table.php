<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            // Check and add columns if they don't exist
            if (!Schema::hasColumn('attendance', 'student_id')) {
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('attendance', 'course_id')) {
                $table->foreignId('course_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('attendance', 'classes_attended')) {
                $table->integer('classes_attended')->default(0);
            }
            if (!Schema::hasColumn('attendance', 'total_classes')) {
                $table->integer('total_classes')->default(11);
            }
            if (!Schema::hasColumn('attendance', 'semester')) {
                $table->string('semester')->nullable();
            }
            if (!Schema::hasColumn('attendance', 'academic_year')) {
                $table->integer('academic_year')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $columns = [
                'student_id', 'course_id', 'classes_attended', 
                'total_classes', 'semester', 'academic_year'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('attendance', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};