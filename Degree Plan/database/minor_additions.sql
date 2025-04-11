-- AGAIN VERIFY CREDIT HOURS-- 

-- Insert Math and Computer Science Courses
-- changing all courses to be of type "Math Minor"
INSERT INTO courses (course_code, title, description, credits, course_type) VALUES
    -- removed cal 1 and 2 to avoid duplicating because they are math support
    -- removed mathematical reasoning because they are math support
    ("MATH 2415", "Calculus III", "Continuation of MATH 2414. Topics include sequences and series, multivariable functions, and partial derivatives.", 4, "Math Minor"),
    ("MATH 3320", "Differential Equations", "Introduction to ordinary differential equations, including methods of solution and applications.", 3, "Math Minor"),
    -- removed statistics/intro to probability  because its math support
    ("MATH 3360", "Intermediate Analysis", "The study of sequences, series, and real-valued functions with a focus on rigorous proofs and convergence.", 3, "Math Minor"),
    ("MATH 3310", "Linear Algebra", "Introduction to vector spaces, linear transformations, matrices, determinants, and eigenvalues/eigenvectors.", 3, "Math Minor"),
    ("MATH 3315", "Algebraic Structures", "Introduction to abstract algebra, focusing on groups, rings, and fields.", 3, "Math Minor");
    -- removed discrete math because its comp sci core