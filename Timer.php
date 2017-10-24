<?php
    
    $time=6;
    
    $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    
    sleep($time);

    $calcalute1="http://localhost/index.php/Home/count/rocordehandle";
    $calcalute2="http://localhost/index.php/Home/count/setkdata";


    file_get_contents($calcalute1); 
    file_get_contents($calcalute2); 


    
    file_get_contents($url);



?>