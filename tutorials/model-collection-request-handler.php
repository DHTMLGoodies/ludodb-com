<?php
require_once("../includes/header.php");
?>
<h1>Tutorial - Model, Collection and Request handler</h1>
<p>This tutorial will show you to create a Web Service using LudoDB</p>
<p>We will:</p>
<ul>
    <li>Create LudoDB models for cities, states and countries.</li>
    <li>Create LudoDB collections for cities, states and countries.</li>
    <li>Make the countries collection available as a Web Service.</li>
    <li>Create a simple front-end controller(index.php) which outputs the countries collection.</li>
</ul>
<p>At the end, we will be able to get countries, states and cities in this JSON tree structure when opening index.php:</p>
<div class="code code-pre">
{
    "success":true,
        "message":"",
        "code":200,
        "resource":"Countries",
        "response":[
    {
        "id":"3",
        "name":"Germany",
        "states/counties":[
            {
                "id":"5",
                "name":"Bavaria",
                "country":"3",
                "cities":[
                    {
                        "id":"10",
                        "name":"Munich"
                    }
                ]
            }
        ]
    },
    {
        "id":"1",
        "name":"Norway",
        "states/counties":[
            {
                "id":"2",
                "name":"Hordaland",
                "country":"1",
                "cities":[
                    {
                        "id":"4",
                        "name":"Bergen"
                    }
                ]
            },
            {
                "id":"1",
                "name":"Rogaland",
                "country":"1",
                "cities":[
                    {
                        "id":"3",
                        "name":"Haugesund"
                    },
                    {
                        "id":"2",
                        "name":"Sandnes"
                    },
                    {
                        "id":"1",
                        "name":"Stavanger"
                    }
                ]
            }
        ]
    },
    {
        "id":"2",
        "name":"United States",
        "states/counties":[
            {
                "id":"4",
                "name":"California",
                "country":"2",
                "cities":[
                    {
                        "id":"8",
                        "name":"Los Angeles"
                    },
                    {
                        "id":"9",
                        "name":"San Diego"
                    },
                    {
                        "id":"7",
                        "name":"San Fransisco"
                    }
                ]
            },
            {
                "id":"3",
                "name":"Texas",
                "country":"2",
                "cities":[
                    {
                        "id":"6",
                        "name":"Austin"
                    },
                    {
                        "id":"5",
                        "name":"Houston"
                    }
                ]
            }
        ]
    }
],
"log":{
    "time":0.025645017623901,
    "queries":3
}}
</div>
<h2>Preparation:</h2>
<h4>PHP</h4>
<p>You need access to a WebServer with PHP 5.3 or newer.</p>
<h4>Create root folder for the demo.</h4>
<p>Create a new folder called cities.</p>
<h4>Download LudoDB</h4>
<p>Download the LudoDB framework from <a href="https://github.com/DHTMLGoodies/ludoDB">GitHub</a> or clone it using
git (command line from the cities folder): </p>
<div class="code">git clone https://github.com/DHTMLGoodies/ludoDB.git</div>
<p>LudoDB should be exracted into cities/ludoDB. </p>
<h4>Autoload builder (Optional)</h4>
<p>If you have the PHP Autoload builder available at <a href="https://github.com/theseer/Autoload">https://github.com/theseer/Autoload</a> installed,
    it will make your work much easier. It will scan your directories and
    create one single autoload.php file for you. Then you only need one require/include statement in your code
    instead of one for each of the PHP files you'll need to include.</p>
<p>If you're having problems installing Autoload from the command line using PEAR install, try this code:</p>
<div class="code">
pear config-set auto_discover 1<br>
pear install -a -f pear.netpirates.net/Autoload
</div>
<h2>The models</h2>
<p>A LudoDBModel class represents a database table. An instance of a LudoDBModel class represents a row in the database table.</p>
<p>To create a new LudoDBModel, we use this code</p>
<div class="code">
class Country extends LudoDBModel{<br>

}
</div>
<p>Now, we need to configure the database table, i.e. the columns. This can be done directly in the class by specifying a</p>
<div class="code">
protected $config = array();
</div>
<p>where the $config property specifies how the database table looks like.</p>
<p>You can also specify the configuration in a JSON file. To do that, you simply specify:</p>
<div class="code">
protected $JSONConfig = true;
</div>
<p>And add a file called Country.json inside a sub folder named JSONConfig. The JSON should be a JSON encoded version of the PHP $config array.</p>
<h3>Country.php</h3>
<p>Now, let's configure the Country table. The first we specify is name of database table. Let's call it "country":</p>
<div class="code code-pre">
class Country extends LudoDBModel{
    protected $config = array(
        "table" => "country"
    );
}
</div>
<p>The next property is "sql". "sql" is an optional property which specifies the sql used when you want to look up a record. If you choose not to define "sql",
LudoDB will create one on the fly based on table name and columns. </p>
<p>For code clarity, it's preferable to always specify "sql". For Country, we will set sql to:</p>
<div class="code code-pre">
class Country extends LudoDBModel{
    protected $config = array(
        "table" => "country",
        "sql" => "select * from country where id=?"
    );
}
</div>
<p>The question marks in the sql definition is a placeholder for the arguments sent to the constructor when a new Country instance is created.</p>
<p>Now, let's move on to the columns which are defined inside a "columns" array. The key of the array is the name of the column. The value of each key may
be a string specifying the definition of the column or an array containing column definition and other properties.</p>
<p>The config of the  id field, we set to "int auto_increment not null primary key":</p>
<div class="code code-pre">
class Country extends LudoDBModel{
    protected $config = array(
        "table" => "country",
        "sql" => "select * from country where id=?",
        "columns" => array(
            "id" => "int auto_increment not null primary key"
        )
    );
}
</div>
<p>For the "name", attribute we use an array. We set "db" to "varchar(255)" and we also specify an access attribute
to "rw". The access attribute specifies access to the column for the LudoDBModel::save and LudoDBModel::read methods.
    "r" means read access and "w" write access.
    The default value is "", i.e. no access. The exception is the "id" field which has "rw" as default.</p>
<p>You will still able to write and read from column internally in your class. </p>
<p>Our Country class should now look like this:</p>
<div class="code code-pre">
class Country extends LudoDBModel
{
    protected $config = array(
        "table" => "country",
        "sql" => "select * from country where id=?",
        "columns" => array(
            "id" => "int auto_increment not null primary key",
            "name" => array(
                "db" => "varchar(255)",
                "access" => "rw"
            )
        )
    );
}
</div>
<p>In the LudoDB model, we can also define default data which are inserted when the table is created by the
    LudoDBModel::createTable() method.</p>
<p>Let's add "Norway", "United States" and "Germany" as default data for country inside the "data" property.</p>
<div class="code code-pre">
class Country extends LudoDBModel
{
    protected $config = array(
        "table" => "country",
        "sql" => "select * from country where id=?",
        "columns" => array(
            "id" => "int auto_increment not null primary key",
            "name" => array(
                "db" => "varchar(255)",
                "access" => "rw"
            )
        ),
        "data" => array(
            array("name" => "Norway"),
            array("name" => "United States"),
            array("name" => "Germany")
        )
    );
}
</div>
<p>And that's it for the Country class. </p>
<h3>State.php</h3>
<p>Now, let's move on to the State class. In our example, Texas in the United States, Rogaland in Norway and
    Bavaria in Germany, all represents states.</p>
<p>For the State class will specify a config where "state" is the name of the database table. It should contain the
columns id, name and country, where country is a reference to the id of the country table.</p>
<p>The definition of id and name is the same for State as it was for Country. The last column "country" is an
int referencing the id of the country table. We define this using the "references" attribute:</p>
<div class="code code-pre">
State extends LudoDBModel
{
    protected $config = array(
        "table" => "state",
        "sql" => "select * from state where id=?",
        "columns" => array(
            "id" => "int auto_increment not null primary key",
            "name" => array(
                "db" => "varchar(255)",
                "access" => "rw"
            ),
            "country" => array(
                "db" => "int",
                "references" => "country(id) on delete cascade",
                "access" => "rw"
            )
        )
    );
}
</div>
<p>The value of the references attribute is the same as you would use in a MySQL create table statement. Here,
the column config is an int references the id of the country table. And when the country is deleted, all states
with reference to that country should also be deleted(i.e. on delete cascade).</p>
<p>The last thing we do in the State class is to define indexes and set default data. indexes is a config property which should be an
array with the names of the columns which should be indexed. For this table, "country" should be indexed. </p>
<p>This gives us this State class:</p>
<div class="code code-pre">
class State extends LudoDBModel
{
    protected $config = array(
        "table" => "state",
        "sql" => "select * from state where id=?",
        "columns" => array(
            "id" => "int auto_increment not null primary key",
            "name" => array(
                "db" => "varchar(255)",
                "access" => "rw"
            ),
            "country" => array(
                "db" => "int",
                "references" => "country(id) on delete cascade",
                "access" => "rw"
            )
        ),
        "data" => array(
            array("name" => "Rogaland", "country" => 1),
            array("name" => "Hordaland", "country" => 1),
            array("name" => "Texas", "country" => 2),
            array("name" => "California", "country" => 2),
            array("name" => "Bavaria", "country" => 3),
        ),
        "indexes" => array("country")
    );
}
</div>
<h3>City.php</h3>
<p>The last Class to create is City. The configuration of this table is the same as for State except that it has a
state column referencing state(id) instead of a country column:</p>
<div class="code code-pre">
class City extends LudoDBModel
{
    protected $config = array(
        "table" => "city",
        "sql" => "select * from city where id=?",
        "columns" => array(
            "id" => "int auto_increment not null primary key",
            "name" => array(
                "db" => "varchar(255)",
                "access" => "rw"
            ),
            "state" => array(
                "db" => "int",
                "references" => "state(id) on delete cascade",
                "access" => "rw"
            )
        ),
        "data" => array(
            array("name" => "Stavanger", "state"=> 1),
            array("name" => "Sandnes", "state"=> 1),
            array("name" => "Haugesund", "state"=> 1),
            array("name" => "Bergen", "state"=> 2),
            array("name" => "Houston", "state"=> 3),
            array("name" => "Austin", "state"=> 3),
            array("name" => "San Fransisco", "state"=> 4),
            array("name" => "Los Angeles", "state"=> 4),
            array("name" => "San Diego", "state"=> 4),
            array("name" => "Munich", "state"=> 5),
        ),
        "indexes" => array("state")
    );
}

</div>
<p>That's it for the LudoDBModel classes. You should now have three files inside your "cities" folder, Country.php,
State.php and City.php.</p>
<h2>The LudoDBCollection classes</h2>
<p>LudoDBCollection classes are used to retrieve a collection of records, example: all states of a country. You create
a new collection class by extending LudoDBCollection.</p>
<p>Example:</p>
<div class="code code-pre">
class Cities extends LudoDBCollection
{
}
</div>
<p>The LudoDBCollection class is also configured using the $config property or by JSON.</p>
<h3>Citites.php</h3>
<p>Let's start bulding the Cities collection(Cities.php). The sql should be</p>
<div class="code">
select * from city order by name
</div>
<div class="code code-pre">
class Cities extends LudoDBCollection
{
    protected $config = array(
        "sql" => "select * from city order by name"
    );
}
</div>
<p>You now have the simplest form of a working LudoDBCollection class.</p>
<p>One thing we want to specify in the config is "model". "model" is a name of a LudoDBModel class. By specifying "model",
LudoDBCollection will call the getValues method of the model for each row in the result set. This is useful when you
want to avoid returning values of read-only columns.</p>
<p>The name of the model for Cities is "City".</p>
<div class="code code-pre">
class Cities extends LudoDBCollection
{
    protected $config = array(
        "sql" => "select * from city order by name",
        "model" => "City"
    );
}
</div>
<p>And that's all we need for the Cities class. </p>
<h3>States.php</h3>
<p>The sql and model config of States are about the same as for Cities:</p>
<div class="code code-pre">
class States extends LudoDBCollection
{
    protected $config = array(
        "sql" => "select * from state order by name",
        "model" => "State"
    );
}
</div>
<p>but we're not quite done with the States collection yet. What we want to do is to merge in the cities
of this state. We do that using the "merge" config attribute. "merge" is an array containing three
properties: "class", "fk" and "pk".</p>
<p>"class" is a name of a LudoDBCollection class, "fk" is the name of foreign key column and pk is the
name of the column the foreign key is referencing. </p>
<p>For States, we set class to "Cities". We set "fk" to "state" since that's the name of the column in
the city table where reference to state is stored. We set "pk" to "id" since that's the name of the column
the "state" column in "City" is referencing.</p>
<p>This gives us this code:</p>
<div class="code code-pre">
class States extends LudoDBCollection
{
    protected $config = array(
        "sql" => "select * from state order by name",
        "model" => "State",
        "merge" => array(
            array(
                "class" => "Cities",
                "fk" => "state",
                "pk" => "id"
            )
        )
    );
}
</div>
<p>We're almost done with the States collection now. The last thing we want to add to the config is "childKey" and "hideForeignKeys". </p>
<p>childKey is a string which will be used as array key for the merged collection. By setting "childKey" to "cities", the Cities
collection will be returned as a "cities" array("cities" => array()). </p>
<p>ps! childKey can be defined globally on the config object for all merged collections, or inside the "merge" array, i.e. with "fk", "pk" and "class".</p>
<p>"hideForeignKeys" is a boolean property which, when set to true will hide foreign keys in the merged collection, i.e. state property of city
will not be shown.</p>
<p>The final code for the States class now looks like this:</p>
<div class="code code-pre">
class States extends LudoDBCollection
{
    protected $config = array(
        "sql" => "select * from state order by name",
        "model" => "State",
        "childKey" => "cities",
        "hideForeignKeys" => true,
        "merge" => array(
            array(
                "class" => "Cities",
                "fk" => "state",
                "pk" => "id"
            )
        )
    );
}
</div>
<h3>Countries.php</h3>
<p>The last collection we want to create is Countries. The configuration of this collection is the same as for States:</p>
<div class="code code-pre">
class Countries extends LudoDBCollection
{
    protected $config = array(
        "sql" => "select * from country order by name",
        "childKey" => "states/counties",
        "hideForeignKeys" => true,
        "merge" => array(
            array(
                "class" => "States",
                "fk" => "country",
                "pk" => "id"
            )
        )
    );
}

</div>
<p>For now, this completes our LudoDBModel and LudoDBCollection classes. We have created 3 models and 3 collections. Since we
are merging States into Countries and Citites into States, we will get both States and Cities when calling
    the Countries::read method.</p>
<p>Now, let's move on to index.php, our front end controller.</p>
<h3>index.php</h3>
<p>In index.php we want to create a LudoDBRequestHandler instance and use it to output the JSON for
the Countries collection. </p>
<p>The code for the request handler looks like this:</p>
<div class="code code-pre">
$handler = new LudoDBRequestHandler();
echo $handler->handle("Countries/read");
</div>
<p>We create a LudoDBRequestHandler instance and call the handle method, passing the request we want to have processed.</p>
<p>The argument to the handle method is in a web service format where tokens are separated by a slash(/).</p>
<p>The first token is the name of the class or resource which the request should be delegated to. The lsat token("read") is the
name of the service or more specific the name of the method which should handle the request.</p>
<p>Any arguments in between the first and last are passed as constructor arguments to the resource, example:
request: City/1/read will give you the data for City with id equals 1. </p>
<h3>The LudoDBService interface</h3>
<p>Resources handled by the LudoDBRequestHandler class has to implement the LudoDBService interface. The interface contains
the following methods which has to be implemented.</p>
<div class="code code-pre">
public function validateArguments($service, $arguments);
public function validateServiceData($service, $data);
public function shouldCache($service);
public function getValidServices();
public function getOnSuccessMessageFor($service);
</div>
<ul>
    <li><b>validateArguments</b> is used to validate arguments sent to the constructor of the resource class. When invalid,
    you may return false or throw a LudoDBException exception. The name of the service and an array containing constructor
    parameters are passed to this method.</li>
    <li><b>validateServiceData</b> is used to validate eventual POST data($_POST['data']) which will be passed to the service method. As
    for validateArguments, you can return false when invalid or throw a LudoDBException if you want to return an error message.</li>
    <li><b>shouldCache</b> should return a boolean when the LudoDBRequest handler should try to get value from the ludo_db_cache
    database table instead of passing the request to the resource. This is useful if a service requires a lot of database queries
    to complete. </li>
    <li><b>getValidServices</b> should return an array with the name of valid services, example return array("read");</li>
    <li><b>getOnSuccessMessageFor</b> return default success messages for successful requests. Example: return "Data saved successfully";</li>
</ul>
<p>We need to implement these methods for our Countries collection. So re-open Countries.php and add the following code:</p>
<div class="code code-pre">
public function getValidServices(){
    return array("read");
}

public function validateArguments($service, $arguments){
    return count($arguments) === 0;
}

public function validateServiceData($service, $data){
    return empty($data);
}

public function cacheEnabledFor($service){
    return false;
}

public function getOnSuccessMessageFor($service){
    return "";
}
</div>
<p>Only the read service is supported, so we return "read" in getValidServices. The "read" method is already
implemented in LudoDBCollection so we don't need to create a new "read" method inside our Countries class.</p>
<p>validateArguments
should return true only when number of arguments is 0 since our service does not require an arguments.
The read service does not support any data either, so we return true from validateServiceData only when $data is empty.</p>
<p>Finally, we return true from cacheEnabled and an empty string from getOnSuccessMessageFor. For LudoDBModels and
LudoDBCollection classes, we can choose to not implement the getOnSuccessMessageFor method because it has it's default
implementation in LudoDBObject which is parent class of LudoDBModel and LudoDBCollection.</p>

<p>That's it. Countries is now a LudoDBService class. Let's move back to index.php. This is the code we have
so far:</p>
<div class="code code-pre">&lt;?php
$handler = new LudoDBRequestHandler();
echo $handler->handle("Countries/read");
</div>
<p>If you open index.php in your browser, you will see a blank screen or an error message. We need to include
our PHP classes. Files can be included manually, or we can create an autoload.php file using the Autoload
php PEAR plugin available at <a href="https://github.com/theseer/Autoload">https://github.com/theseer/Autoload</a>.
<h4>Manual import</h4>
<p>Use this code at the top of index.php to manually import the required php files:</p>
<div class="code code-pre">
require_once("ludoDB/autoload.php"); // Includes ludodb
require_once(__DIR__ . "/Country.php");
require_once(__DIR__ . "/City.php");
require_once(__DIR__ . "/State.php");
require_once(__DIR__ . "/Countries.php");
require_once(__DIR__ . "/States.php");
require_once(__DIR__ . "/Citites.php");
</div>
<h4>Import using theseer/Autoload:</h4>
<p>Open a command line/shell and go to the citites folder. There type:</p>
<div class="code code-pre">
phpab -o autoload.php -e *Test* .
</div>
<p>Now, you only have to include autoload.php in index.php:</p>
<div class="code code-pre">
require_once(__DIR__ . "/autoload.php");
</div>
<h4>Specify database connection details.</h4>
<p>The next thing we need to do inside index.php is to specify the database connection details. </p>
<p>Example:</p>
<div class="code code-pre">
LudoDB::setDb("name_of_database");
LudoDB::setUser("db_user_name");
LudoDB::setPassword("db_password");
LudoDB::setHost("localhost");
</div>
<p>Replace the values above with the connection details for your database.</p>
<h4>Create database tables if not exists</h4>
<p>For this demo, we want to make sure that the database tables are created. Usually, you will not
have such code in your front end controller. So bear in mind this is for the demo only.</p>
<p>This is the code used to create database tables when they doesn't exists:</p>
<div class="code">
$c = new Country();
if (!$c->exists()) {
    $util = new LudoDBUtility();
    $util->dropAndCreate(array("State", "City", "Country"));
}
</div>
<p>Finally, I call LudoDB::enableLogging(); to see number of queries and time used to process the requests in the response
from LudoDBRequestHandler.</p>
<p>The final code for index.php now looks like this:</p>
<div class="code code-pre">&lt;?php
require_once(__DIR__ . "//autoload.php");

LudoDB::setDb("my_db");
LudoDB::setUser("myUser");
LudoDB::setPassword("myPassword");
LudoDB::setHost("localhost");

$c = new Country();
if (!$c->exists()) {
    $util = new LudoDBUtility();
    $util->dropAndCreate(array("State", "City", "Country"));
}


LudoDB::enableLogging(); // get number of queries and server time in response

$handler = new LudoDBRequestHandler();
echo $handler->handle("Countries/read");

</div>
<p>If you open http://your-domain/path/to/citites/index.php, you should now get JSON for the Countries collection. To get a pretty
view, open the page in Chrome or Firefox after installing a JSONView addon/extension.</p>
<h2>Conclusion:</h2>
<p>We have seen how to create LudoDBModel and LudoDBCollection classes. We have also taken a quick look at the
LudoDBRequestHandler class.</p>
<p>The code for this tutorial is also available inside the examples folder of the LudoDB repository(or zip). I will also suggest looking at the
mod_rewrite code inside examples/mod_rewrite. Instead of a static index.php it has a dynamic router.php and a .htaccess file
which parses url's and passes them to router.php. By opening the url http://yourdomian/path/to/mod_rewrite/Book/1/read in your browser.
"Book/1/read" will be passed to Router.php which will pass the request to a LudoDBRequeestHandler instance and output the result.</p>
<?php
require_once("../includes/footer.php");
?>


