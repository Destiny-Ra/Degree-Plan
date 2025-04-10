-- NOTE- CHANGE MANDATORY MATH SUPPORT COURSES TO GEN ED AND VERIFY THE CREDIT HOURS
-- WITH THE ADDITION OF ADDING COURSE NUMBERS TO VARIABLE COURSES LIKE CREATIVE ARTS OR SCIENCE W/ LAB
-- ALSO NO CORE COMP SCIENCE YET

-- freshman requirement
('UNIV 1301', 'Honors Freshman Seminar I', 'Introduction to university life, academic skills, and critical thinking.', 3, 'General Ed'),
-- communication requirement
('ENGL 1301', 'Composition I', 'Introduction to composition, focusing on writing processes and academic writing techniques.', 3, 'General Ed'),
('ENGL 1302', 'Composition II', 'Advanced composition course focused on argumentative writing and research skills.', 3, 'General Ed'),
-- history requirement
('HIST 1301', 'History of the US to 1877', 'Survey of American history from pre-Columbian times to the Reconstruction Era.', 3, 'General Ed'),
('HIST 1302', 'History of the US since 1877', 'Survey of American history from Reconstruction to the present.', 3, 'General Ed'),
-- creative arts requirement. changed to ARTS 1301 because thats generally what advisors recommend, options can be added later
('ARTS 1301', 'Art Appreciation', 'The study of art, its role in society, the creative process and standards of artistic judgment.', 3, 'General Ed'),

('ECON 2301', 'Principles of Macroeconomics', 'A description of major economic problems facing modern societies is presented together with how the capitalistic market system addresses these issues. The emphasis is on macroeconomics theory and practice.', 3, 'General Ed'),

('COMM 1315', 'Intro to Public Speaking', 'Fundamentals of public speaking, including speech preparation, delivery, and audience analysis.', 3, 'General Ed'),

('PLSC 2305', 'American National Politics', 'Introduction to the American political system, focusing on its structure and function.', 3, 'General Ed'),
('PLSC 2306', 'State and Local Politics', 'Study of state and local government in the United States, including political systems and policies.', 3, 'General Ed'),

-- put precal into math support area
-- removed cal 1 and 2 because they are duplicates

-- changed general science w lab to be 4 seperate courses: the classes themselves and their labs. Also, chose chemistry because it is very popular choice.
('CHEM 1311', 'General Chemistry I', 'An introduction to chemistry, fundamentals of atomic structure and bonding, periodic chart, chemical nomenclature, equations and reactions.', 3, 'General Ed'),
('CHEM 1312', 'General Chemistry II', 'Continuation of Chemistry 1311.  Kinetics, equilibria, thermodynamics, electrochemistry, environmental chemistry, nuclear chemistry, and organic chemistry.', 3, 'General Ed');
('CHEM 1111', 'General Chemistry Lab I', 'Experiments related to principles and topics covered in CHEM 1311.', 1, 'General Ed');
('CHEM 1112', 'General Chemistry Lab II', 'Experiments related to principles and topics covered in CHEM 1312.', 1, 'General Ed');

