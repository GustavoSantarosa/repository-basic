<?php
	/**
	* @package Api/
	* @author Luis Gustavo Santarosa Pinto
    * @version 1.0.0
    * 
	*/

    namespace AccessAPi;
class AccessApi {

    private $apiUrl;
    private $apiKey;
    private $password;
    private $endPoint;
    private $build;
    private $callback;

    public function __construct($apiUrl=null, $apikey=null, $password=null){

        /**
         * Url da Api
         */
        if($apiUrl){
            $this->apiUrl = getenv('URLAPI')."{$apiUrl}/";
        }

        /**
         * Chave da Api
         */
        $this->apiKey = $apikey;

        /**
         * Senha de acesso ao usuario da api
         */
        $this->password = $password;
    }

     /**
      * Função para Visualizar caso o ID seja nulo.
      *
      *Path = { contacts, tickets, conversations, agents, skills, roles, groups, etc}
      *
      * @param string $path
      * @param integer $id
      * @return object
      */
    public function request($metodo="POST", $route=null, $id=null, $data=null){

        if($id){
            $this->endPoint = "{$route}/{$id}";
        }
        else{
            $this->endPoint = $route;
        }
        
        if($metodo == "GET"){
            $this->build = $data;
            $this->get();

        }else if($metodo == "POST"){
            $this->build = json_encode($data);
            $this->post();
        }else if($metodo == "PUT"){
            $this->build = json_encode($data);
            $this->put();
        }else if($metodo == "DELETE"){
            $this->delete();
        }

        return $this;
    }

     /**
      * Função para apresentar o retorno da Api
      *
      * @return void
      */
    public function callback(){
        return $this->callback ? $this->callback : "Retorno Nulo!";
    }

    public function linkapi(){
        return "<pre>".$this->apiUrl . $this->endPoint . $this->build."</pre>";
    }

    /**
     * Função utilizada para efetuar a requisição via Put com a api
     *
     * @return void
     */
    private function put(){

        
        $ch = curl_init($this->apiUrl . $this->endPoint);

        $header[] = "Content-type: application/json";
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->apiKey:$this->password");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $this->callback = substr($server_output, $header_size);
        curl_close($ch);
    }

    /**
     * Função utilizada para efetuar a requisição via Delete com a api
     *
     * @return void
     */
    private function delete(){
        $ch = curl_init($this->apiUrl . $this->endPoint);
        $header[] = "Content-type: application/json";
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->apiKey:$this->password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        
        $this->callback = substr($server_output, $header_size);
        curl_close($ch);
    }

    /**
     * Função utilizada para efetuar a requisição via Get com a api
     *
     * @return void
     */
    private function get(){
        
        $ch = curl_init($this->apiUrl . $this->endPoint . $this->build);
        
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->apiKey:$this->password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       
        $server_output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
         
        $this->callback = substr($server_output, $header_size);
        curl_close($ch);
    }

    /**
     * Função utilizada para efetuar a requisição via Post com a api
     *
     * @return void
     */
    private function post(){
        $ch = curl_init($this->apiUrl . $this->endPoint);
        $header[] = "Content-type: application/json";
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->apiKey:$this->password");
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        
        $this->callback = substr($server_output, $header_size);
        curl_close($ch);
    }

}