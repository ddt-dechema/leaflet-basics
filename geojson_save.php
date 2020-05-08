<?php

    $conn = null;
   
    $twoletter = stripslashes($_POST['twoletter']);
    $threeletter = stripslashes($_POST['threeletter']);
    $geofence = stripslashes($_POST['geofence']);

    //for online
    //$conn = new mysqli('85.10.205.173:3306','ddtdechema','DECHEMA14','potanadev');
    //for localhost
    $conn = new mysqli('localhost','ddtdechema','DECHEMA14','potanadev');

     $sql = "INSERT INTO country_borders(iso_a2, iso_a3, geofence) SELECT '$twoletter', '$threeletter', ST_AsText(ST_GeomFromGeoJSON('$geofence'))";  
     $res = $conn->query($sql);
     $conn->close(); 

     $rows=array();
     //select ST_Centroid(st_geomfromtext(gf_geom_string)) from pgo_geofences 
    ?>