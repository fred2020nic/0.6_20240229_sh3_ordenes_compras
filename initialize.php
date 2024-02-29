<?php


$host = $_SERVER['HTTP_HOST'];
//  var_dump($host);

 if ($host === 'localhost' || $host === 'localhost:8080') {

$dev_data = array('id'=>'-1','firstname'=>'Mauricio','Sevilla'=>'','username'=>'configuroweb','password'=>'4b67deeb9aba04a5b54632ad19934f26','last_login'=>'','date_updated'=>'','date_added'=>'');
if(!defined('base_url')) define('base_url','http://localhost/0.6_20240229_sh3_ordenes_compras/');
if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );
if(!defined('dev_data')) define('dev_data',$dev_data);
if(!defined('DB_SERVER')) define('DB_SERVER',"localhost");
if(!defined('DB_USERNAME')) define('DB_USERNAME',"root");
if(!defined('DB_PASSWORD')) define('DB_PASSWORD',"");
if(!defined('DB_NAME')) define('DB_NAME',"0.6_20240229_sh3_ordenes_compras");



}else{
    $dev_data = array('id'=>'-1','firstname'=>'Mauricio','Sevilla'=>'','username'=>'configuroweb','password'=>'4b67deeb9aba04a5b54632ad19934f26','last_login'=>'','date_updated'=>'','date_added'=>'');
    if(!defined('base_url')) define('base_url','https://fepapp.com/sh3_ordenes_compras/');
    if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );
    if(!defined('dev_data')) define('dev_data',$dev_data);
    if(!defined('DB_SERVER')) define('DB_SERVER',"localhost");
    if(!defined('DB_USERNAME')) define('DB_USERNAME',"u363832898_sh3_oc");
    if(!defined('DB_PASSWORD')) define('DB_PASSWORD',"#1234Abcd..#");
    if(!defined('DB_NAME')) define('DB_NAME',"u363832898_sh3_oc");

}    
?>

