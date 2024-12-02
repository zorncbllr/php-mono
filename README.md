# MONO - PHP FRAMEWORK

Mono is a Powerful and Fast, Object Oriented Based PHP Framework.
It follows the MVC architecture.
It comes with its own commandline interface.

## Serving the Project

#### Run command:

    php mono serve

or

    php mono -s

## Creating a Controller

#### Run command:

    php mono -g con <filename>

or

    php mono gen controller <filename>

or

    php mono generate controller <filename>

After running this command, a controller class snippet will be generated with
a default index route handler.

example controller:

    <?php

    class Home extends Controller
    {
        #[Get()]
        public function index(Request $request)
        {
            return 'Home Controller';
        }
    }

Mono uses hybrid file-based routing and Route attributes for flexible
and faster routing.

Base routes are anchored to the name of the controller class.

example:

    controller: Home.php
    route: /home

## Creating a Middleware

#### Run command:

    php mono -g mid <filename>

or

    php mono gen middleware <filename>

or

    php mono generate middleware <filename>

After running this command, a new middleware class snippet will be
generated with a runnable method that would be executed once a middleware
is instanciated inside a middleware attribute.

example:

    <?php

    use App\Core\Middleware;

    class Auth extends Middleware
    {
        static function runnable(Request $request, callable $next)
        {
            echo 'Auth Middleware';

            return $next();
        }
    }

Use the $next() callable function to move to the next middleware
or controller.

You may return void, json(), view(), and redirect() in handling
bad requests or unauthenticated requests.

You may also use a native jwt token inside the middleware,
which is the recommended place for authentication and
authorization processes.

example with native jwt token:

    <?php

    use App\Core\Middleware;

    class Auth extends Middleware
    {
        static function runnable(Request $request, callable $next)
        {
            $jwt = $request->cookies['auth_token'] ?? "";

    	    $key = "sample_secret_key";

    	    $payload = Token::verify($jwt, $key);

    	    if (!$payload) {
    	    	return redirect('/login');
    	    }

    	    return $next();
        }
    }

#### Using Middlewares

To use a middleware, add an attribute on top of your target method
to which you wish to apply the middleware.

Create a new instance of your middleware inside the Middleware attribute
constructor.

example:

    <?php

    class Home extends Controller
    {
        #[Get()]
        #[Middleware(new Auth)]
        public function index(Request $request)
        {
            return view('Home');
        }
    }

Middlewares may also be applied on the controller class itself.
This way, the middleware will be able to handle authentication
and authorization processes for the whole base route.

example:

    <?php

    #[Middleware(new Auth)]
    class Home extends Controller
    {
        #[Get()]
        public function index(Request $request)
        {
            return view('Home');
        }
    }

## Creating a Model

#### Run command:

    php mono -g mod <filename>

or

    php mono gen model <filename>

or

    php mono generate model <filename>

After running this command, a model class snippet will be generated with default
id attribute and a commented static function that you need to configure
depending on your liking.

#### Generated example Model

    <?php

    class Product extends Model {
        private $id;
        
        public function __construct($id = null)
        {
            $this->id = $id;
        }
    }

Use the self::createTable() method to create a new table.

The name of the model class will be automatically saved as
a table to the database.

Model class provides find(), findById(), create(), update(),
delete(), and initModel() out of the box.

example:

    // returns all rows in the table.
    User::find();

    // returns either the user or false if not found.
    User::find(['email' => $email]);

    // returns specific user from the database.
    User::findById();

    // creates new user in the database.
    User::create();

#### Model Auto-Completion

You may automatically initialize all your model attributes/properties
and generates getters and setters for all private properties of your model schema.

just run the command:

    php mono -f <model name>

or

    php mono fill <model name>

example:

        php mono fill user

After running the command, getters and setters for all specified
attributes within the class will be generated automatically, as well as
the initialization of the attributes.

You will also be provided with configuration options to configure
your rows in the database within the initModel() function.

example:

    public static function initUser()
    {
    	self::createTable('
    		name VARCHAR(20) NOT NULL,
    		email VARCHAR(20) NOT NULL,
    		id INT AUTO_INCREMENT PRIMARY KEY,
    		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    		password VARCHAR(20) NOT NULL
    	');
    }

Within this function inside your model, you can configure what your
table columns should look like.

The syntax comes from SQL code that is being passed in the createTable() method.

## Creating a View

#### Run command:

    mono -g vw <filename>

or

    mono gen view <filename>

or

    mono generate view <filename>

After running this command, a .view.php file will be generated with
default html snippet and an h1 tag with the file's name.

example view:

    <!DOCTYPE html>
    <html lang='en'>

    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Home</title>
    </head>

    <body>
        <h1>Mono</h1>
        <h3>The Fast, Scalable, Object-Oriented PHP Framework.</h3>
        <p>HELLO WORLD!</p>
    </body>

    </html>

You can return view( filename , [ options ] ) to pass data from your
controller. the keys in your options will be evaluated as a variable.

example:

controller:

    return view('Home', [ 'name' => 'John Doe' ]);

view:

    <p> <?= $name ?> </p>

Note that variables in the view depends on the its key being passed
as the second argument to the view() function.

## Creating a Service

#### Run command:

    php mono -g ser <filename>

or

    php mono gen ser <filename>

of

    php mono generate service <filename>

After running the command, a new service class will be generated with the
the same methods as the controller class but only static.

Controller class will be automatically created if a controller class
for the service is not yet created.

example:

    <?php

    class ProductsService
    {
        static function index(Request $request)
        {
            $product = [
                'name' => 'Brandyy',
                'brand' => 'Brand X',
                'expiration' => 'November 23, 2030'
            ];

            return json([
                'product' => $product
            ]);
        }
    }

usage:

    <?php

    class Products extends Controller
    {
        #[Get()]
        public function index(Request $request)
        {
            return ProductsService::index($request);
        }
    }

Using services can be efficient when the logic is too complex.

Services separates the logic from route handling for better
code refactoring and better code structuring.

Using services will help your code to be more organized and
readable.

## Routing with Mono

Mono uses hybrid file-based routing. Thanks to its file-based characteristics,
the framework can easily find existing routes, resulting in much faster performance.

It is labelled as hybrid because it utilizes route attributes alongside file routing
system. Because of this, Mono is not only performant in terms of speed, it is also 
flexible enough to handle complex and nested uri routes. 

Base routes are named after the name of file for the controller.

example:

    <?php

    class Home extends Controller
    {
        #[Get()]
        public function index(Request $request)
        {
            return ProductsService::index($request);
        }
    }

By default, Home.php or /home refers to the root path.

Mono would recognize a controller with Home.php filename as the root controller.
hence, / is the same as /home.

## Nested Routes

Nested routes are routes added after the base route.

example:

    /users/profile

where profile is the nested route.

In Mono, to create a nested route handler,
you have to create a controller class and another function
with specified http method attribute on the top.

example implementation:

    <?php

    class Users extends Controller 
    {
        #[Get()]
        public function index(Request $request)
        {
            return 'Users controller';
        }

        #[Get('/profile')]
        public function index(Request $request)
        {
            return 'this is the profile route handler';
        }
    }

#### explanation:

Mono makes use of php attributes such as Get, Post, Patch, Put, and Delete that are extension of Route attributes.
You may specify the route inside of these attributes.

By default, path is pointing at the name of the controller class,
thus, you only need to add the specified path like '/profile' to the path.

path result:

    /users/profile

## Dynamic Routes

Dynamic routes in Mono are the same with other frameworks.
You need to add a semicolon at the beginning of the route.

example:

    /users/:id

id route will be dynamic and can contain dynamic values.

example implementation:

    <?php

    class Users extends Controller 
    {
        #[Get()]
        public function index(Request $request)
        {
            return 'Users controller';
        }

        #[Get('/:id')]
        public function index(Request $request)
        {
            return 'id = ' . $request->param["id"];
        }
    }

If a route includes an specified :id,
the route handler will run regardless of the value being passed into the :id.

example:

    users/12
    users/11
    users/7

    id = 12
    id = 11
    id = 7

## Parsing Dynamic Route Param

To parse the route parameter in Mono, you need to access
the $request parameter that's availble to you.

The request object $request includes various data and
one of those data is the route param.

#### accessing the value:

The value is within the $request object in param array.
To access the value, simply specify the name of the parameter.

example:

    path: /users/:id

accessing:

    $request->param["id"]

## Query Parameters in Mono

Query parameters can be found within the $request object
provided by mono function handlers.

To access the value of the query parameter, simply specify the query key.

example:

path:

    /users?id=12

query parameter:

    $request->query["id"]

## Request Methods in Mono

Request methods may include Get, Post, Patch, Put, and Delete.
Routes with the same path but different request methods will be
treated and handled differently.

## The Route Attributes: Get(), Post(), Patch(), Put(), Delete()

Route attributes are similar to annotations in other programming languages.

There are five route attributes available in Mono: Get, Post, Patch, Put, and Delete.

Note that route attributes are dependent on the controller they are implemented from.
This means that if you are in a controller with for example, named Home,
it would automatically assume that home is your base route.

To implement a route handler, you need to specify the right route attribute for your desired request method.
You may also provide additional uri path as an argument to these attributes.

By default, if no arguments are provided, Mono assumes that it corresponds to the base route named after the controller.

example:

    <?php

    class Home extends Controller
    {
        #[Get()]
        public function index(Request $request)
        {
            return view("Home");
        }

        #[Get('/profile')]
        public function index(Request $request)
        {
            return view("Profile");
        }
    }

uri routes:

    /home
    /home/profile

In this example, the route handler function named 'index' has an attribute
of Get with the path poiting at home.

Notice that you only need to specify the nested path which is /profile
instead of including /home. This is because /profile is within the Home controller,
thus Mono assumes that it is already related to the home route, abstracting it from
the route path.

## Views in Mono Framework

Mono provides a way to render a view or an HTML template inside PHP.
View files have an extension of .view.php
View files can be returned by the controller route handler functions.
View contains all the HTML tags and templates being rendered by the server.

#### Returning a View

    <?php

    class Home extends Controller
    {
        #[Get()]
        public function index(Request $request)
        {
            return view("Home");
        }
    }

To return a view, simply call the view() function and
specify the filename of the .view.php file as the first argument.

You can add a second argument to the view() function as an
associative array to pass in data from the controller to the view.

example:

Controller:

    <?php

    class Home extends Controller {
        #[Get()]
        public function index(Request $request)
        {
            return view("Home", [ "name" => "KENDRICK" ]);
        }
    }

View:

    <h1> <?= $name ?> </h1>

If you pass in a value as the second argument in the view() function,
the value can be accessed within a variable with the same naming convention as specified
in your key within the associated array options that you've passed.

## Models in Mono

Model classes are blueprints of a database table.

Model classes can have columns specified in your database table.

The name of the model class represents the name of the table inside your database.

For example:

    User.php refers to the user table inside your database.

Model classes also provides built in static functions that
you can use to read, create, update, and delete data inside your database.

## Model Class Methods

Model class methods includes:

- create()
- delete()
- update()
- find()
- findById()
- query()
- migrateModel()
- initModels()

## MigrateModel Method in Mono

The migrateModel() method is used to initialize and configure your database table.
You can specify the name, type, and other configurations of your columns
by using this method.

After configuring, simply run the command 'php mono db push' to migrate your models into your database.

Make sure to provide the correct database configurations inside the src/config folder.

example implementation:

    <?php

    class User extends Model
    {
        public $id, $username, $password;

        public function __construct($id = null, $username = null, $password = null)
        {
            $this->id = $id;
            $this->username = $username;
            $this->password = $password;
        }

        public static function initUser()
        {
            self::migrateModel("
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(100) NOT NULL
            ");
        }
    }

## Create() Method

The create() method provided by the Model class creates and saves
a new Model to the database table.

example implementation:

    $new_user = new User(
        username: "zornnn",
        password: "zorn123456"
    );

    User::create($new_user);

The create() method accepts a User object with the same type.
It then creates a new row and saves the newly created row into the table.

Syntax:

    Model::create(new Model(...))

The create() method returns a boolean value,
which then can be used to handle invalid inputs and failed creation.

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

## Find() and FindById() Methods

For each model schema you create, you will have access to find()
and findById() methods.

find() method is used to get all of the data from your table.

Mono will automatically assume and use the name of your model
schema class as the table name inside your database.

example:

Model schema class name:

    User

Database table name:

    users

example implementations:

    // returns all the users in thes users table.
    $user = User::find();

    // returns a specific user based on the username.
    // returns false if not found.
    $user = User::find(['username' => $username])

The find() method returns an array of User objects, hence
each element posesses the properties and methods you have specified
inside the User model class, such as the getters and setters.

##### findById() implementation

For this example, assume again that you have a model user inside
your models folder.

example:

        $user = User::findById( id: 1 );

findById() method takes and int $id parameter to find specific data
by its id attribute in the database.

findById() method returns an object of the model class that
issued the method.

In this example, findById() returns an object instance of User.
All public attributes and methods inside the User model class will
be accessible in the object returned by this method.

## Update() method

The update method lets you update a specific data in your database
table.

It takes one argument, your model object, and updates the data
by its id.

The $id variable is important in this method for targeting the
right data in your database table.

In this example, assume that you have a model class User with
variables $id, $username, and $password

example:

    $user = User::findById( id: 1 );

    $user->setUsername('new_sername_example');

    User::update($user);

The update method returns a boolean value which you can use
to stuffs like checking if the operation was successfull.

example:

    $user = User::findById( id: 1 );

    $user->setUsername('new_sername_example');

    $is_updated = User::update($user);

    if ($is_updated) {
        return 'user updated successfully';
    }

## Delete() method

The delete() method lets you delete and remove specific data from
your database table.

The delete() method takes one argument, either an object or an integer
variable.

Using integer as an argument will speed up the process of deleting.

The delete() method returns a boolean value which you can use to check
if the operation was successful.

example:

    $is_deleted = User::delete( target: 1 );

    if ($is_deleted) {
        return 'user deleted successfully';
    }

or

    $user = User::findById( id: 1 );

    $is_deleted = User::delete($user);

    if ($is_deleted) {
        return 'user deleted successfully';
    }

## Native JWT Token class: sign() and verify() methods

In Mono, you will be provided with the native jwt token with static methods
sign() and verify() which you can use to authenticate users.

The sign() method generates a hashed token, with the provided payload and secret key.

example with sign():

    $payload = [
        'userId' => 07737477
    ];

    $secret = 'hGS7asBcczaUcsa';

    $jwt = Token::sign($payload, $secret);

You may also specify the token expiration as the third argument.

example with expiration:

    $payload = [
        'userId' => 07737477
    ];

    $secret = 'hGS7asBcczaUcsa';

    $jwt = Token::sign($payload, $secret, 60 * 60 * 24);

You can use verify() method to check the validity of the token.
this method returns the decrypted payload if the token is valid,
and returns false if invalid.

example with verify():

    $payload = Token::verify($payload, $secret);

    // if invalid and returned false.
    if (!$payload) {
        return redirect('/login');
    }

You can use both methods to handle authentication process with your app.

## Validator class

In Mono, you will be provided with the Validator class to for validating
your data by passing the filters as the first argument and the data as the
second argument.

example:

    $email = $request->body['email'];
    $password = $request->body['password'];

    $result = new Validator([
        'email' => [
            'type' => 'email',
            'required' => true,
        ],
        'password' => [
            'required' => true,
            'length' => [
                'min' => 8,
                'max' => 50
            ],
            'message' => 'password must be at least 8 characters.'
        ]
    ], ['email' => $email, 'password' => $password]);

    if (!$result->isValid()) {
        return json(['errors' => $result->getErrors()]);
    }

    return json(['msg' => 'registered successfully.']);

You may specify the type, required, length with minimum and maximum, and
add your custom message. By default if message is not explicitly provided,
the default error message will be used.

You can check the validity by using the isValid() method.
You can also get all errors using the getErrors() getter method.
