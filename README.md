Description and database table
I made an e-commerce site that works on localhost. The technologies I use are (Html, CSS, PHP, JS). There are admin and user logins on the homepage. In the User Login (registering and logging in, adding and deleting products to the cart after logging in, and updating the product quantity, the stock quantity next to the product added to the cart and checking whether the selected product is there or not) are available. In the Admin Login, there are sections such as updating the stock quantity, tracking the cargo of the selected product, and deleting user records.


user table : 
CREATE TABLE `user_form` ( 
`id` int(100) NOT NULL AUTO_INCREMENT, 
`name` varchar(100) NOT NULL, 
`email` varchar(100) NOT NULL, 
`password` varchar(100) NOT NULL, 
PRIMARY KEY (`id`) 
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 
cart table : 
CREATE TABLE `cart` ( 
`id` int(100) NOT NULL AUTO_INCREMENT, 
`user_id` int(100) NOT NULL, 
`name` varchar(100) NOT NULL, 
`price` varchar(100) NOT NULL, 
`image` varchar(100) NOT NULL, 
`quantity` int(100) NOT NULL, 
PRIMARY KEY (`id`) 
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 
product table : 
CREATE TABLE `products` ( 
`id` int(100) NOT NULL AUTO_INCREMENT, 
`name` varchar(100) NOT NULL, 
`price` varchar(100) NOT NULL, 
`image` varchar(100) NOT NULL, 
`stock` int(11) NOT NULL, 
PRIMARY KEY (`id`) 
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 

CREATE TABLE `admin` ( 
`id` int(100) NOT NULL AUTO_INCREMENT, 
`email` varchar(100) NOT NULL, 
`password` varchar(100) NOT NULL, 
PRIMARY KEY (`id`) 
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 

Write this query in the phpMyAdmin structure section. Write the file name of the image in the image section in the product table. The products will be pulled from the database to the site. This is my 3rd grade web design and programming project assignment.
