@layout('layouts.master')
<div class="col-sm-6">
    <h2>Introduction</h2>
    <p>Kekre is a minimal, light  weight framework. It aims to handle small projects that requires the elegance of an MVC 
       web application while having a light foot print. For this reason Kekere was build as a stand alond framework, hence no 
       vendors folder is found in it's root directory</p>
    <p>The framework borrows from some of <a href="http://laravel.com">Laravel's</a> convention and its file structure is 
    similar to that of <a  href="http://asp.net/mvc">ASP.NET MVC</a>.</p>
    <h3>Routing</h3>
    <p>Routes are defined in the <em>routes.php</em> file in the root directory. Each route is mapped to a Controller action: 
<pre>Route::get("about", "HomeController@about");</pre>
        The route above maps the url: http://example.com/about  to the <code>about()</code> method of the <code>HomeController</code> class.<br />
        Some Example routes can be found in the <em>routes.php</em> file in the root directory.
    </p>
    <h3>Controllers</h3>
    <p>
       Controllers are defined in the controller directory. By convention, the word 'controller' is appended to the name of controllers. <br />
       The <code>HomeController</code> controller can serve as a good example of a controller definition. <br />

    </p>
    <h3>Models</h3>
    <p>Models are defined in the Models directory. 
    A model must be defined within the App\Models namespace and mus extend the <code>App\Models\Model</code> abstract class</p>
    <p>A model named <em>User</em> for eaxample is expected to be backed by a table named <em>users</em> by convention. 
        If this is not the case, then you will have to specify the table name using the <code>$table </code> property  of your mode.</p>
    <p>If you prefer to intereact with you tables without creating a model, then you can do so using the <code>App\Models\DB</code>class.
       See more of this under the Database section</p>
    <h3>View</h3>
    <p>Kekere has a minimal implemetation of a view engine. Its views are regular php files with embeded HTML. They are kept in the Views directory or a sub directory within the view directory. <br />
        You can define a layout for your views and share the layout between all or some of  your views using the <code>@layout</code> annotation within the child views. If you have worked with Laravel or ASP.NET you will find this feature very familier. <br />
        The view engine also have a <code>@partial</code> annotations that can be used within the layout to include a partial.
    </p>

    <h3>Database</h3>
    <p>To work with our database we have to first create our database and thereafter, register our database credentials in the <em>settings.json</em> file under the database section as shown below:
<pre>
"database":{        
      "dbhost":"localhost",
      "dbname":"kekere",
      "dbuser":"dbuser",
      "dbpass":"secret"
}       
</pre>
    </p>
</div>
<div>
    <h3>Migrations</h3>
    <p>Kekere has a built in migration capability. You can define you table schema for your migration in the <em>Database/Migrations</em> directory.<br />
    Every migration must be defined using the namespace <code>App\Database\Migrations</code> and must extend the <code>App\Database\Migrations\Migration</code> abstract class.
    </p>
    <p>
       A migration must contain at least one method that defines the table schema of the required table.
        The method shown below defines the table schema for the <em>orders</em> table. Note that the desired
        table name (orders in the case) has "create" prepended and "Table" appended to it's mehod. The naming convention
        must be used for successful migration.
<pre>
public function createOrdersTable()
{
    $query  = "CREATE TABLE IF NOT EXISTS orders(
         id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         user_id INT(11) UNSIGNED NOT NULL,       
         date DATETIME,
         FOREIGN KEY(user_id) REFERENCES users(id)
     )";
    $this->execQuery($query);
    return true;
}
</pre>
    </p>
    <p>
      To generate the command after defining you migration method you must navigate to the project root directory on a terminal/command line and enter the following command:
<pre>$ php console.php migrate-all</pre>
      The will run all the migration class defined in the <em>Database/Migrations</em> directory and generate all the tables defined. <br />
      Alternatively, you can run the comman below to run only a specified migration class.
<pre>$ php console.php migrate UserMigration</pre>
where UserMigration is the name of the migration class you wish to run. Note that you can define more than one table creation method in a migration class.
   </p>
    
</div>
     