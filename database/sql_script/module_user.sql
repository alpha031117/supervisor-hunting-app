-- Selecting Database
SELECT DATABASE();
USE supervisor_hunting;

-- 2.2.1 User Table
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
    password VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    program_id INT NULL,
    first_login BOOLEAN DEFAULT TRUE,
    role VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_User_Program FOREIGN KEY (program_id) REFERENCES Program(id) ON DELETE SET NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2.2.2 Program Table
CREATE TABLE Program (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
    description VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2.2.3 ResearchGroup Table (Removed program_id)
CREATE TABLE ResearchGroup (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
    group_description VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    lecturer_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_ResearchGroup_User FOREIGN KEY (lecturer_id) REFERENCES User(id) ON DELETE SET NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2.2.4 Proposal Table
CREATE TABLE Proposal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lecturer_id INT NOT NULL,
    proposal_title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    proposal_description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    status ENUM('Available', 'Taken') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Available' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_Proposal_Lecturer FOREIGN KEY (lecturer_id) REFERENCES User(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2.2.5 StudentApplication Table (Added lecturer_quota_id)
CREATE TABLE StudentApplication (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    lecturer_id INT NOT NULL,
    lecturer_quota_id INT NOT NULL,
    proposal_id INT NULL,
    proposal_title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    student_title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    status ENUM('Pending', 'Accepted', 'Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Pending' NOT NULL,
    decision_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_Application_Student FOREIGN KEY (student_id) REFERENCES User(id) ON DELETE CASCADE,
    CONSTRAINT FK_Application_Lecturer FOREIGN KEY (lecturer_id) REFERENCES User(id) ON DELETE CASCADE,
    CONSTRAINT FK_Application_Proposal FOREIGN KEY (proposal_id) REFERENCES Proposal(id) ON DELETE SET NULL,
    CONSTRAINT FK_Application_Quota FOREIGN KEY (lecturer_quota_id) REFERENCES LecturerQuota(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2.2.6 SupervisorHuntingPeriod Table
CREATE TABLE SupervisorHuntingPeriod (
    id INT AUTO_INCREMENT PRIMARY KEY,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2.2.7 LecturerQuota Table
CREATE TABLE LecturerQuota (
    id INT AUTO_INCREMENT PRIMARY KEY,
    semester VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    lecturer_id INT NOT NULL,
    total_quota INT NOT NULL,
    remaining_quota INT DEFAULT 0 NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_Quota_Lecturer FOREIGN KEY (lecturer_id) REFERENCES User(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2.2.8 Notifications Table
CREATE TABLE Notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    content TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    status ENUM('Unread', 'Read') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Unread' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_Notification_User FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2.2.9 Appointment Table
CREATE TABLE Appointment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    lecturer_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Pending' NOT NULL,
    reason TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_Appointment_Student FOREIGN KEY (student_id) REFERENCES User(id) ON DELETE CASCADE,
    CONSTRAINT FK_Appointment_Lecturer FOREIGN KEY (lecturer_id) REFERENCES User(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- 2.2.10 Timetable Table
CREATE TABLE Timetable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lecturer_id INT NOT NULL,
    file_path VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    semester VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_Timetable_Lecturer FOREIGN KEY (lecturer_id) REFERENCES User(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2.2.11 AppointmentRequest Table
CREATE TABLE AppointmentRequest (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    lecturer_id INT NOT NULL,
    requested_date DATE NOT NULL,
    requested_time TIME NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Pending' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_Request_Student FOREIGN KEY (student_id) REFERENCES User(id) ON DELETE CASCADE,
    CONSTRAINT FK_Request_Lecturer FOREIGN KEY (lecturer_id) REFERENCES User(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2.2.12 Reminder Table
CREATE TABLE Reminder (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    reminder_date DATE NOT NULL,
    reminder_status ENUM('Sent', 'Pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Pending' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_Reminder_Appointment FOREIGN KEY (appointment_id) REFERENCES Appointment(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
