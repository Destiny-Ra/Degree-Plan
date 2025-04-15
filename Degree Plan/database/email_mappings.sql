
-- connection between new table 'students' to connect email to course database

CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE student_courses (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    semester ENUM('FALL', 'SPRING', 'SUMMER') NOT NULL,
    year INT NOT NULL,
    UNIQUE (student_id, course_id, semester, year),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);

