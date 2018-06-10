<?php
/*
   MySQL Database Connection Class
*/
namespace comphp\db;

use mysqli;

use app\log\MyLog;

include_once(APP_PATH. 'app/log/' .'Logger.php');


class MySQL 
{

  protected  $myLog;
  protected  $host;
  protected  $dbUser;
  protected  $dbPass;
  protected  $dbName;
  protected  $dbConn;
  protected  $dbConnectError;
  protected  $result;

  protected  $dbConnForStmt;
  protected  $dbconnectForStmtError;
  

	function __construct($host, $dbUser, $dbPass, $dbName )
	{
		$this->myLog = new MyLog('MySQL', 'MySQLDB.php');
		$this->host   = $host;
		$this->dbUser = $dbUser;
		$this->dbPass = $dbPass;
		$this->dbName = $dbName;
		$this->connectToServerOOP();
	}


	function connectToServer()
	{
		$this->dbConn = mysqli_connect( $this->host, $this->dbUser, $this->dbPass );
		if ($this->dbConn->connect_error)
		{
		   trigger_error('could not connect to server' );
		   
		   $this->dbConnectError = true;
		}
		else
		{
			$this->myLog->log_msg(MyLog::TYPE_NOTICE,'connected to server');
            //echo ('connected to server');
		}
	   
	}
	
	function connectToServerOOP(){

		$this->dbConnForStmt = new MySQLi( $this->host, $this->dbUser, $this->dbPass, $this->dbName);

		if($this->dbConnForStmt->connect_error){
			trigger_error('could not connect to server oop' );
		}else
		{
			$this->dbConnForStmt->set_charset('utf8');
            $this->myLog->log_msg(MyLog::TYPE_NOTICE,'connected to server');
			//echo ('connected to server for oop');
		}
		
	}

    function selectDatabase()
    {
    if (! mysqli_select_db( $this->dbConn, $this->dbName ) )
           {
              trigger_error( 'could not select database' );  
              $this->dbConnectError = true;
           }
		   else
           {
			   $this->myLog->log_msg(MyLog::TYPE_NOTICE, " $this->dbName  database selected ");
               //echo (" $this->dbName  database selected ");
           }
      }
     

    function dropDatabase()
    {
		$sql = "drop database $this->dbName";
        echo "<br> $sql  <br>";
		if ( $this->query($sql) )
		{
			echo "<br> the $this->dbName database was dropped<br>";
		}
		else
		{
			echo "the $this->dbName database was not dropped<br>";
		}
    }


    function createDatabase()
    {
		$sql = "create database if not exists $this->dbName ";
		echo "<br> $sql  <br>";
		if ( $this->query($sql) )
		   {
				echo "the $this->dbName database was created<br>";
		   }
			else
		   {
				echo "the $this->dbName database was not created<br>";
		   }
    }


   function isError()
   {
      if  ( $this->dbConnectError )
      {
         return true;
      }
      $error = mysqli_error( $this->dbConn );
      if (empty ($error))
      {
           return false;
      }
      else
      {
           return true;   
      }
   }

   
   	function createTable($table, $sql )
	{
		$result = $db->query($sql);
		if ( $result == True )
		{
			echo "$table was added<br>";
		}
		else
		{
			echo "$table was not added<br>";
		}
   }
   

	function query( $sql )
	{
		 mysqli_query( $this->dbConn, "set character_set_results='utf8'");
		 if (!$queryResource = mysqli_query($this->dbConn, $sql ))
		 {
			trigger_error ( 'Query Failed: <br>' . mysqli_error($this->dbConn ) . '<br> SQL: ' . $sql );
			return false;
		 }
	 
		 return new MySQLResult( $this, $queryResource ); 
   }

    /**
     * @param $sql
     * @return mixed
     */
   public function prepare($sql){
       $stmt = $this->dbConnForStmt->prepare($sql);

       return $stmt;
   }

    /**
     * @param $stmt
     * @param array $paras
     * @return mixed
     */
   public function bindParams ($stmt, array $paras){

       //var_dump($paras);

       $params = array_merge(array(str_repeat('s', count($paras))), $paras);

       $paramsRef = array();

       foreach($params as $k => $v){
           $paramsRef[$k] = &$params[$k];
       }

       call_user_func_array(array($stmt, 'bind_param'), $paramsRef);

       return $stmt;

   }

   public function queryStmt($stmt){
       $stmt->execute();

       $result = $stmt->get_result();

       $stmt->close();
       return new MySQLResult($this, $result);
   }

    /**
     * http://php.net/manual/zh/mysqli-stmt.get-result.php
     * OOP
     * @param $sql
     * @param array $paras
     * @return mixed
     */
   public function prepareBindQuery($sql, array $paras){

	    //echo $sql;

	    //var_dump($paras);

        $stmt = $this->prepare($sql);

        $stmt = $this->bindParams($stmt, $paras);

        return $this->queryStmt($stmt);

   }
   

   
}


class MySQLResult 
{
   protected $mysql;
   protected $result;

   function __construct( &$mysql, $result )
   {
     $this->mysql = &$mysql;
     $this->result = $result;
   }

    function size()
    {
        return $this->result->num_rows;
    }

    function fetch()
    {
		if ( $row = $this->result->fetch_array(MYSQLI_ASSOC))
		{
		   return $row;
		}
       else
       {
           return false;
       }         
    }

    function insertID()
    {
            /**
            * returns the ID of the last row inserted
            * @return  int
            * @access  public
            */
          return mysqli_insert_id($this->mysql->dbConn);
    }


   function isError()
   {
        return $this->mysql->isError();
   }
}