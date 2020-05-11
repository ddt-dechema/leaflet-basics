<?php
$conn = null;

//for online
$conn = new mysqli('p:db4free.net:3306','ddtdechema','DECHEMA14','potanadev');
//for localhost
//$conn = new mysqli('localhost', 'ddtdechema', 'DECHEMA14', 'potanadev');

$sql = "SELECT ST_AsGeoJSON(ST_GeomFromText(geofence)) AS geometry, 
     ci.iso_a3,
     country_name_en,
     MTonnes
     FROM potanadev.country_borders cb 
     join potanadev.country_information ci on cb.iso_a3=ci.iso_a3 
     join potanadev.country_co2emissions emi on ci.iso_a2=emi.iso_a2 
     
      ";    
      //>50 <=69
// $sql="SELECT ST_AsGeoJSON(ST_GeomFromText(geofence)) as geometry FROM `country_borders`  ";

$res = $conn->query($sql);

$data = array(); //setting up an empty PHP array for the data to go into
while ($r = $res->fetch_assoc())
{
    $data[] = $r;
};
//print_r(json_encode($data, JSON_PRETTY_PRINT));

//https://stackoverflow.com/questions/31885031/formatting-json-to-geojson-via-php
$jsonData = json_encode($data);
$original_data = json_decode($jsonData, true);
$features = array();
foreach ($original_data as $key => $value)
{ //Die zweite Form schreibt zusätzlich den Schlüssel des aktuellen Elementes in jedem Durchlauf in die Variable $key.
    $features[] = array(
        'type' => 'Feature',
        'properties' => array(
            'MTonnes' => $value['MTonnes'],
            'country_name_en' => $value['country_name_en'],
            'iso_a3' => $value['iso_a3']
        ) ,
        'geometry' => json_decode($value['geometry']) //https://stackoverflow.com/questions/48426813/php-formatting-for-geojson
        
    );
};

$new_data = array(
    'type' => 'FeatureCollection',
    'features' => $features,
);

$final_data = json_encode($new_data, JSON_PRETTY_PRINT);
print_r($final_data);

//$geojson = '{"type":"Feature","properties":{ "MTonnes": '.$MTOnnes.', "country_name_en":"'.$country_name_en.'", "iso_a3": "'.$iso_a3.'"},"geometry":'.$rows.'}';
//  echo $geojson;
//OLD APPROACH
//  while ($r = $res->fetch_assoc()) {
//   $geometry = $r['geometry'];
//   $MTonnes = $r['MTonnes'];
//   $iso_a3 = $r['iso_a3'];
//   $country_name_en = $r['country_name_en'];
//  }
//$geojson_row = '{"type":"Feature","properties":{ "MTonnes": '.$MTonnes.', "country_name_en":"'.$country_name_en.'", "iso_a3": "'.$iso_a3.'"},"geometry":'.$geometry.'},';
//  $geojson='{"type": "FeatureCollection",      "features":['.$geojson_row.']}';
//  array_push($geojson, $geojson_row);
//$geojson = '{"type":"Feature","properties":{ "MTonnes": '.$MTOnnes.', "country_name_en":"'.$country_name_en.'", "iso_a3": "'.$iso_a3.'"},"geometry":'.$rows.'}';
//  echo $geojson;
$conn->close();

?>
