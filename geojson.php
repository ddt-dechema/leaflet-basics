<?php

  //   if($q == "") {echo ""; return;}
//   if(preg_match("/^\s*(gym|arena)+:/i",$q)) {
//    require_once("../../../config/init_mysqli.php");
//    require_once("../../../generic/helpers.php");
   $conn = null;
   
    //  $conn = get_db_connection();
    //  $q = trim(preg_replace("/^\s*(gym|arena)+:(.*)$/i","$2",$q));
    //  $q = preg_replace("/\s+/","%",$q);
    //  $q = mysqli_real_escape_string($conn, $q);
    $conn = new mysqli('localhost','ddt-dechema','DECHEMA14','test');

     $sql = "SELECT ST_AsGeoJSON(ST_GeomFromText(gf_geom_string)) as gf_geom_string FROM `pgo_geofences` "; 
     $res = $conn->query($sql);
     $conn->close(); 

     $rows=array();

     while ($r = $res->fetch_assoc()) {
     $rows = $r['gf_geom_string'];
       }

       $geojson = '{"type":"Feature","properties":{},"geometry":'.$rows.'}';
       echo $geojson;
    