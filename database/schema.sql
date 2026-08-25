-- Database Schema

CREATE TABLE users (
    user_id     INT AUTO_INCREMENT PRIMARY KEY,
    full_name   VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('student', 'mentor', 'admin') NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE student_profiles (
    user_id         INT PRIMARY KEY,
    university      VARCHAR(150),
    department      VARCHAR(100),
    academic_year   TINYINT,                 -- 1 to 5
    cgpa_range      ENUM('<2.5','2.5-3.0','3.0-3.5','3.5-4.0'),
    skills          VARCHAR(255),    
    interests       VARCHAR(255),
    career_goal     ENUM('FAANG','MS_Abroad','PhD_Abroad','PhD_Local',
                          'Research','Startup','Government','Other'),
    target_detail   VARCHAR(255),       
    experience      TEXT,                
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE mentor_profiles (
    user_id              INT PRIMARY KEY,
    university           VARCHAR(150),
    department           VARCHAR(100),
    cgpa_range           ENUM('<2.5','2.5-3.0','3.0-3.5','3.5-4.0'),  
    graduation_year      YEAR,
    current_organization VARCHAR(150),
    current_position     VARCHAR(150),
    skills               VARCHAR(255),
    achievements         TEXT,
    career_story         TEXT,            
    external_profile_url VARCHAR(255),     
    goal_achieved        ENUM('FAANG','MS_Abroad','PhD_Abroad','PhD_Local',
                               'Research','Startup','Government','Other'),
    verification_status  ENUM('pending','verified','rejected') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE session_requests (
    request_id    INT AUTO_INCREMENT PRIMARY KEY,
    student_id    INT NOT NULL,
    mentor_id     INT NOT NULL,
    message       TEXT NOT NULL,
    status        ENUM('pending','accepted','rejected') DEFAULT 'pending',
    requested_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    responded_at  TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (mentor_id)  REFERENCES users(user_id) ON DELETE CASCADE
);