# About LaraLiber

A simple web application that allows a user to create and manage a list of book that they would like to read.

- Search for books to add from Google Books
- Users can not only view their book list in the UI, but also access a restful endpoint for the books on a user's list.
- Users can: add/remove books, sort books, cusomize their sorting, and mark books as read.
- Detail's about a book are available with a click.

# Hosting and Deployment

This application is hosted on Heroku to take advantage of their continuous integration.

There are 2 environments hosted on Heroku:

- **[Staging](http://staginglaraliber.herokuapp.com/)**
    * http://staginglaraliber.herokuapp.com/
    * This is linked to the 'master' branch
- **[Production](http://laraliber.herokuapp.com/)**
    * http://laraliber.herokuapp.com/
    * This is linked to the 'master' branch

Deployment is initiated by pushing changes to on of these branches. 

**Initial deployment will need to run laravel's migrations and seeders from the restpective app bash environments.**

    php artisan migrate
    php artisan db:seed --class=AddUsers
    php artisan db:seed --class=AddInitialBooks

# Credentials and other info

Since this is a demo app, the credentials are already entered into the login form for you.

If you find yourself need them again the simple login is below.

- User email: jake+tester@jmctyre.com
- Password: tester

___

LaraLiber was created using Laravel 8 and Vue.js 3

Thanks for taking a look!
You can contact me at jake@jmctyre.com
