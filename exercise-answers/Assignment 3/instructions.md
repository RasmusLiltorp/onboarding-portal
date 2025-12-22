# My web solution: Back-End

## Introduction

In this assignment, you will use the front-end and back-end components you developed from the previous assignment and connect them to users, implementing authentication and authorization features.
Moreover, you will reflect on the use of advanced web techniques.
The purpose of this assignment is to get you acquainted with security considerations within the web development context and to consider non-functional requirements.

You must submit your answer to itslearning and ScalableTeaching.
We will provide you with dedicated feedback on each submission.
You are allowed to work in pairs, but no more than two!

<span style="color:red">**Disclaimer 1: use of AI** We discourage the use of generative AI to develop this application. The goal of this assignment is to prepare you for the exam, and we will provide feedback on something that **you** make.</span>

<span style="color:red">**Disclaimer 2: exam** We use this and the following assignments as preparation for the exam. We try to be as close as possible to the exam. However, be aware that not every question from the assignments will be present, and we may ask questions that are not included in the assignments.</span>


## Setup


### Code Submission

We have provided you with a repository (the same one this file is in) through Scalable Teaching, where you will submit any code you create.
Alongside this file, you will find a Laravel project with several tests.
To fulfill this assignment, all tests must be green.

To submit your assignment, simply push it to the repository.
Only the latest push before the deadline will be looked at, so feel free to save your progress as you go.

**Important!**
Remember to double-check that all of your changes are correctly committed and pushed to the GitLab repository we have provided.

### Questions Submission

In this assignment, you must answer three questions.
These questions are provided via assignments using itslearning.
The questions are:
* Question 1: Security & CRUD Operations
* Question 2: Performance, Scalability, & Greenability
* Question 3: REST

**Important!**
Similar to the previous assignment, we recommend that you work on the code first and then answer the questions.


## The application

In this assignment, you must develop authentication and authorization features for your previously developed CRUD operations.
Be aware of the URLs and HTTP methods used for every operation.
To do this, your application should consider:

* Database and Data
	* For developing purposes, you must develop migration files for setting up the database
	* For testing purposes, you must also populate the database with testing data
	* We will test your application using the first and second *User* of your database, so at least create two users
* Registration:
	* The header should contain a link to a registration page
	* The registration page should have a form to create a new user
	* When creating a new user, the user should be logged in and redirected to the index page
	* The header should display the name of the new user
* Log in:
	* The header should contain a link to a login page
	* The login page should have a form to log in an existing user
	* After submitting the form, the user should be logged in and redirected to the index page
	* The header should display the name of the new user
* Log out
	* The header should contain a link/form to log out the authenticated user
	* When logged out, the now *guest* user should be redirected to the index page
* Authorization - View:
	* Register and login links should only be visible to *guest* users
	* Login and logout links should only be visible to *authenticated* users
* Authorization - Controller:
	* Register and login actions should only be accessible to *guest* users
	* Login and logout actions should only be accessible to *authenticated* users
* Personalization:
	* A user should be able to *register* resources
		* Each user can register different resources
		* A resource can be registered by multiple users
		* *e.g.,* you can use Miguel's example, and build a *Wishlist* feature
	* This registration can then be accessed by a link located in the header
	* This new feature is only accessible by *authenticated* users
	* Different users will see different things on this new page, according to how they register resources

### Task 0: Data and Testing Configuration

To develop and test your application correctly, you should create migration files that enable developers to run and test it with their own database.
Moreover, you should also create seed files to enable databases to have testable data.
At the very least, you should have four resources and two users in the database using a seed.
These two users should have the same password: *password* (Please note that this is only for testing purposes).

### Task 1: Registration

The header should display a link to the registration page, with the text *Sign Up*.
When you click this link, the browser displays a registration form that includes three fields: the user's name, email address, and password. 
The names of these fields should be: name, email, and password.
Moreover, it should have a submit button with text *Submit*.

When the submit button is clicked, a new user is created in the database, and then this user is authenticated on the website.
The user is redirected to the index page, and the user's name is displayed in the header.

### Task 2: Log in

The header should display a link to the login page, with the text *Log In*.
When clicking this link, the browser displays a login form with two fields: email and password. 
The names of these fields should be: email and password.

When the submit button is clicked, an existing user from the database is authenticated on the website.
The user is redirected to the index page, and their name is displayed in the header.

If the email or password is incorrect, the user is redirected to the same Log In page, but in the form a new message appears: *Incorrect email or password*


### Task 3: Log out

The header should display a form with a single button to log out a user, with the text *Log Out*.
This form should have a `dusk="logout"` attribute and the POST HTTP method.
When submitting the form, the user should be logged out of the system and redirected to the index page.


### Task 4: Authorization - View

Some links and text in the application will change depending on whether the user has been authenticated or not. The users can only see:
* For non-authenticated users:
	* Registration and Log In links
* For authenticated users:
	* Log Out button
	* User name

### Task 5: Authorization - Controller

Some actions in the application should only be possible to execute, depending on whether the user has been authenticated or not. These actions are:
* For non-authenticated users:
	* Registration form (route to form should have the name *registration.create*)
	* User creation (route to user creation should have the name *registration.store*)
	* Log In form (route to form should have the name *login*)
	* Log In action (route to user login should have the name *login.store*)
* For authenticated users:
	* Log Out action (route to user login should have the name *login.destroy*)


### Task 6: Personalization

The final task is to incorporate a feature that enables users to register specific resources, allowing them to access those resources later.
While you can think of anything, you can also use Miguel's example: Wishlist.
To code this new feature, you should:
* Add a button to register the resource, in the show page of the resource itself
	* This button should be contained in a form
		* The form should have the HTTP POST action, and `dusk="resource-registration"` attribute.
	* The button should have the text *Add Resource*
	* When clicking, the resource is being added to the user
* While the show page is accessible to every type of user, the button should only appear to authenticated users
* The button action should only be executed by authenticated users
* A new link in the header should appear only for authenticated users, with text *Registered Resources*.
	* The link should go to a URL with the route name *registered.index*.
* When this link is clicked, a page only accessible by authenticated users should appear, showing at least the name of each registered resource

**Note 1:** It is not necessary to add a button to *de-register* the resource.
This task is considered completed by only registering resources.

**Note 2:** You can reflect on how to code this feature or even code it if you want.
The reflection *may* be a good question for a *particular* examination.


## Code reflections

In this assignment, you develop authenticating features for your web application.
Here, you should reflect on both security and non-functional features, as well as advanced features that you could apply in your application.

### Question 1: Security & CRUD Operations

In this activity, you secure your CRUD Operations for different types of users (non-authorized and authorized users).
Quickly explain how you secure these operations:
* Why is it important to restrict actions in the first place?
* How do you hide restricted actions from even appearing on the website?
* How do you ensure *smart* users cannot send requests to restricted actions?
* How do you validate (i.e., test) that you are restricting actions?

### Question 2: Performance, Scalability, & Greenability

Although the developed application is relatively small, you still need to assess its readiness for production, considering its performance and other non-functional requirements.
Answer the following questions:
* How can you validate the performance of the application? In other words, what steps must you take to ensure the application is ready according to its needs? (e.g., if you have an e-commerce site, a typical day vs black Friday)
* When do you need to scale your application? If necessary, how would you consider scaling your application for your specific use case? Is there any configuration you should change? (hint: where are you storing sessions?)
* How does performance relate to greenability? What changes would you make to your application (code, configuration, deployment, etc) to ensure it is running *green*?

### Question 3: REST

While your application is far from being *RESTful*, you can see that CRUD-type applications are not restricted to being only stateful:
* Why is your current application not *RESTful*?
* What changes do you need to apply to make it *RESTful*?
* Considering that we use sessions to store authenticated users, how *RESTful* applications impact this feature? Is it even possible to have authenticated users? Why?
