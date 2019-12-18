<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('../resource/class/tcpdf/tcpdf.php');
include_once("../resource/class/PHPJasperXML.inc.php");
include_once ('./setting.php');



$xml =  simplexml_load_file("client.jrxml");


$PHPJasperXML = new PHPJasperXML();
//$PHPJasperXML->debugsql=true;
//$PHPJasperXML->arrayParameter=array("parameter1"=>1);
$PHPJasperXML->xml_dismantle($xml);

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file


?>
