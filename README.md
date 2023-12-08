# Online Store Application

## Overview
This web application is a feature-rich online store built using Object-Oriented PHP, following the MVC (Model-View-Controller) design pattern. The application seamlessly integrates front-end technologies like HTML, CSS, Bootstrap, JavaScript, and jQuery for an engaging user experience. MySQL is employed as the database management system for efficient data storage and retrieval.

## Features

### Back-end
- **Object-Oriented PHP:** Utilized for robust server-side functionality.
- **MySQL Database:** Stores and manages product information and user data.
- **MVC Design Pattern:** Ensures a clear separation of concerns for maintainability.

### Front-end
- **CSS, Bootstrap, JavaScript, AJAX and jQuery:** Enhance the user interface and provide a responsive design.


### Server
- **Apache (Xampp):** Local server setup for development.

### Product Management
- **Display Products:** Lists products with detailed information and images.
- **Product Details:** Shows comprehensive information for selected products.
- **Dynamic Pricing:** Calculates total price based on selected product quantity.

### Shopping Cart
- **Session-Based Cart:** Allows both visitors and  users to add and remove products, displaying total price.
- **Cart Overview:** Shows all products in the cart with the total price.

### User Authentication
- **Separate Logins:** Users and administrators have distinct login routes.
- **Admin Panel Security:** Only accessible locally; 403 forbidden for external access.
- **Security Measures:** Prevents Possible LFi, SQL injection, XSS, and Brute Force Attacks.
- **Registration Check:** Ensures strong user registration credentials.

### User Interaction
- **Profile Page:** Displays the username of the logged-in user.
- **Conditional Sign-in Options:** Shows sign-in or log-out options based on user status.
- **Order Submission:** Users can submit orders, awaiting validation by administrators.
- **Email Notification:** Admins can send email notifications once orders are validated.

### Administration
- **Admin Control Panel:** Exclusive access to add/remove products and categories.
- **Pagination System:** Manages large datasets for an organized user experience.

## Usage
1. Clone the repository.
2. Set up Apache server using Xampp.
3. Import the provided MySQL database.
4. Access the application through the localhost.

Feel free to explore and enhance the codebase to meet your specific requirements. If you encounter any issues or have suggestions, please open an issue in the repository. Happy coding!
