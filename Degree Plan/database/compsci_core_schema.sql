-- TEST if database exists, do nothing if it doesn't, if it does, delete it
DROP DATABASE IF EXISTS courses_database;
-- CREATE database called courses_database
CREATE DATABASE courses_database;
USE courses_database;


-- table for courses
CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) NOT NULL,
    title VARCHAR(255) NOT NULL, 
    description TEXT, 
    credits INT NOT NULL,
    course_type ENUM('General Ed', 'Comp Sci General', 'Math Support', 'Math Minor') NOT NULL 
);

-- table for course prerequisites
CREATE TABLE prerequisites (
    course_id INT NOT NULL,
    prerequisite_course_id INT NOT NULL,
    PRIMARY KEY (course_id, prerequisite_course_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (prerequisite_course_id) REFERENCES courses(course_id)
);

-- table for specifc tracks in major
CREATE TABLE tracks (
    track_id INT AUTO_INCREMENT PRIMARY KEY,
    track_name VARCHAR(50) NOT NULL, 
    description TEXT
);

-- table that connects courses to tracks
CREATE TABLE course_tracks (
    course_id INT NOT NULL, 
    track_id INT NOT NULL, 
    PRIMARY KEY (course_id, track_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (track_id) REFERENCES tracks(track_id)
);

-- insert core computer science courses
INSERT INTO courses (course_code, title, description, credits, course_type) VALUES
("COSC 1430", "Introduction to Computer Science I", "Computer organization, algorithm design, programming, and elementary data structures.  Introduction to programming in a high-level language.", 4, "Comp Sci General"), 
("COSC 2430", "Introduction to Computer Science II", "Continuation of COSC 1430. Data structures, data abstraction, information hiding. Advanced programming in the language of the current COSC 1430.", 4, "Comp Sci General"),
("COSC 2420", "C Programming", "Programming in C, investigating the characteristics and implementation", 4, "Comp Sci General"),
("COSC 3310", "Digital Computer Organization", "Design of arithmetic, control, and memory units, binary data representation, error-detecting and error-correcting codes.", 3, "Comp Sci General"),
("COSC 3312", "Discrete Mathematics", "Elementary logic, sets, functions, relations, permutations and combinations, modular arithmetic, graph theory and its applications.", 3, "Comp Sci General"),
("COSC 3315", "Information Systems and Security", "Computer systems and relationships between hardware and software components. Emphasis on business system design and analysis and information security.", 3, "Comp Sci General"),
("COSC 3420", "Data Structures","Design and implementation of algorithms for handling data structures such as linear lists, linked lists, stacks, queues, graphs, trees and strings.", 4, "Comp Sci General"),
-- Math support courses
("MATH 2413", "Calculus I", "Differentiation of functions of one variable, introduction to integration.", 4, "Math Support"),
("MATH 2414", "Calculus II", "Continuation of MATH 2413. Integration of transcendental functions, techniques of integration, sequences and series.", 4, "Math Support"),
("MATH 3301", "Introduction to Probability I", "Introduction to probability theory using calculus.  Basic ideas of probability and random variables, discrete probability, functions, continuous probability densities, joint distribution, transformation of random variables, moments and generating functions of random variables, limit theorems.", 3, "Math Support"), 
("MATH 3305", "Mathematical Reasoning", "Logic methods of proof, set theory, relations, functions, cardinality.  Algebraic properties of the real, rational, and integer number systems.", 3, "Math Support"),
("NTSC 4311", "History and Philosophy of Science", "History and philosophical development of science from Classical Greece to modern times.", 3, "Math Support");