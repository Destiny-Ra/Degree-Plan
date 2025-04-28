# Abstract 
This project will be a web application that allows a student to login, enter what major they are, and see what classes are needed for them to graduate. It will also tell a student whether they should take 3 or 4 credit courses based on their graduation date, improving their ability to plan courses out. Once finished, this project will allow students to keep track of classes taken, classes needed, prerequisites, and graduation date. 
# Motivation
The motivation for this project is the unfortunate smoke and mirrors game of figuring out what courses need to be taken at UTPB, especially as a Computer Science major. Some advisors say certain classes are required, others say they aren’t. Others get to their last semester and find they are just one or two credits shy of graduating, making it very hard to find a class to fill the role. Our degree map web application will make this process a simpler and more intuitive.

## Required tools/packages
- Composer package manager
- Auth0 application
- sever hosting capabilities for both database and web-app.

## Implementation
Firstly, after all necessary tools are downloaded (github package, composer, etc.), create an Auth0 application.
Then, in an .env file, fill in the required info (client id, client secret, etc.)
Make sure the Allowed Callback URLs are: http://127.0.0.1:3000/callback, http://127.0.0.1:3000/home_page/home_page.php
Make sure the Allowed Logout URLS are: http://127.0.0.1:3000/logout, http://127.0.0.1:3000
Finally, make sure the Allowed Web Origins is http://127.0.0.1:3000
If preferred, change the image from the default Auth0 image to a UTPB one for correct branding

After all this is done, open up MySQL, create a schema with username 'root' and server address 'localhost'
A password is not necessary, but possible, just change the $password variable in the 'db_connection.php' file
Then, load open all the SQL scripts from the 'database' folder and run each script, starting with 'compsci_core_schema.sql', then 'email_mappings.sql', and then the rest in any order.
Then, make sure the information is correct on the 'db_connection.php' file, and the application can be used.

Once viable, users will be able to make an account or sign in with an existing account via Auth0 to access the main home page, from there the user will have the ability to add courses to their itinerary based on credit and course requirements.
