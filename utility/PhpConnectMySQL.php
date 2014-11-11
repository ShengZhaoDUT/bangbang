<?php
/**
 * php connect mysql
 * @author shengzhaodut@gmail.com
 * @date 2014-11-03
 */

class PhpConnectMySQL{

	protected $link = null;
    protected $ip = null;
    protected $port = null;
    protected $username = null;
    protected $password = null;
    protected $database = null;
    protected $charset = null;

    public function __construct($ip, $port, $username, $password, $database, $charset="latin1"){
        $this->ip = $ip;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;
        $this->connect();
    }
    public function __destruct(){
        if($this->link){
            mysqli_close($this->link);
        }
    }
    public function connect(){
        $this->link = mysqli_connect($this->ip, $this->username, $this->password, $this->database, $this->port);
        if (!$this->link){
            trigger_error("mysql connect error[ ".mysqli_connect_error()." ]\n", E_USER_WARNING);
            return mysqli_connect_error();
        }
        //auto mysql db default charset is utf8.
        mysqli_set_charset($this->link, $this->charset);
        return $this->link;
    }
    public function select($sql){
        $result=null;
        $res = mysqli_real_query($this->link, $sql);
        if ($res){
            $data = mysqli_store_result($this->link);
            if ($data){
                $row_num = mysqli_num_rows($data);
                for ($i=0; $i<$row_num; ++$i){
                    $row = mysqli_fetch_row($data);
                    $result[$i] = $row;
                }
                mysqli_free_result($data);
            }
        }
        return $result;
    }
    /**
     * select result as assocition array
     * @sql sql
     */
    public function selectAsArray($sql){
        $result = array();
        if($res = mysqli_query($this->link, $sql)){
            while ($row = $res->fetch_assoc()){
                array_push($result, $row);
            }
            mysqli_free_result($res);
        }
        return $result;
    }
    /**
     * select result as assocition array, return only one
     * @sql sql
     */
    public function selectAsOne($sql){
        $result = null;
        if($res = mysqli_query($this->link, $sql)){
            if ($row = $res->fetch_assoc()){
                $result = $row;
            }
            mysqli_free_result($res);
        }
        return $result;
    }
    /**
     * update or delete
     */
    public function modify($sql){
        $res = mysqli_real_query($this->link, $sql);
        if ($res){
            $effect_num = mysqli_affected_rows($this->link);
            return $effect_num;
        }
        return $res;
    }

    public function modify_return_key($sql){
        $res = mysqli_real_query($this->link, $sql);
        if ($res){
            $key = mysqli_insert_id($this->link);
            return $key;
        }
        return $res;
    }
}