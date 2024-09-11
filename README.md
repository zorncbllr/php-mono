# MONO - PHP FRAMEWORK

    Mono is an Object Oriented Based PHP Framework.
    It follows the MVC architecture.
    It comes with it's own commandline interface built with composer scripts.

## Creating a Controller

    Run command: 'composer gen con [name of controller class]'

    After running this command, a controller class snippet will be generated with a default index route handler.
    Routes are determined by the controllers defined inside the controllers folder.
    Base routes are anchored to the name of the controller class.

    example:

    controller: Home.php
    route: /home

## Creating a Model

    Run command: 'composer gen mod [name of model class]'

    After running this command, a model class snippet will be generated with default id attribute and a commented static function that you need to configure to depending on your liking.
    The name of the model class will be automatically saved as a table to the database.

    Model classes provides find, findById, update, and delete static methods.

    example:

        User::find() will return all rows in the table

## Creating a View

    Run command: 'composer gen view [name of view]'

    After running this command, a .view.php file will be generated with default html snippet and an h1 tag with the file's name.
    All parameters are provided by the $data associative array variable;

    example:

    <p> <?= $data["name"] ?> </p>
