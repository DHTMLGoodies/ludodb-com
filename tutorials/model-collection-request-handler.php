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
<h2>Preparation:</h2>
<p>You need access to a WebServer where PHP 5.3 or newer is installed.</p>
<p>Download the LudoDB framework from <a href="https://github.com/DHTMLGoodies/ludoDB">GitHub</a> or clone it using
git: </p>
<div class="code">git clone https://github.com/DHTMLGoodies/ludoDB.git</div>
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
<p>For code clarity, I prefer to always specify "sql". For "country", we will set sql to:</p>
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
<p>For the id field, we will simply specify:</p>
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
<?php
require_once("../includes/footer.php");
?>


