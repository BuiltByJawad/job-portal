CREATE DATABASE IF NOT EXISTS job_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE job_portal;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    role ENUM('seeker','employer','recruiter','admin') NOT NULL,
    profile_pic VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS seeker_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    headline VARCHAR(150),
    summary TEXT,
    skills TEXT,
    years_experience DECIMAL(4,1),
    education_level VARCHAR(100),
    current_salary DECIMAL(12,2),
    expected_salary DECIMAL(12,2),
    preferred_location VARCHAR(120),
    resume_path VARCHAR(255),
    CONSTRAINT fk_seeker_user FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS employer_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_name VARCHAR(150) NOT NULL,
    industry VARCHAR(100),
    company_size VARCHAR(50),
    description TEXT,
    website VARCHAR(200),
    address VARCHAR(255),
    logo_path VARCHAR(255),
    CONSTRAINT fk_employer_user FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS recruiter_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    agency_name VARCHAR(150) NOT NULL,
    specialization VARCHAR(120) NOT NULL,
    description TEXT,
    website VARCHAR(200),
    CONSTRAINT fk_recruiter_user FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS recruiter_clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recruiter_id INT NOT NULL,
    employer_id INT NULL,
    company_name_override VARCHAR(150) NULL,
    added_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_rc_recruiter FOREIGN KEY (recruiter_id) REFERENCES users(id),
    CONSTRAINT fk_rc_employer FOREIGN KEY (employer_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    recruiter_id INT NULL,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT,
    benefits TEXT,
    salary_min DECIMAL(12,2),
    salary_max DECIMAL(12,2),
    location VARCHAR(120),
    job_type ENUM('full-time','part-time','remote','contract') NOT NULL,
    experience_level ENUM('entry','mid','senior') NOT NULL,
    deadline DATE NOT NULL,
    status ENUM('active','closed','draft') NOT NULL DEFAULT 'draft',
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_jobs_employer FOREIGN KEY (employer_id) REFERENCES users(id),
    CONSTRAINT fk_jobs_recruiter FOREIGN KEY (recruiter_id) REFERENCES users(id),
    CONSTRAINT fk_jobs_category FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    seeker_id INT NOT NULL,
    recruiter_id INT NULL,
    cover_letter TEXT,
    resume_path VARCHAR(255),
    status ENUM('submitted','reviewed','shortlisted','interview','rejected','withdrawn') NOT NULL DEFAULT 'submitted',
    applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_apps_job FOREIGN KEY (job_id) REFERENCES jobs(id),
    CONSTRAINT fk_apps_seeker FOREIGN KEY (seeker_id) REFERENCES users(id),
    CONSTRAINT fk_apps_recruiter FOREIGN KEY (recruiter_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS saved_jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    saved_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_saved_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_saved_job FOREIGN KEY (job_id) REFERENCES jobs(id)
);

CREATE TABLE IF NOT EXISTS job_alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seeker_id INT NOT NULL,
    keyword VARCHAR(100),
    category_id INT NULL,
    location VARCHAR(120),
    job_type ENUM('full-time','part-time','remote','contract') NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_alerts_seeker FOREIGN KEY (seeker_id) REFERENCES users(id),
    CONSTRAINT fk_alerts_category FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS recruiter_outreach (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recruiter_id INT NOT NULL,
    seeker_id INT NOT NULL,
    job_id INT NOT NULL,
    message TEXT NOT NULL,
    status ENUM('sent','read','responded') NOT NULL DEFAULT 'sent',
    sent_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_outreach_recruiter FOREIGN KEY (recruiter_id) REFERENCES users(id),
    CONSTRAINT fk_outreach_seeker FOREIGN KEY (seeker_id) REFERENCES users(id),
    CONSTRAINT fk_outreach_job FOREIGN KEY (job_id) REFERENCES jobs(id)
);

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    application_id INT NULL,
    body TEXT NOT NULL,
    sent_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    CONSTRAINT fk_msg_sender FOREIGN KEY (sender_id) REFERENCES users(id),
    CONSTRAINT fk_msg_recipient FOREIGN KEY (recipient_id) REFERENCES users(id),
    CONSTRAINT fk_msg_application FOREIGN KEY (application_id) REFERENCES applications(id)
);

CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    submitter_id INT NOT NULL,
    subject_id INT NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open','resolved') NOT NULL DEFAULT 'open',
    admin_note TEXT,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_complaint_submitter FOREIGN KEY (submitter_id) REFERENCES users(id),
    CONSTRAINT fk_complaint_subject FOREIGN KEY (subject_id) REFERENCES users(id)
);
