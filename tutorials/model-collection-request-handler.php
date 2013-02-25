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
<p>To use the Autoload builder available at <a href="https://github.com/theseer/Autoload">https://github.com/theseer/Autoload</a> is also recommended. It
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
<?php
require_once("../includes/footer.php");
?>


