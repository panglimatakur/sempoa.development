<?php
defined('mainload') or die('Restricted Access');
define( 'DB_HOST',$db_host); // set database host
define( 'DB_USER',$db_user); // set database user
define( 'DB_PASS',$db_password); // set database password
define( 'DB_NAME',$db_name ); // set database name
define( 'SEND_ERRORS_TO', 'thetakur@gmail.com' ); //set email notification email address
define( 'DISPLAY_DEBUG', true ); //display db errors?
$con = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die("Failed to connect to MySQL");

class db {
    public $defaultDebug = false;
    public $mtStart;
    public $nbQueries;
    public $lastResult;
	

    public function query($query, $debug = -1) {
	  global $con;
      $this->nbQueries++;
      $this->lastResult = mysqli_query($con,$query); //or $this->debugAndDie($query);
     //$this->debug($debug, $query, $this->lastResult);
      return $this->lastResult;
    }
	public function insert($table,$value){
		global $db;
		$jml	= count($value);
		$t 		= 0;
		while($t<$jml){
			$t++;	
			$separator	= "";
			if($t!=$jml){ $separator = ","; }
			@$fields	.= $value[$t][0]."".$separator;
		}
		$t 		= 0;
		while($t<$jml){
			$t++;	
			$separator	= "";
			if($t!=$jml){ $separator = ","; }
			@$vals	.= "\"".$value[$t][1]."\"".$separator;
		}
		$sql = $this->query("INSERT INTO ".$table." (".$fields.") VALUES (".$vals.")");
		#echo "INSERT INTO ".$table." (".$fields.") VALUES (".$vals.")<br>";
		return $sql;
		
	}

	public function update($table,$value,$condition){
		global $db;
		$jml	= count($value);
		$t 		= 0;
		while($t<$jml){
			$t++;	
			$separator	= "";
			if($t!=$jml){ $separator = ","; }
			@$container	.= $value[$t][0]."='".$value[$t][1]."'".$separator;
		}
				
		$sql = $this->query("UPDATE ".$table." SET ".$container." ".$condition."");
		#echo "UPDATE ".$table." SET ".$container." ".$condition."<br><br>";
		return $sql;
		
	}
	
	public function delete($table,$condition){
		$this->table 	 	= $table;
		$this->condition	= $condition;
		$this->query 		= $this->query("delete from ".$table." ".$condition);
		//echo "delete from ".$table." ".$condition;
		return $this->query;
	}
	
	public function fob($fields,$table,$condition){
		$this->fields 	 	= $fields;
		$this->table 	 	= $table;
		$this->condition 	= $condition;
		$this->q 			= $this->query("SELECT ".$fields." FROM ".$table." ".$condition);
		$data				= $this->fetchNextObject($this->q);
		return $data->$fields;
	}
	
	public function recount($query){
		$this->query 		= $this->query($query);
		$this->jml 			= $this->numRows($this->query);
		return $this->jml;
	}
	
	public function sum($fields,$table,$condition){
		$this->fields 	 	= $fields;
		$this->table 	 	= $table;
		$this->condition 	= $condition;
		$this->q 			= $this->query("SELECT SUM(".$fields.") as JUMLAH FROM ".$table." ".$condition);
		$data				= $this->fetchNextObject($this->q);
		return $data->JUMLAH;		
	}
	
	public function last($fields,$table,$condition){
		$this->fields 	 	= $fields;
		$this->table 	 	= $table;
		$this->condition 	= $condition;
		$this->q 			= $this->query("SELECT MAX(".$fields.") as MAXIMAL FROM ".$table." ".$condition);
		#echo "SELECT MAX(".$fields.") as MAXIMAL FROM ".$table." ".$condition;
		$data				= $this->fetchNextObject($this->q);
		return $data->MAXIMAL;		
	}
	
	public function first($fields,$table,$condition){
		$this->fields 	 	= $fields;
		$this->table 	 	= $table;
		$this->condition 	= $condition;
		$this->q 			= $this->query("select MIN(".$fields.") as MINIMAL from ".$table." ".$condition);
		$data				= $this->fetchNextObject($this->q);
		return $data->MINIMAL;		
	}	

	public function concat($field_list,$table,$condition){
		$this->q 			= $this->query("select CONCAT(".$fields.") as CON from ".$table." ".$condition);
		$data				= $this->fetchNextObject($this->q);
		return $data->CON;
	}
	/*-----------Do the same as query() but do not return nor store result------------
		  * Should be used for INSERT, UPDATE, DELETE...
		  * @param $query The query.
		  * @param $debug If true, it output the query and the resulting table.
	  ----------------------------------------------------------------------------------------------*/
    public function execute($query, $debug = -1) {
      $this->nbQueries++;
      mysqli_query($query) or $this->debugAndDie($query);

      $this->debug($debug, $query);
    }
	
    /*-------------Convenient method for mysqli_fetch_object()--------------------------
		  * @param $result The ressource returned by query(). If NULL, the last result returned by query() will be used.
		  * @return An object representing a data row.
	 ------------------------------------------------------------------------------------------------ */
    public function fetchNextObject($result = NULL) {
      if ($result == NULL)
        $result = $this->lastResult;
      if ($result == NULL || $this->numRows($result) < 1)
        return NULL;
      else
        return mysqli_fetch_object($result);
    }
	
    public function fetchNextArray($result = NULL) {
      if ($result == NULL)
        $result = $this->lastResult;
      if ($result == NULL || $this->numRows($result) < 1)
        return NULL;
      else
        return mysqli_fetch_array($result);
    }
	
    /*----------------------- Get the number of rows of a query---------------------------------
		  * @param $result The ressource returned by query(). If NULL, the last result returned by query() will be used.
		  * @return The number of rows of the query (0 or more).
	 -------------------------------------------------------------------------------------------------- */
    function numRows($result = NULL) {
      if ($result == NULL)
        return mysqli_num_rows($this->lastResult);
      else
        return mysqli_num_rows($result);
    }
    /** Get the result of the query as an object. The query should return a unique row.\n
      * Note: no need to add "LIMIT 1" at the end of your query because
      * the method will add that (for optimisation purpose).
      * @param $query The query.
      * @param $debug If true, it output the query and the resulting row.
      * @return An object representing a data row (or NULL if result is empty).
      */
    public function queryUniqueObject($query, $debug = -1) {
      $query = "$query LIMIT 1";
      $this->nbQueries++;
      $result = $this->query($query) or $this->debugAndDie($query);
      $this->debug($debug, $query, $result);
      return $this->fetchNextObject($result);
    }
    /** Get the result of the query as value. The query should return a unique cell.\n
      * Note: no need to add "LIMIT 1" at the end of your query because
      * the method will add that (for optimisation purpose).
      * @param $query The query.
      * @param $debug If true, it output the query and the resulting value.
      * @return A value representing a data cell (or NULL if result is empty).
      */
    public function queryUniqueValue($query, $debug = -1) {
      $query = "$query LIMIT 1";
      $this->nbQueries++;
      $result 	= $this->query($query) or $this->debugAndDie($query);
      $line 	= $this->numRows($result);
      $this->debug($debug, $query, $result);
      return $line[0];
    }
    /** Get the count of rows in a table, with a condition.
      * @param $table The table where to compute the number of rows.
      * @param $where The condition before to compute the number or rows.
      * @return The number of rows (0 or more).
      */
    public function countOf($table, $condition) {
      return $this->queryUniqueValue("SELECT COUNT(*) FROM `$table` $condition");
    }
    /** Internal function to debug when MySQL encountered an error,
      * even if debug is set to Off.
      * @param $query The SQL query to echo before diying.
      */
    public function debugAndDie($query)
    {
      $this->debugQuery($query, "Error");
      die("<p style=\"margin: 2px;\">".mysqli_error()."</p></div>");
    }
    /** Internal function to debug a MySQL query.\n
      * Show the query and output the resulting table if not NULL.
      * @param $debug The parameter passed to query() functions. Can be boolean or -1 (default).
      * @param $query The SQL query to debug.
      * @param $result The resulting table of the query, if available.
      */
    public function debug($debug, $query, $result = NULL)
    {
      if ($debug === -1 && $this->defaultDebug === false)
        return;
      if ($debug === false)
        return;

      $reason = ($debug === -1 ? "Default Debug" : "Debug");
      $this->debugQuery($query, $reason);
      if ($result == NULL)
        echo "<p style=\"margin: 2px;\">Number of affected rows: ".mysqli_affected_rows()."</p></div>";
      else
        $this->debugResult($result);
    }
    /** Internal function to output a query for debug purpose.\n
      * Should be followed by a call to debugResult() or an echo of "</div>".
      * @param $query The SQL query to debug.
      * @param $reason The reason why this function is called: "Default Debug", "Debug" or "Error".
      */
    public function debugQuery($query, $reason = "Debug"){
      $color = ($reason == "Error" ? "red" : "orange");
      echo "<div style=\"border: solid $color 1px; margin: 2px;\">".
           "<p style=\"margin: 0 0 2px 0; padding: 0; background-color: #DDF;\">".
           "<strong style=\"padding: 0 3px; background-color: $color; color: white;\">$reason:</strong> ".
           "<span style=\"font-family: monospace;\">".htmlentities($query)."</span></p>";
    }
    /** Internal function to output a table representing the result of a query, for debug purpose.\n
      * Should be preceded by a call to debugQuery().
      * @param $result The resulting table of the query.
      */
    public function debugResult($result){
      echo "<table border=\"1\" style=\"margin: 2px;\">".
           "<thead style=\"font-size: 80%\">";
      $numFields = mysqli_num_fields($result);
      // BEGIN HEADER
      $tables    = array();
      $nbTables  = -1;
      $lastTable = "";
      $fields    = array();
      $nbFields  = -1;
      while ($column = mysqli_fetch_field($result)) {
        if ($column->table != $lastTable) {
          $nbTables++;
          $tables[$nbTables] = array("name" => $column->table, "count" => 1);
        } else
          $tables[$nbTables]["count"]++;
        $lastTable = $column->table;
        $nbFields++;
        $fields[$nbFields] = $column->name;
      }
      for ($i = 0; $i <= $nbTables; $i++)
        echo "<th colspan=".$tables[$i]["count"].">".$tables[$i]["name"]."</th>";
      echo "</thead>";
      echo "<thead style=\"font-size: 80%\">";
      for ($i = 0; $i <= $nbFields; $i++)
        echo "<th>".$fields[$i]."</th>";
      echo "</thead>";
      // END HEADER
      while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        for ($i = 0; $i < $numFields; $i++)
          echo "<td>".htmlentities($row[$i])."</td>";
        echo "</tr>";
      }
      echo "</table></div>";
      $this->resetFetch($result);
    }
    /** Get how many time the script took from the begin of this object.
      * @return The script execution time in seconds since the
      * creation of this object.
      */
    public function getExecTime(){
      return round(($this->getMicroTime() - $this->mtStart) * 1000) / 1000;
    }
    /** Get the number of queries executed from the begin of this object.
      * @return The number of queries executed on the database server since the
      * creation of this object.
      */
    public function getQueriesCount(){
      return $this->nbQueries;
    }
    /** Go back to the first element of the result line.
      * @param $result The resssource returned by a query() function.
      */
    public function resetFetch($result){
      if (mysqli_num_rows($result) > 0)
        mysqli_data_seek($result, 0);
    }
    /** Get the id of the very last inserted row.
      * @return The id of the very last inserted row (in any table).
      */
    public function lastInsertedId(){
      return mysqli_insert_id();
    }
    /** Close the connexion with the database server.\n
      * It's usually unneeded since PHP do it automatically at script end.
      */
    public function close(){
      mysqli_close();
    }

    /** Internal method to get the current time.
      * @return The current time in seconds with microseconds (in float format).
      */
    public function getMicroTime(){
      list($msec, $sec) = explode(' ', microtime());
      return floor($sec / 1000) + $msec;
    }
  } // class DB
  
  $db = new db();

?>
