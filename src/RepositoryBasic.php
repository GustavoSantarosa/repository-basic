<?php

namespace App\Lib;

/**
 * @package Api/
 * @author Luis Gustavo Santarosa Pinto
 * @version 2.0.0
 * 
 */
class RepositoryBasic
{

    private $apiUrl;
    private $user;
    private $password;
    private $endPoint;
    private $build;
    private $callback;
    private $headers;
    private $headers_return;
    private $dev;
    private $http_code;

    /**
     * Undocumented function
     *
     * @param [type] $apiUrl
     * @param [type] $user
     * @param [type] $password
     */
    public function __construct($apiUrl = null, $user = null, $password = null)
    {

        /**
         * Url da Api
         */
        if ($apiUrl) {
            $this->apiUrl = $apiUrl . "/";
        }

        /**
         * User de um basic Auth
         */
        $this->user = $user;

        /**
         * Senha de um basic Auth
         */
        $this->password = $password;
    }

    /**
     * Método utilizado para efetuar a requisição.
     * Para debugar basta informar o 'dev' true
     * 
     * @param string $metodo
     * @param [type] $route
     * @param boolean $dev
     * @param [type] $data
     * @return void
     */
    public function request($metodo = "POST", $route = null, $dev = false, $data = null)
    {
        $this->endPoint = $route;
        $this->dev      = $dev;

        $this->headers = array();

        if (isset($data["headers"])) {
            foreach ($data["headers"] as $indice => $value) {
                $this->headers[] = "{$indice}: $value";
            }
            unset($data["headers"]);
        }

        $this->headers[] = "Content-type: application/json";

        if ($metodo == "GET") {
            $build = "";
            $k = 1;
            foreach ($data as $header => $value) {
                $build .= $header . "=" . rawurlencode($value);
                if ($k < count($data)) {
                    $build .= "&";
                    $k++;
                }
            }

            $this->build = "?" . $build;
            return $this->get();
        } else if ($metodo == "POST") {
            $this->build = json_encode($data);
            return $this->post();
        } else if ($metodo == "PUT") {
            $this->build = json_encode($data);
            return $this->put();
        } else if ($metodo == "DELETE") {
            return $this->delete();
        }

        return $this;
    }

    /**
     * Função para apresentar o retorno da Api
     *
     * @return void
     */
    public function callback()
    {
        if ($this->dev) {
            echo json_encode(array(
                "Api Url"           => $this->apiUrl . $this->endPoint,
                "Headers Envio"     => $this->headers,
                "Body"              => $this->build,
                "Headers Return"    => $this->headers_return,
                "Retorno"           => $this->callback ? $this->callback : "Retorno Nulo!"
            ));
            exit;
        } else {
            return $this->callback ? $this->callback : "Retorno Nulo!";
        }
    }

    /**
     * Função utilizada para efetuar a requisição via Put com a api
     *
     * @return void
     */
    private function put()
    {
        $ch = curl_init($this->apiUrl . $this->endPoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->user:$this->password");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $this->headers_return = curl_getinfo($ch);

        $this->callback = json_decode(substr($server_output, $header_size));
        $this->http_code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $this->callback();
    }

    /**
     * Função utilizada para efetuar a requisição via Delete com a api
     *
     * @return void
     */
    private function delete()
    {
        $ch = curl_init($this->apiUrl . $this->endPoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->user:$this->password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $this->headers_return = curl_getinfo($ch);

        $this->callback = substr($server_output, $header_size);
        $this->http_code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $this->callback();
    }

    /**
     * Função utilizada para efetuar a requisição via Get com a api
     *
     * @return void
     */
    private function get()
    {
        $ch = curl_init($this->apiUrl . $this->endPoint . $this->build);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->user:$this->password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $this->headers_return = curl_getinfo($ch);

        $this->callback     = json_decode(substr($server_output, $header_size));
        $this->http_code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return $this->callback();
    }

    /**
     * Função utilizada para efetuar a requisição via Post com a api
     *
     * @return void
     */
    private function post()
    {
        $ch = curl_init($this->apiUrl . $this->endPoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->user:$this->password");
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $this->headers_return = curl_getinfo($ch);

        $this->callback  = json_decode(substr($server_output, $header_size));
        $this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $this->callback();
    }


    /**
     * Get the value of http_code
     */
    public function getHttp_code()
    {
        return $this->http_code;
    }

    /**
     * Set the value of http_code
     *
     * @return  self
     */
    public function setHttp_code($http_code)
    {
        $this->http_code = $http_code;

        return $this;
    }
}
