<?php

require "vendor/autoload.php";

use GustavoSantarosa\RepositoryBasic;

/**
 * Caso haja um basic auth, informar o user como segundo e o password como terceiro parametro 
 */
$RepositoryApi = new RepositoryBasic("http://api.snapsolutions.com.br/api", "apikey", "password");

/**
 * Exemplo de um post, utilizando o modo dev para debugar no terceiro parametro.
 * Contendo headers
 */
$RepositoryApi->request("POST", "contatos", true, [
  "headers"   => [
    "Content-Type"  => "application/json",
    "Authorization" => "key"
  ],
  "cidade"    => "americana",
  "uf"        => "SP",
  "telefone"  => "34075942",
  "email"     => "bolota_xd@hotmail.com",
  "web"       => "gustavosantarosa.esy.es",
]);

/**
 * Exemplo de put, sem utilizar o modo dev para debugar
 */
$RepositoryApi->request("PUT", "contatos/10", false, [
  "cidade"    => "piracicaba"
]);  

/**
 * Exemplo de Delete
 */
$RepositoryApi->request("DELETE", "contatos/10", true);

/**
 * Exemplo de get
 */
$RepositoryApi->request("GET", "contatos");

