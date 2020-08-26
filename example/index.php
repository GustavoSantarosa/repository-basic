<?php
require "../src/accessapi.php";

$AccessApi = new AccessApi("http://api.snapsolutions.com.br/api");

 $AccessApi->request("POST", "contatos", null, [
    "cidade"    => "americana",
    "uf"        => "SP",
    "telefone"  => "34075942",
    "email"     => "bolota_xd@hotmail.com",
    "web"       => "gustavosantarosa.esy.es",
    ]);
echo $AccessApi->callback();

  $AccessApi->request("PUT", "contatos", 11, [
    "cidade"    => "piracicaba"
    ]);  
echo $AccessApi->callback();

$AccessApi->request("DELETE", "contatos", 10);
echo $AccessApi->callback();

$AccessApi->request("GET", "contatos");
echo $AccessApi->callback();

echo $AccessApi->linkapi();
