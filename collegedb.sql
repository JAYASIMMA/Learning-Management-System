-- Database: collegedb
CREATE DATABASE IF NOT EXISTS collegedb;
USE collegedb;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('admin','faculty','student') NOT NULL
);

-- Courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    faculty_id INT,
    FOREIGN KEY (faculty_id) REFERENCES users(id)
);

-- Subjects table
CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    name VARCHAR(100) NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Attendance table
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject_id INT,
    date DATE,
    status ENUM('present','absent') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- Marks table
CREATE TABLE IF NOT EXISTS marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject_id INT,
    marks INT,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- Notices table
CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    posted_by INT,
    role ENUM('admin','faculty','student','all') DEFAULT 'all',
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES users(id)
);

-- Sample users
INSERT INTO users (username, password, name, email, role) VALUES
('admin', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 'Admin User', 'admin@collegedb.com', 'admin'),
('faculty1', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 'Faculty One', 'faculty1@collegedb.com', 'faculty'),
('student1', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 'Student One', 'student1@collegedb.com', 'student');

-- Sample courses
INSERT INTO courses (name, description, faculty_id) VALUES
('Computer Science', 'BSc in Computer Science', 2);

-- Sample subjects
INSERT INTO subjects (course_id, name) VALUES
(1, 'Data Structures'),
(1, 'Algorithms');

-- Sample attendance
INSERT INTO attendance (student_id, subject_id, date, status) VALUES
(3, 1, '2024-06-01', 'present'),
(3, 2, '2024-06-01', 'absent');

-- Sample marks
INSERT INTO marks (student_id, subject_id, marks) VALUES
(3, 1, 85),
(3, 2, 78);

-- Sample notices
INSERT INTO notices (title, content, posted_by, role) VALUES
('Welcome', 'Welcome to the Online Course Management System!', 1, 'all'); 