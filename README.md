# Title: Calculator

Creating a calculator to calculate a customers discount who has multiple fixed and variable discounts based on department and company.

- Repository: `calculator`
- Link github: https://github.com/elduderinow/calculator
- Language: HTML, SCSS, PHP, SQL
- In team

## PURPOSE
The purpose of this project is to learn how to fetch data from a database using SQL, working with MCV pattern and OOP programming.

## FUNCTIONALITIES
- MVC pattern
- Dropdown to select customers (the selections keeps selected after submitting)
- List of products with an individual ADD button
- Checkout section where the added products appear with a DELETE button.
- Each SQL table gets loaded only once to reduce loading time and faster querying.
- Seperate objects for importing the entities with SQL, and for managing the entities.
- Detailed discount calculations
- Pagination
- No errors


## THE PROJECT ITSELF & OUR OPINION
It was a challenging project, we first thought it was easier than we expected. 

We could create a request for every query from the database, but instead we chose to only query each table once to save loading times and to use best practices because
when working with extremely big databases, the loading times can get quite high. 
Because of that we couldn't fetch the users group multiple times, so we created a nice recursive function to solve this problem.

Also we chose to not use two dropdowns as we wanted to replicate a webshop as realistic as possible, with a product list section and a checkout section where the user
can add and delete products. Because of that, we encountered several errors we needed to solve.

The project was nice, we learned a lot from it. We could do it the easy way, but there's no fun in that.

