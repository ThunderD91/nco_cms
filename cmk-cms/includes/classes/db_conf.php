<?php
    class DB{
        private $h = "localhost";
        private $u = "root";
        private $p = "";
        private $d = "hfp2_nco_cms";

        protected $conn;
        public $last_err;

        public $row_totals=0;
        public $row_totals_second=0;
        public $countrows=true;

        function __construct(){
            $this->conn = new mysqli($this->h, $this->u, $this->p, $this->d);
            if (mysqli_connect_error() || mysqli_connect_errno()){
                $this->last_err="Connect failed: ".mysqli_connect_error()."\n";
                connect_error($this->conn->connect_errno,$this->conn->connect_error,__LINE__,__FILE__);
                exit;
            }
            $this->conn->set_charset("utf8");
            $this->conn->query("SET lc_time_names='da_DK';");
        }
        function __destruct(){
            $this->conn->close();
        }
        function _close(){
            $this->conn->close();
        }
        /**
         * Method executes a custom query with result
         * @param string $q: query to be executed
         */
        function findQuery($q,$skipFetch=false){
            $result = $this->conn->query($q);

            $r2=array();
            if($result && $result->num_rows > 0 )
                $r2 = $result->fetch_all(MYSQLI_ASSOC);
            return $r2;
        }
        /**
         * Method to find specific data
         * @param string $table: is a most, table to extract data from
         * @param array $obj:
         *      @param array 'join': array of joins
         *          @param string 'type': type of join example: inner, left, full
         *          @param string 'table': table to join
         *          @param string 'cond': condition for join
         *      @param string 'cond': condition for the extract, 'WHERE' not needed
         *      @param string 'order': order by for the extract, 'ORDER BY' not needed
         *      @param string 'group': group by for the extract, 'GROUP BY' not needed
         *      @param string 'limit': limit for the extract, 'LIMIT' not needed
         */
        function find($table,$obj = null){
            $parameters = array();
            if($obj){
                if(isset($obj['join'])){
                    foreach ($obj['join'] as $k=>$v) {
                        $parameters[] 	= $v['type'] . " JOIN " . $v['table'] . " ON " . $v['cond'];
                    }
                }
                if(isset($obj['cond']))  $parameters[] = "WHERE " . $obj['cond'];
                if(isset($obj['group'])) 		$parameters[] 	= "GROUP BY " . $obj['group'];
                if(isset($obj['order']) && !$this->countrows) $parameters[] = "ORDER BY " . $obj['order'];
                if(isset($obj['limit']) && !$this->countrows) $parameters[] = "LIMIT " . $obj['limit'];

            }
            $fields = $obj && isset($obj['fields']) ? $obj['fields'] : '*';
            $queryDefault="SELECT " . $fields . " FROM " . $table;


            $query=$queryDefault;
            if(count($parameters) > 0)
                $query .= " " . join(" ",$parameters);

            $result = $this->conn->query($query);

            $this->row_totals=$result->num_rows;

            if($this->countrows && $obj){
                if(isset($obj['order'])) $parameters[] = "ORDER BY " . $obj['order'];
                if(isset($obj['limit'])) $parameters[] = "LIMIT " . $obj['limit'];
                if(count($parameters) > 0)
                    $query = $queryDefault." " . join(" ",$parameters);
                $result = $this->conn->query($query);
                $this->row_totals_second=$result->num_rows;
            }

            $r2=array();
            if(!$result){
                query_error($this->conn->error,$query,__LINE__,__FILE__);
            }else if($result->num_rows > 0){
                //$r2 = $result->fetch_all(MYSQLI_ASSOC);
                while ($row = $result->fetch_assoc()) {
                    $r2[]=$row;
                }
            }
            return $r2;
        }
        /**
         * Method executes a custom query without result
         * @param string $q: query to be executed
         * @param boolean $id: if true, returns auto generated id
         */
        function execute($q,$id=false){
            $this->conn->query($q);
            if($this->conn->error)
                $this->last_err=$this->conn->error;
            else
                $this->last_err=false;
            if($id)
                return $this->conn->insert_id;
        }
        function esc($str){
            return $this->conn->real_escape_string($str);
        }



        /*protected function generateSalt($max=16){
            $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%";
            $i = 0;
            $salt = "";
            while ($i < $max) {
                $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
                $i++;
            }
            return $salt;
        }
        protected function matchHashedPw($salt,$pass,$user_hash){
            $hash = $this->getHashedPw($salt,$pass);
            if($hash == $user_hash)
                return true;
            return false;
        }
        protected function getHashedPw($salt,$pass){
            $len = strlen($pass);
            $mid = floor($len/2);
            return hash('sha256',substr($pass,0,$mid).substr($salt,0,7).substr($pass,$mid,$len-1).substr($salt,7,16));
        }*/
    }
?>