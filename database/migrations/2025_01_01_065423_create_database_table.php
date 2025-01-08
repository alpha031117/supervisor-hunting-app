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
        // 1) Create the 'programs' table
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description', 500)->nullable();
            $table->timestamps();
        });

        // 2) Create the 'research_groups' table
        Schema::create('research_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name')->unique();
            $table->string('group_description', 500)->nullable();
            $table->timestamps();
        });

        // 3) Create the 'users' table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('password');
            $table->foreignId('program_id')
                ->nullable()
                ->constrained('programs')
                ->nullOnDelete();
            $table->string('year');
            $table->foreignId('research_group_id')
                ->nullable()
                ->constrained('research_groups')
                ->nullOnDelete();
            $table->boolean('first_login')->default(true);
            $table->string('role', 50);
            $table->timestamps();
            $table->rememberToken();
        });

        /**
         * IMPORTANT: Create 'supervisor_hunting_periods' before 'lecturer_quotas'
         * so that we can properly reference 'semester'.
         */
        Schema::create('supervisor_hunting_periods', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('semester', 50);  // Not forced to be unique anymore
            $table->boolean('is_set')->default(false);
            $table->timestamps();
        });

        // 4) Create the 'lecturer_quotas' table
        Schema::create('lecturer_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_hunting_period_id')
                ->constrained('supervisor_hunting_periods')
                ->cascadeOnDelete();
            $table->foreignId('lecturer_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->integer('total_quota');
            $table->integer('remaining_quota')->default(0);
            $table->timestamps();
        });

        // 5) Create the 'proposals' table
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->string('proposal_title');
            $table->text('proposal_description');
            $table->enum('status', ['Available', 'Taken'])->default('Available');
            $table->timestamps();
        });

        // 6) Create the 'student_applications' table
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

        // 7) Create the 'notifications' table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 50);
            $table->text('content');
            $table->enum('status', ['Unread', 'Read'])->default('Unread');
            $table->timestamps();
        });

        // 8) Create the 'appointments' table
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

        // 9) Create the 'timetables' table
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_path');
            $table->timestamps();
        });

        // 10) Create the 'appointment_requests' table
        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->date('requested_date');
            $table->time('requested_time');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamps();
        });

        // 11) Create the 'reminders' table
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointments')->cascadeOnDelete();
            $table->date('reminder_date');
            $table->enum('reminder_status', ['Sent', 'Pending'])->default('Pending');
            $table->timestamps();
        });

        // 12) Create the 'password_reset_tokens' table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 13) Create the 'sessions' table
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
        // Drop in the reverse order, ensuring that referencing tables
        // are dropped first (lecturer_quotas before supervisor_hunting_periods).
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('appointment_requests');
        Schema::dropIfExists('timetables');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('student_applications');
        Schema::dropIfExists('proposals');
        Schema::dropIfExists('research_groups');

        // Drop lecturer_quotas before supervisor_hunting_periods because of FK reference
        Schema::dropIfExists('lecturer_quotas');
        Schema::dropIfExists('supervisor_hunting_periods');

        Schema::dropIfExists('users');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
