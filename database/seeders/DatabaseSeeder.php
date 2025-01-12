<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Program;
use App\Models\User;
use App\Models\ResearchGroup;
use App\Models\LecturerQuota;
use App\Models\Proposal;
use App\Models\SupervisorHuntingPeriod;
use App\Models\Notification;
use App\Models\Appointment;
use App\Models\Timetable;
use App\Models\AppointmentRequest;
use App\Models\Reminder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the programs
        Program::insert([
            ['name' => 'Software Engineering', 'description' => 'BCS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Networking', 'description' => 'BCN', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Graphic Design', 'description' => 'BCG', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CyberSecurity', 'description' => 'BCY', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Create the research groups
        ResearchGroup::insert([
            ['group_name' => 'AI Research Group', 'group_description' => 'Focused on Artificial Intelligence', 'created_at' => now(), 'updated_at' => now()],
            ['group_name' => 'Networking Research Group', 'group_description' => 'Exploring advanced networking concepts', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Create users
        User::insert([
            [
                'email' => 'coordinator@example.com',
                'name' => 'Coordinator User',
                'password' => Hash::make('password'),
                'program_id' => 1,
                'research_group_id' => null,
                'first_login' => true,
                'year' => '2024/2025',
                'role' => 'coordinator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'lecturer@example.com',
                'name' => 'Lecturer User',
                'password' => Hash::make('password'),
                'program_id' => 2,
                'research_group_id' => 1,
                'first_login' => true,
                'year' => '2024/2025',
                'role' => 'lecturer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'student@example.com',
                'name' => 'Student User',
                'password' => Hash::make('password'),
                'program_id' => 1,
                'research_group_id' => null,
                'first_login' => true,
                'year' => '2024/2025',
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create supervisor hunting periods
        $period = SupervisorHuntingPeriod::create([
            'start_date' => '2025-01-01',
            'end_date' => '2025-06-30',
            'semester' => 'Semester I 2024/25',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create lecturer quotas
        LecturerQuota::insert([
            [
                'supervisor_hunting_period_id' => $period->id,
                'lecturer_id' => 2,
                'total_quota' => 0,
                'remaining_quota' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create proposals
        Proposal::insert([
            [
                'lecturer_id' => 2,
                'proposal_title' => 'AI in Education',
                'proposal_description' => 'Exploring AI applications in educational technology.',
                'status' => 'Available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create notifications
        Notification::insert([
            [
                'user_id' => 1,
                'title' => 'Welcome',
                'content' => 'Welcome to the Supervisor Hunting System!',
                'status' => 'Unread',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create appointments
        Appointment::insert([
            [
                'student_id' => 3,
                'lecturer_id' => 2,
                'appointment_date' => '2025-01-15',
                'appointment_time' => '10:00:00',
                'status' => 'Pending',
                'reason' => 'Discuss research proposal.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create timetables
        Timetable::insert([
            [
                'lecturer_id' => 2,
                'file_path' => 'uploads/timetables/timetable_lecturer_2.pdf',
                'room_no' => '101',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
