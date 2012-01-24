<?php

class SearchDatabase {

	protected $dbhost     = "localhost";

	protected $dbuser     = "root";

	protected $dbpass     = "";

	protected $dbname     = "webelite";

	protected $typesCol   = array("varchar","char","text","tinytext","mediumtext","longtext","binary","varbinary","tinyblob","mediumblob","blob","longblob","enum","set");

	protected $cnfTab     = array();

	protected $str;

	protected $alias      = 0;

	protected $sql_continue;

	protected $resourse;

	function __construct($v){

		$this->str = $v;

		if(!$this->resourse = mysql_connect($this->dbhost,$this->dbuser,$this->dbpass)) die("not connect to database");

		@mysql_query('SET NAMES cp1251');
		@mysql_query('SET CHARACTER SET cp1251');
		@mysql_query("SET collation_connection='cp1251_general_ci'");

		return $this->changeBase();

	}

	private function changeBase(){

		if(!mysql_select_db($this->dbname,$this->resourse)) die("is not selected database");

		return $this->db_list();

	}

	private function validationTypes($field){

		foreach($this->typesCol as $value){
			if(strstr($field,$value)) return true;
		}
		return false;
	}

	private function db_list(){

		$returnSql = mysql_query("SHOW TABLES FROM ".$this->dbname."");

		while($resultSql = mysql_fetch_array($returnSql)){

			$queryColumns = mysql_query("SHOW COLUMNS FROM ".$resultSql[0]." FROM ".$this->dbname."");

			while($resFields = mysql_fetch_assoc($queryColumns)){

				if($this->validationTypes($resFields['Type'])){

					@$this->cnfTab[$resultSql[0]] .= $resFields['Field'].",";
				}
			}

		}

		$this->db_query();
	}

	private function db_query(){

		$returnString = "<b>Результат поиска по базе данных</b> : ".$this->dbname."<br />";

		foreach($this->cnfTab as $k => $v){

			$v = substr($v,0,strlen($v)-1); $col = explode(",",$v);

			$sql = " FROM ".$k." AS tb".$this->alias." WHERE";

			foreach($col as $key => $val){

				$sql.= " tb".$this->alias.".".$val." LIKE '%".$this->str."%' OR";

				$col[$key] = "tb".$this->alias.".".$val;

			}

			$sql = "SELECT ".join(",",$col)." ".substr($sql,0,strlen($sql)-3);


				if($dbResult = mysql_query($sql,$this->resourse)){

					if(@mysql_num_rows($dbResult) > 0){

						$returnString .= "<hr />строка : <b>".$this->str."</b> | найдена в таблице : <b>".$k."</b> - используемые поля : ";

						foreach($col as $tab){
							if($req = mysql_query("SELECT ".$tab." FROM ".$k." AS tb".$this->alias." WHERE ".$tab." LIKE '%".$this->str."%'")){
								if(mysql_num_rows($req) > 0) $returnString .= "<b>".substr($tab,strlen("tb".$this->alias."."),strlen($tab))."</b> ; ";
							}
						}
						$returnString .= " | <b>".mysql_num_rows($dbResult)."</b> вхождений";

					}

				}else{

					echo mysql_error();
				}

			@mysql_free_result($dbResult); $this->alias++;
		}

		echo $returnString;

		@mysql_close($this->resourse);
	}

}

?>