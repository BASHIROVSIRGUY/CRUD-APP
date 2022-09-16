<?php 
// namespace App/Database;

class dbHandler {
    const HOST = '127.0.0.1';
    const USER = 'root';
    const PASSWORD = '';
    const DB_NAME = 'mydb';

    protected $connector = null;

    function __construct(){
        $this->connector = new mysqli(self::HOST, self::USER, self::PASSWORD, self::DB_NAME);
    }

    public function query($sql_query){
        $query_result = $this->connector->query($sql_query);
        return $this->output($query_result);
    }

    public function query_with_param($sql_query, $bind_type_string = ''){
        $stmt = $this->connector->prepare($sql_query);
        if($stmt){
            $params = array_slice(func_get_args(), 2);
            if(strlen($bind_type_string) == count($params)){
                $stmt->bind_param($bind_type_string, ...$params);
            }
            $query_status = $stmt->execute();
            $query_result = $stmt->get_result() ?: $query_status;
        } else{
            $query_result = false;
        }
        return $this->output($query_result);
    }

    private function output($query_result){
        if(is_bool($query_result)){
            $result = $query_result;
        } else{
            $result = $this->get_result_array($query_result);
            if(is_array($result) && count($result) == 1){
                $result = $result[0];
            }
        }
        return $result;
    }

    private function get_result_array($query_result){
        $result_array = [];
        while($tuple_data = $query_result->fetch_array(MYSQLI_ASSOC)){
            if(count($tuple_data) == 1) $tuple_data = array_shift($tuple_data);
            $result_array[] = $tuple_data;
        }
        return $result_array;
    }

    function __destruct(){
        if (!empty($this->connector)) $this->connector->close();
    }
}