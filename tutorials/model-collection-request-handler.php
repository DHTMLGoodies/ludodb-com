<?php
require_once("../includes/header.php");
?>
<h1>Tutorial - Model, Collection and Request handler</h1>
<p>In this tutorial, we will:</p>
<ul>
    <li>Create LudoDB models for cities, states and countries.</li>
    <li>Create LudoDB collections for cities, states and countries.</li>
    <li>Make the countries collection available as a web service.</li>
    <li>Create a simple front-end controller(index.php) which outputs the countries collection.</li>
</ul>
<p>At the end, we will be able to get countries, states and cities in this JSON tree structure when opening index.php:</p>
<div class="code code-pre">
{
    "success":true,
        "message":"",
        "code":200,
        "resource":"DemoCountries",
        "response":[
    {
        "id":"3",
        "name":"Germany",
        "states\/counties":[
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
        "states\/counties":[
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
        "states\/counties":[
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
], "log":{
    "time":0.025645017623901,
            "queries":3
}}
</div>
<h2>Preparation:</h2>
<p>You need access to a WebServer where PHP 5.3 or newer is installed.</p>
<p>Create a new folder called cities.</p>
<p>Download the LudoDB framework from <a href="https://github.com/DHTMLGoodies/ludoDB">GitHub</a> or clone it using
git (command line from the cities folder): </p>
<div class="code">git clone https://github.com/DHTMLGoodies/ludoDB.git</div>
<p>Put LudoDB inside cities/ludoDB. </p>
<p>To use the PHP autoload builder available at <a href="https://github.com/theseer/Autoload">https://github.com/theseer/Autoload</a> is also recommended. It
makes it easy to build one autoload file for the PHP files you'll have to include instead of using a lot of require or include statements.</p>
<p>If you're having problems installing the autoload from the command line using pear install, I will suggest using this:</p>
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
<p>childKey is a string which will be used as array key for the merged collection. By setting "childKeys" to </p>
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
<div class="code code-pre">
class Countries extends LudoDBCollection
{
    protected $config = array(
        "sql" => "select * from country order by name",
        "childKey" => "states/counties",
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
<?php
require_once("../includes/footer.php");
?>


