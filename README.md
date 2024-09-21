# MONO - PHP FRAMEWORK

    Mono is a Fast, Object Oriented Based PHP Framework.
    It follows the MVC architecture.
    It comes with it's own commandline interface built with composer scripts.

## Creating a Controller

    ####Run command:
        mono -g con <filename>
    or
        mono gen controller <filename>
    or
        mono generate controller <filename>

    After running this command, a controller class snippet will be generated with a default index route handler.
    Routes are determined by the controllers defined inside the controllers folder.
    Base routes are anchored to the name of the controller class.

    example:

    controller: Home.php
    route: /home

## Creating a Model

    ####Run command:
        mono -g mod <filename>
    or
        mono gen model <filename>
    or
        mono generate model <filename>


    After running this command, a model class snippet will be generated with default id attribute and a commented static function that you need to configure to depending on your liking.
    The name of the model class will be automatically saved as a table to the database.

    Model classes provides find, findById, update, and delete static methods.

    example:

        User::find() will return all rows in the table

## Creating a View

    ####Run command:
        mono -g vw <filename>
    or
        mono gen view <filename>
    or
        mono generate view <filename>


    After running this command, a .view.php file will be generated with default html snippet and an h1 tag with the file's name.
    All parameters are provided by the $data associative array variable;

    example:

    <p> <?= $data["name"] ?> </p>

## Routing with Mono

    Routes are determined by the controller classes inside the controllers folder.
    Base routes are named after the file name of the controller.

    example:

        controller: Users.php
        route: /users

    By default, Home.php or /home refers to the root path. Mono would recognize a controller with Home.php filename as the root controller. hence, / is the same as /home.

## Nested Routes

    Nested routes are routes added after the base route.

    example:
        /users/profile

    where profile is the nested route.

    In Mono, to create a nested route handler, you have to create a controller class and another function with Route attribute on the top.

#### example implementation:

    <?php

    class Users extends Controller {

        #[Route(method: 'GET')]
        public function index(Request $request){

            return 'Users controller';
        }

        #[Route(path: "/profile", method: "GET")]
        public function index(Request $request){

            return 'this is the profile route handler';
        }
    }

#### explanation:

    In creating a route handler, you need to specify its Route attribute --- the route path and method.
    By default, path is pointing at the name of the controller class, thus, you only need to add the specified path like '/profile' to the path.

    path result:
        /users/profile

## Dynamic Routes

    Dynamic routes in Mono are the same with other frameworks.
    You need to add a semicolon at the beginning of the route.

#### example:

    /users/:id

    id route will be dynamic and can contain dynamic values.

#### implementation:

    <?php

    class Users extends Controller {

        #[Route(method: 'GET')]
        public function index(Request $request){

            return 'Users controller';
        }

        #[Route(path: "/:id", method: "GET")]
        public function index(Request $request){

            return 'id = ' . $request->param["id"];
        }
    }

#### explanation:

    If a route includes an specified :id, the route handler will run regardless of the value being passed into the :id.

    example:
        users/12
        users/11
        users/7

        id = 12
        id = 11
        id = 7

## Parsing Dynamic Route Param

    To parse the route parameter in Mono, you need to access the $request parameter that's availble to you.

    The request object $request includes various data and one of those data is the route param.

#### accessing the value:

    The value is within the $request object in param array.
    To access the value, simply specify the name of the parameter.

    example:

       path: /users/:id
       accessing: $request->param["id"]

## Query Parameters in Mono

    Query parameters can be found within the $request object provided by mono function handlers.
    To access the value of the query parameter, simply specify the query key.

#### example:

    path: /users?id=12
    query parameter: $request->query["id"]

## Request Methods in Mono

    You can specify the request methods in the Route attribute located on top of the route function handlers.

#### example:

    #[Route(method: "POST")]

    Request methods may include GET, POST, PATCH, PUT, and DELETE.
    Routes with the same path but different request methods will be treated and handled differently.

## The Route Attribute

    Route attributes are similar to annotations in other programming languages.
    The route attribute determines the path, as well as the method a route handler will be handling.

#### example:

    <?php

    class Home extends Controller
    {
        #[Route(method: 'GET')]
        public function index(Request $request)
        {
            return view("Home");
        }
    }

#### explanation:

    In this example, the route handler function named 'index' has an attribute of Route with the path poiting at home and a request method of GET.

    The function will only run if the route path given matches the request url.

## Views in Mono Framework

    Mono provides a way to render a view or an HTML template inside PHP.
    Views file have an extension of .view.php
    Views file can be returned by the controller route handler functions.
    Views contains all the HTML tags and templates being rendered by the server.

#### Returning a View

    <?php

    class Home extends Controller
    {
        #[Route(method: 'GET')]
        public function index(Request $request)
        {
            return view("Home");
        }
    }

    To return a view, simply call the view() function and specify the filename of the .view.php file as the first argument.

#### Returning a View with Argument

    <?php

    class Home extends Controller
    {
        #[Route(method: 'GET')]
        public function index(Request $request)
        {
            return view("Home", [
                "name" => "KENDRICK"
            ]);
        }
    }

    You can add a second argument to the view() function as an associative array to pass in data from the controller to the view.
    You can access the value inside the $data array variable.

#### example:

    Controller:

    #[Route(method: 'GET')]
    public function index(Request $request)
    {
        return view("Home", [
            "name" => "KENDRICK"
        ]);
    }

    View:

    <h1> <?= $data["name"] ?> </h1>

#### explanation:

    If you pass in a value as the second argument in the view() function, the value can be accessed inside the $data varible just by specifying the key assigned.

## Models in Mono

    Model classes are blueprints of a database table.
    Model classes can have columns specified in your database table.

    The name of the model class represents the name of the table inside your database.

    For example:
        User.php refers to the user table inside your database.

    Model classes also provides built in static functions that you can use to read, create, update, and delete data inside your database.

## Model Class Methods

    Model class methods includes:

    - create()
    - delete()
    - update()
    - find()
    - findById()
    - query()
    - createTable()
    - initModels()

## CreateTable Method in Mono

    The createTable() method is used to initialize and configure your database table.
    You can specify the name, type, and other configurations of your columns by using this method.
    Note that createTable() method will only create a new table if it is not yet created.

#### example implementation:

    <?php

    class User extends Model
    {
        public $id, $username, $password;

        public function __construct($id = null, $username = null, $password = null)
        {

            $this->id = $id;
            $this->username = $username;
            $this->password = $password;

            self::createTable("
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(100) NOT NULL
            ");
        }
    }

## Create Method

    The create() method provided by the Model class creates and saves a new Model to the database table.

#### example implementation:

    $new_user = new User(
        username: "zornnn",
        password: "zorn123456"
    );

    User::create($new_user);

#### explanation:

    The create() method accepts a User object with the same type.
    It then creates a new row and saves the newly created row into the table.

    Syntax:
        Model::create(new Model(...))

    The create() method returns a boolean value, which then can be used to handle invalid inputs and failed creation.

    Hence, should be:

    $new_user = new User(
        username: "zornnn",
        password: "zorn123456"
    );

    $isCreated = User::create($new_user);

    if ($isCreated){
        return 'new user is created';
    } else {
        return 'failed to create new user';
    }