<?php

namespace GustavoSantarosa;

	/**
    * 
    * Lib utilizada para consumir apis.
    * 
	* @package Api
	* @author Luis Gustavo Santarosa Pinto
    * @version 1.0.0
    * 
	* 
	*/

class AccessApi {

    private $apiUrl;
    private $userName;
    private $password;
    private $endPoint;
    private $build;
    private $callback;

    /**
    * Link da api, sem o endpoint
    * userName = ou normalmente, passado uma key.
    * password = caso tenha, se não tiver, é passado em branco
    * @param string $apiUrl
    * @param string $userName
    * @param string $password
    */
    public function __construct(string $apiUrl, string $userName=null, $password=""){

        /**
         * Url da Api
         */
        $this->apiUrl = $apiUrl;

        /**
         * UserName
         */
        $this->userName = $userName;

        /**
         * Senha de acesso ao usuario da api
         */
        $this->password = $password;
    }

    /**
    * Função que prepara os parametros para a conexao com a api.align-middle.
    * Method = "PUT", "POST", "GET" ou "DELETE"
    * Path = EndPoint.
    * id = Caso queira passar um id, para fazer alteração ou deletar.
    * Dados = Caso va fazer um update, ou insert
    *
    * @param string $method
    * @param string $path
    * @param integer $id
    * @param array $dados
    * @return object
    */
    public function send(string $method="POST", string $path, int $id=null, array $dados=null):object{

        //Verifica se foi passado dados para ser enviado
        if($dados){
            $this->build = json_encode($dados);
        }
        
        //Ve se vai ser passado id no endpoint
        if($id){
            $this->endPoint = "{$path}/{$id}";
        }else{
            $this->endPoint = $path;
        }

        //Checa se o metodo passado esta certo
        if($method == "POST" || $method=="GET" || $method=="PUT" || $method=="DELETE"){
            $this->Connection();
        }else{
            $this->callback = "Informe apenas GET, POST, PUT ou DELETE no parametro Method!";
        }

        return $this;
    }

    /**
     * Função utilizada para efetuar a requisição com a api
     *
     * @return void
     */
    private function Method(){
        $ch = curl_init($this->apiUrl . $this->endPoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_HEADER, true);
            if($this->$method=="PUT" || $this->method=="POST"){
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build);
            }
            if($this->userName){
                curl_setopt($ch, CURLOPT_USERPWD, "{$this->userName}:{$this->password}");
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
             
            $this->callback = json_decode(substr($server_output, $header_size));
            curl_close($ch);
    }
     
    /**
    * Função para apresentar o retorno da Api
    *
    * @return void
    */
    public function callback(){
        return $this->callback;
    }

}