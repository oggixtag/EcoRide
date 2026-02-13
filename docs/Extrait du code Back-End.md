# Back-End Code Extraction & MVC Analysis

This document analyzes the custom-built PHP MVC (Model-View-Controller) architecture of the EcoRide project. It extracts key code segments from each layer of the architecture to explain their roles and interactions.

---

## 1. The Bootstrap & Routing Process

The application follows a "Front Controller" pattern, where all HTTP requests are directed to a single entry point: `public/index.php`. This file is responsible for initializing the application and routing the request to the appropriate controller.

### 1.1. Rationale for Extraction

`index.php` is the most critical file for understanding the request lifecycle. It shows how the application starts, how classes are loaded, and how a URL parameter is translated into a specific controller action. This simple, yet effective, routing mechanism is the foundation of the entire framework.

### 1.2. Schematic Flow: Request Lifecycle

```mermaid
graph TD
    A[HTTP Request] --> B[public/index.php];
    B --> C[Define ROOT constant];
    C --> D[Load app/App.php];
    D --> E[Call App::load()];
    subgraph App::load()
        F[session_start()]
        G[Register Autoloaders]
    end
    E --> F --> G;
    G --> H[Parse '?p=' parameter from URL];
    H --> I{Routing logic (if/elseif)};
    I -- '?p=utilisateurs.login' --> J[Instantiate UtilisateursController];
    J --> K[Call login() method];
    I -- other routes --> L[...];
```

### 1.3. Source Code: `public/index.php`

```php
<?php
define('ROOT', dirname(__DIR__));
// Protection contre le Clickjacking
header('X-Frame-Options: DENY');
require(ROOT . '/app/App.php');
App::load();

if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 'home';
}

// Simple router based on the 'p' parameter
// Example for login:
if ($page === 'utilisateurs.login') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->login();
} 
// Example for user dashboard:
elseif ($page === 'utilisateurs.index') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->index();
} 
// ... dozens of other elseif conditions for all other routes
elseif ($page === 'home') {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->index();
}
// ...
?>
```

---

## 2. The Core Application & Service Container

The `App` class acts as the heart of the framework. It uses a Singleton pattern to provide a single, global point of access to core services, most notably the database connection and model factories.

### 2.1. Rationale for Extraction

`App.php` demonstrates how the application manages its dependencies, a concept known as a Service Container or Factory. The `getTable()` method is a perfect example of a factory pattern, dynamically creating model instances while injecting the required database dependency. This is a fundamental aspect of the project's architecture, ensuring that components are loosely coupled and the database connection is managed efficiently.

### 2.2. Schematic Flow: Model Factory (`getTable()`)

```mermaid
graph TD
    A[Controller calls App::getInstance()->getTable('Utilisateur')] --> B[getTable('Utilisateur') method starts];
    B --> C[Construct class name: '\NsAppEcoride\Model\UtilisateurModel'];
    C --> D[Call getDb() to get database instance];
    subgraph getDb()
        E{db_instance exists?}
        E -- No --> F[Load config file];
        F --> G[Create new MysqlDatabase instance];
        G --> H[Store instance in db_instance];
        E -- Yes --> H;
        H --> I[Return db_instance];
    end
    D --> I;
    I --> J[Instantiate new UtilisateurModel(db_instance)];
    J --> K[Return the model instance to Controller];
```

### 2.3. Source Code: `app/App.php`

```php
<?php

use NsCoreEcoride\Config;
use NsCoreEcoride\Database\MysqlDatabase;

class App
{
    private static $_instance;
    private $db_instance;

    /**
     * Gets the single instance of the App (Singleton Pattern).
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new App();
        }
        return self::$_instance;
    }

    /**
     * Loads essential components like session and autoloaders.
     */
    public static function load()
    {
        session_start();
        require ROOT . '/app/Autoloader.php';
        NsAppEcoride\Autoloader::register();
        require ROOT . '/core/Autoloader.php';
        NsCoreEcoride\Autoloader::register();
        // ...
    }

    /**
     * Factory method to get a Model instance.
     * @param string $name The name of the model (e.g., 'Utilisateur')
     * @return object An instance of the requested model.
     */
    public function getTable($name)
    {
        $class_name = '\NsAppEcoride\Model' . ucfirst($name) . 'Model';
        // Dependency Injection: the DB instance is passed to the model's constructor.
        return new $class_name($this->getDb());
    }

    /**
     * Gets the single database connection instance.
     */
    public function getDb()
    {
        $config = Config::getInstance(ROOT . '/config/config.php');
        if (is_null($this->db_instance)) {
            $this->db_instance = new MysqlDatabase(
                $config->get('db_name'), 
                $config->get('db_user'), 
                $config->get('db_pass'), 
                $config->get('db_host')
            );
        }
        return $this->db_instance;
    }
}
```

---

## 3. The Controller Layer

Controllers are the "traffic cops" of the MVC architecture. They receive requests from the router, interact with the necessary models to fetch or modify data, and then select a view to render the response.

### 3.1. Rationale for Extraction

The `UtilisateursController::login()` method is a classic example of a controller's responsibilities. It handles user input (`$_POST`), uses a dedicated `DbAuth` service to perform business logic (authentication), and makes a decision based on the result: either redirect to the dashboard on success or re-render the login form with an error message on failure. The base `Controller::render()` method is also extracted to show the core view-rendering logic.

### 3.2. Schematic Flow: `login()` Action

```mermaid
graph TD
    A[Request for 'utilisateurs.login'] --> B[UtilisateursController->login() is called];
    B --> C{Is request method POST?};
    C -- No --> D[Prepare empty form];
    C -- Yes --> E[Instantiate DbAuth service];
    E --> F[Call auth->login() with POST data];
    F --> G{Login successful?};
    G -- Yes --> H[Redirect to user dashboard];
    H --> I[End Request];
    G -- No --> J[Set error flag];
    J --> D;
    D --> K[Call render('utilisateurs.login', ...)];
    K --> L[End Request];
```

### 3.3. Source Code: `core/Controller/Controller.php` and `app/Controller/UtilisateursController.php`

**Base Controller:**
```php
<?php
namespace NsCoreEcoride\Controller;

class Controller
{
    protected $viewPath;
    protected $template;

    /**
     * Renders a view within a template.
     * @param string $view The view file to render.
     * @param array $variables Variables to be extracted for the view.
     */
    protected function render($view, $variables = [])
    {
        ob_start();
        extract($variables); // Makes variables available in the view file
        
        // Render the specific view file (e.g., login.php)
        require($this->viewPath . str_replace('.', '/', $view) . '.php');
        
        $content = ob_get_clean(); // Capture the output
        
        // Render the main template (e.g., default.php), which will use $content
        require($this->viewPath . 'templates/' . $this->template . '.php');
    }
    // ... other methods like forbidden(), notFound()
}
```

**Utilisateurs Controller Action:**
```php
<?php
namespace NsAppEcoride\Controller;

use \NsCoreEcoride\Auth\DbAuth;
use \App;

class UtilisateursController extends AppController
{
    // ... constructor and other methods

    /**
     * Handles the login page and authentication process.
     */
    public function login()
    {
        $errors = false;

        if (!empty($_POST)) {
            $auth = new DbAuth(App::getInstance()->getDb());

            if ($auth->login($_POST['pseudo'], $_POST['password'])) {
                header('Location: index.php?p=utilisateurs.index');
                exit;
            } else {
                $errors = true; // Set error flag for the view
            }
        }

        // Render the login view
        $this->render('utilisateurs.login', compact('errors'));
    }
    
    // ... other actions like logout(), inscrir(), etc.
}
```

---

## 4. The Model Layer

The Model layer is responsible for all data-related logic. It interacts directly with the database to retrieve, insert, update, and delete data. Each model class typically corresponds to a single database table.

### 4.1. Rationale for Extraction

The base `Model` class is crucial as it implements the core data access logic and establishes conventions (like automatically determining the table name). The `query()` method shows how it abstracts away the database-specific preparation and execution of SQL queries. `UtilisateurModel` is extracted to show how a concrete model extends this base class to provide specific, readable methods like `findByPseudo()`, encapsulating the SQL logic away from the controllers.

### 4.2. Schematic Flow: Model Data Retrieval

```mermaid
graph TD
    A[Controller calls UtilisateurModel->findByPseudo('john')] --> B[findByPseudo('john') method starts];
    B --> C[Calls its own generic query() method];
    subgraph query() method
        D[Constructs Entity class name ('UtilisateurEntity')]
        E{Attributes provided?}
        E -- Yes --> F[Calls db->prepare(SQL, attributes, ...)];
        E -- No --> G[Calls db->query(SQL, ...)];
    end
    C --> D;
    D --> E;
    F --> H[Returns result from DB layer];
    G --> H;
    H --> I[Returns result to findByPseudo()];
    I --> J[Returns the User entity to the Controller];
```

### 4.3. Source Code: `core/Model/Model.php` and `app/Model/UtilisateurModel.php`

**Base Model:**
```php
<?php
namespace NsCoreEcoride\Model;

use NsCoreEcoride\Database\Database;

class Model
{
    protected $table;
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
        // If the child class doesn't define a $table, guess it from the class name.
        if (is_null($this->table)) {
            $part = explode('', get_class($this));
            $class_name = end($part);
            $this->table = strtolower(str_replace('Model', '', $class_name));
        }
    }

    /**
     * Generic method to execute a SQL query.
     * @return mixed The result of the query.
     */
    public function query($statement, $attributes = null, $one = false)
    {
        $classe_name = str_replace('Model', 'Entity', get_class($this));

        if ($attributes) {
            return $this->db->prepare($statement, $attributes, $classe_name, $one);
        } else {
            return $this->db->query($statement, $classe_name, $one);
        }
    }
    
    /**
     * Finds a single record by its primary key.
     */
    public function find($id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE {$this->column} = ?", [$id], true);
    }
    
    // ... generic update(), delete(), insert() methods
}
```

**Utilisateur Model:**
```php
<?php
namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

class UtilisateurModel extends Model
{
    protected $table = 'utilisateur';
    protected $column = 'utilisateur_id';

    /**
     * Retrieves a user by their pseudo.
     * @param string $pseudo
     * @return object|null A user entity or null.
     */
    public function findByPseudo($pseudo)
    {
        return $this->query(
            "SELECT * FROM utilisateur WHERE pseudo = ?",
            [$pseudo],
            true
        );
    }

    // ... many other specific methods like isPseudoUnique(), getVoituresForUser(), etc.
}
```
---

## 5. The View Layer

The View layer is responsible for presenting data to the user. In this architecture, views are PHP files that contain HTML mixed with simple PHP logic (loops, conditionals) to display the variables passed from the controller.

### 5.1. Rationale for Extraction

The `login.php` view is a perfect example of this layer. It's almost pure HTML, but it uses PHP to conditionally display an error message (`<?php if ($errors): ?>`) based on a variable passed from the `UtilisateursController`. This demonstrates the separation of concerns: the view doesn't know *why* there's an error, it only knows that if the `$errors` flag is true, it must display the error block. The rendering process is managed entirely by the base controller's `render()` method.

### 5.2. Schematic Flow: The `render()` Process

```mermaid
graph TD
    A[Controller calls this->render('utilisateurs.login', ...)];
    A --> B[ob_start() begins output buffering];
    B --> C[Variables are extracted (e.g., $errors)];
    C --> D[require('views/utilisateurs/login.php')];
    subgraph login.php execution
        E[HTML is generated]
        F{if ($errors)}
        F -- True --> G[Error div is generated]
    end
    D --> E --> F;
    G --> H[Output is buffered, not sent to browser];
    F -- False --> H;
    H --> I[ob_get_clean() stores buffered output in $content];
    I --> J[require('views/templates/default.php')];
    subgraph default.php execution
        K[Renders header, menu, etc.]
        L[echo $content]
        M[Renders footer]
    end
    J --> K;
    K --> L;
    L --> M;
    M --> N[Final HTML is sent to browser];
```

### 5.3. Source Code: `app/Views/utilisateurs/login.php`

```php
<section class="presentation-section">
    <div class="presentation-content">
        <div class="login-box">
            <h1>Connexion</h1>
            
            <!-- Conditional rendering based on variable from controller -->
            <?php if ($errors): ?>
                <div class="alert alert-error">
                    <p>Identifiants invalides. Veuillez réessayer.</p>
                </div>
            <?php endif; ?>

            <!-- The login form -->
            <form id="loginForm" method="POST" class="login-form">
                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input
                        type="text"
                        id="pseudo"
                        name="pseudo"
                        class="form-control"
                        placeholder="Entrez votre pseudo"
                        required />
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Entrez votre mot de passe"
                        required />
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                    <!-- ... -->
                </div>
            </form>
            <!-- ... -->
        </div>
    </div>
</section>
```

---

## 6. Cross-Cutting Concern: Authentication

Authentication is a "cross-cutting concern" because its logic is needed across many parts of the application. This project correctly encapsulates this logic into a dedicated service class, `DbAuth`, rather than bloating the controllers.

### 6.1. Rationale for Extraction

The `DbAuth::login()` method is the central piece of the authentication logic. It's extracted because it clearly shows the business process for logging in:
1. Try to find a matching `utilisateur`.
2. If found, verify the hashed password.
3. If that fails, try to find a matching `visiteur` (a pre-registered user).
4. If found, verify their password.
5. If successful at any stage, set the appropriate session variables (`$_SESSION['auth']` and `$_SESSION['auth_type']`).

This keeps the controller clean and the authentication logic reusable and testable.

### 6.2. Schematic Flow: Authentication Service `login()`

```mermaid
graph TD
    A[Controller calls DbAuth->login(username, password)];
    A --> B[Query 'utilisateur' table for the given username];
    B --> C{User found?};
    C -- Yes --> D[password_verify(password, user->password)];
    D --> E{Password matches?};
    E -- Yes --> F[Set session: auth=user_id, auth_type='utilisateur'];
    F --> G[Return true];
    C -- No --> H[Query 'visiteur_utilisateur' table for username];
    E -- No --> H;
    H --> I{Visiteur found?};
    I -- Yes --> J[password_verify(password, visiteur->password)];
    J --> K{Password matches?};
    K -- Yes --> L[Set session: auth=visiteur_id, auth_type='visiteur'];
    L --> G;
    I -- No --> M[Return false];
    K -- No --> M;
```

### 6.3. Source Code: `core/Auth/DbAuth.php`

```php
<?php
namespace NsCoreEcoride\Auth;

use NsCoreEcoride\Database\Database;

class DbAuth
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function login($username, $password)
    {
        // 1. Attempt to log in as a full user
        $user = $this->db->prepare("SELECT * FROM utilisateur WHERE pseudo = ?", [$username], null, true);

        if ($user) {
            if (password_verify($password, $user->password)) {
                $_SESSION['auth'] = $user->utilisateur_id;
                $_SESSION['auth_type'] = 'utilisateur';
                return true;
            }
        }

        // 2. If user login fails, attempt to log in as a pre-registered visitor
        $visiteur = $this->db->prepare("SELECT * FROM visiteur_utilisateur WHERE pseudo = ?", [$username], null, true);

        if ($visiteur) {
             if (password_verify($password, $visiteur->password)) {
                $_SESSION['auth'] = $visiteur->visiteur_utilisateur_id;
                $_SESSION['auth_type'] = 'visiteur';
                return true;
             }
        }

        return false;
    }
    
    public function isConnected()
    {
        return isset($_SESSION['auth']) && !empty($_SESSION['auth']);
    }
    
    // ... other methods like getConnectedUserId(), getAuthType(), isAdmin(), etc.
}
```
