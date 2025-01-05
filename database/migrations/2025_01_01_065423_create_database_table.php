<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('research_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name')->unique();
            $table->string('group_description', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('password');
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->string('year');
            $table->foreignId('research_group_id')->nullable()->constrained('research_groups')->nullOnDelete();
            $table->boolean('first_login')->default(true);
            $table->string('role', 50);
            $table->timestamps();
            $table->rememberToken();
        });

        Schema::create('lecturer_quotas', function (Blueprint $table) {
            $table->id();
            $table->string('semester', 50);
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->integer('total_quota');
            $table->integer('remaining_quota')->default(0);
            $table->timestamps();
        });

        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->string('proposal_title');
            $table->text('proposal_description');
            $table->enum('status', ['Available', 'Taken'])->default('Available');
            $table->timestamps();
        });

        Schema::create('student_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lecturer_quota_id')->constrained('lecturer_quotas')->cascadeOnDelete();
            $table->foreignId('proposal_id')->nullable()->constrained('proposals')->nullOnDelete();
            $table->string('proposal_title');
            $table->text('proposal_description');
            $table->string('student_title');
            $table->text('student_description')->nullable();
            $table->enum('status', ['Pending', 'Accepted', 'Rejected'])->default('Pending');
            $table->timestamp('decision_date')->nullable();
            $table->timestamps();
        });

        Schema::create('supervisor_hunting_periods', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 50);
            $table->text('content');
            $table->enum('status', ['Unread', 'Read'])->default('Unread');
            $table->timestamps();
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_path');
            $table->timestamps();
        });

        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->date('requested_date');
            $table->time('requested_time');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamps();
        });

        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointments')->cascadeOnDelete();
            $table->date('reminder_date');
            $table->enum('reminder_status', ['Sent', 'Pending'])->default('Pending');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('appointment_requests');
        Schema::dropIfExists('timetables');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('supervisor_hunting_periods');
        Schema::dropIfExists('student_applications');
        Schema::dropIfExists('proposals');
        Schema::dropIfExists('research_groups');
        Schema::dropIfExists('lecturer_quotas');
        Schema::dropIfExists('users');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};