-- STEP 1: Add student with full info
INSERT INTO students (email, username, password)
VALUES ('reggie_s123456@gmail.com', 'Samantha Reggie', 'r3gg1e_1s_c001');

-- STEP 2: Get her student_id (run separately if needed)
-- (for this script, assume it returns 1 — change as needed)
-- SELECT student_id FROM students WHERE email = 'reggie_s123456@gmail.com';

-- STEP 3: Enroll in ALL General Ed courses
INSERT INTO student_courses (student_id, course_id, semester, year)
SELECT 1, course_id, 'FALL', 2024
FROM courses
WHERE course_type = 'General Ed';

-- STEP 4: Enroll in SOME Math Minor courses
-- Let's pick MATH 2415 and MATH 3320
INSERT INTO student_courses (student_id, course_id, semester, year)
SELECT 1, course_id, 'SPRING', 2025
FROM courses
WHERE course_code IN ('MATH 2415', 'MATH 3320');

-- STEP 5: Enroll in SOME Software Development courses
-- Let's pick COSC 4460 (Software Engineering) and COSC 4415 (Database Systems)
INSERT INTO student_courses (student_id, course_id, semester, year)
SELECT 1, course_id, 'SPRING', 2025
FROM courses
WHERE course_code IN ('COSC 4460', 'COSC 4415');

