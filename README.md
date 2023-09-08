# Restful_Facebook_Apis

<p>This repository houses a collection of custom APIs built using PHP (with Laravel) specifically designed for Facebook integration tailored to your unique needs. Our APIs offer flexibility and control, enabling you to manage users, posts, and comments seamlessly within the Facebook platform.

Explore these APIs and take full charge of your Facebook integration to create engaging and personalized user experiences.
</p>
<br>
<hr>
<br>

## Project Overview

This project leverages advanced PHP features and the Laravel framework to provide a comprehensive solution for integrating with Facebook. Key components and features include:

- **Database Integration**: We utilize Laravel's Eloquent ORM and migrations to create and manage the database schema.

- **Routing Component**: Our custom routing component handles API endpoints, directing requests to the appropriate controllers.

- **Validator Component**: We've implemented a robust validator component to ensure data integrity and security.

- **MVC Design Pattern**: The project adheres to the Model-View-Controller (MVC) design pattern, promoting clean code organization and maintainability.

- **Pagination**: For list output in user and post endpoints, we've implemented pagination to enhance performance.

- **Composer**: Dependencies are managed using Composer for easy project setup and maintenance.

- **.htaccess**: We've included an `.htaccess` file for Apache web server configuration.

  <br>
<hr>
<br>

  ## Authentication and Authorization

Our project utilizes basic authentication to verify user credentials and ensure access control. Here's how it works:

- **Basic Authentication**: We use basic authentication to verify user credentials before granting access to protected API endpoints.Only users with valid usernames and passwords can authenticate and access the APIs.

- **Authorization**: After authentication, the project includes authorization checks to control what authenticated users can do within the application.This ensures that users are only able to perform actions they are authorized for, based on their roles and permissions.

By combining basic authentication and authorization, we maintain the security and integrity of our APIs, allowing users to interact with the application while ensuring they only perform authorized actions.

<br>
<hr>
<br>

## Getting Started

Follow these steps to set up and use the project:

1. **Clone the Repository**: Clone this repository to your local machine.

```pash
get clone git@github.com:salehzt100/Restful_Facebook_Apis.git
```
2. Create a .env File: Create a .env file in the project root directory with the following content, replacing the placeholders with your actual database configuration:

```env
DB_DRIVER = mysql
DB_HOST = 127.0.0.1
DB_NAME = Facebook-API
DB_USERNAME = root
DB_PASS =


```
   

3. **Install Dependencies**: Use Composer to install project dependencies.

```pash
 composer install
 ```


4. **Database Setup Run Migrations**: Create the database schema by sending a POST request to the `{{facebook.com}}/database/migration.script.php` endpoint using a tool like Postman. [Facebook API Postman Collection](https://www.postman.com/planetary-sunset-839501/workspace/new-team-workspace/collection/28169960-14a0d732-94e8-4a49-9ef4-e9c5febc527a)

5. **Start Development Server**: Start the development server.


6. **Explore API Endpoints**: Use the Postman collection linked above to explore and test the API endpoints.


<br>
<hr>
<br>

## API Endpoints

Our project provides a comprehensive set of API endpoints for Facebook integration:

### User APIs

- **Create User**: Register new users.
- **Update User**: Modify user information.
- **Delete User**: Remove user profiles.
- **Show List Of Users**:  a list of all users.
- **Show User**: View detailed user .

### Post APIs

- **Create Post**: Publish new posts.
- **Update Post**: Edit post content.
- **Delete Post**: Remove posts.
- **Show List Of Posts**:  a list of all Posts for a specific user.
- **Show Post**: View detailed Post .


### Like APIs

- **Like Post**: Express appreciation for posts.
- **Unlike Post**: Remove likes from posts.

### Comment APIs

- **Create Comment**: Add comments to posts.
- **Update Comment**: Edit existing comments.
- **Show Comments**:  a list of post comments .

These APIs enable you to manage users, posts, likes, and comments effectively for a seamless Facebook integration experience.

