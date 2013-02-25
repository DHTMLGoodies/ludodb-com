<p>Example of LudoDBCollection Countrires rendered inside a LudoJS tree.Tree class.</p>
<?php
require_once("../includes/header.php");
?>
<script type="text/javascript" src="../ludojs/mootools/mootools-core-1.4.5.js"></script>
<script type="text/javascript" src="../ludojs/mootools/mootools-more-1.4.0.1.js"></script>
<script type="text/javascript" src="../ludojs/js/ludojs.js"></script>
<link rel="stylesheet" href="../ludojs/css/ludojs-light-gray.css"/>
<style type="text/css">
.ludo-tree-node span{
    color:#000;
}
</style>
<script type="text/javascript">
    new ludo.Window({
        title: 'Countries, States and Citites',
        layout:{
            width:600, height:400,
            left:20, top:20
        },
        children:[
            {
                type:'tree.Tree',
                recordConfig:{
                    "country": {
                        "nodeTpl": "<span>{name}</span>"
                    },
                    "state" : {
                        "nodeTpl" : "{name}"
                    },
                    "city": {
                        "nodeTpl": "{name}"
                    }
                },
                dataSource:{
                    url:'../ludodb/examples/cities/index.php',
                    resource:'DemoCountries',
                    service:'read'
                }}
        ]
    });

</script>