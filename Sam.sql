-- Student Info 
INSERT INTO students (email, username, password)
VALUES ('reggie_s123456@gmail.com', 'Samantha Reggie', 'r3gg1e_1s_c001');

-- Enroll in Gen ED
INSERT INTO student_courses (student_id, course_id, semester, year)
SELECT 1, course_id, 'FALL', 2024
FROM courses
WHERE course_type = 'General Ed';

-- Math Min0r
INSERT INTO student_courses (student_id, course_id, semester, year)
SELECT 1, course_id, 'SPRING', 2025
FROM courses
WHERE course_code IN ('MATH 2415', 'MATH 3320');

-- Software Engineering Track 
INSERT INTO student_courses (student_id, course_id, semester, year)
SELECT 1, course_id, 'SPRING', 2025
FROM courses
WHERE course_code IN ('COSC 4460', 'COSC 4415');

