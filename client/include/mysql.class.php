<?
session_start();
class mysql{

	private $db_host;
	private $db_user;
	private $db_pwd;
	private $db_database;
	private $conn;
	private $sql;
	private $result;
	private $coding;
	private $show_error = true;

	//public function __construct($db_host="localhost", $db_user="web", $db_pwd="7e7f6y5d8j", $db_database="web", $coding = 'utf8'){
	public function __construct($db_host="localhost", $db_user="cccenteruser", $db_pwd="amp109", $db_database="evoice", $coding = 'utf8'){

		$this->db_host = $db_host;
		$this->db_user = $db_user;
		$this->db_pwd =  $db_pwd;
		$this->db_database = $db_database;
		$this->coding = $coding;
		$this->connect();
	}

	private function connect(){

		$this->conn = @mysql_connect($this->db_host,$this->db_user,$this->db_pwd);mysql_query("SET NAMES utf8");
		if(!$this->conn){
			if($this->show_error){
				$this->show_error('错误提示：链接数据库失败！');
			}
		}

		if(!@mysql_select_db($this->db_database, $this->conn)){
			if($this->show_error){
				$this->show_error('错误提示：打开数据库失败！');
			}
		}

		if(!@mysql_query("SET NAMES $this->coding")){
			if($this->show_error){
				$this->show_error('错误提示：设置编码失败！');
			}
		}
	}

	public function query($sql){
		$this->sql = $sql;
		$result = mysql_query($this->sql, $this->conn);
		if(!$result){
			$this->show_error('错误的SQL语句：', $this->sql);
		}else{
			return $this->result = $result;
		}
	}
	
	public function close() 
	{  
		return mysql_close();
	}  //关闭数据库连接
	
	
	public function show_databases(){
		$this->query("show databases");
		echo '现有数据库：' . mysql_num_rows($this->result);
		echo "<br />";
		$i = 1;
		while($row=mysql_fetch_array($this->result)){
			echo "$i $row[Database]" . "<br />";
			$i++;
		}
	}

	public function show_tables(){
		$this->query("show tables");
		echo "数据库{$this->db_database}共有". mysql_num_rows($this->result) . "张表：";
		echo "<br />";
		$column_name = "Tables_in_" . $this->db_database;
		$i = 1;
		while($row=mysql_fetch_array($this->result)){
			echo "$i $row[$column_name]" . "<br />";
			$i++;
		}
	}

	public function fetch_array($result=''){
		if($this->result){
			return mysql_fetch_array($this->result);
		}else{
			return mysql_fetch_array($result);
		}
	}

	public function findall($table, $field = '*') {
		return $this->query("SELECT $field FROM $table");
	}

	public function delete($table, $condition) {
		return $this->query("DELETE FROM $table WHERE $condition");
	}

	public function insert($table, $field, $value) {
		$i= $this->query("INSERT INTO $table ($field) VALUES ('$value')");
		return $i;
	}

	public function update($table, $update_content, $condition) {
	 	// echo "UPDATE $table SET $update_content WHERE $condition";
		 
		return $this->query("UPDATE $table SET $update_content WHERE $condition");
		
	}

	public function insert_id() {
		return mysql_insert_id();
	}

	public function num_rows() {
		if ($this->result == null) {
			if ($this->show_error) {
				$this->show_error('SQL语句错误', '请检查是否已经使用了query()方法,并成功查询且返回了资源标识符？');
			}
		} else {
			return mysql_num_rows($this->result);
		}
	}

	public function num_fields($table) {
		$this->query("select * from $table");
		echo "<br />";
		echo '字段数：' . $total = mysql_num_fields($this->result);
		echo "<pre>";
		for ($i = 0; $i < $total; $i++) {
			print_r(mysql_fetch_field($this->result, $i));
		}
		echo "</pre>";
		echo "<br />";
	}

	public function show_error($message='',$sql=''){
		echo "<fieldset>";
		echo "<legend>错误信息提示:</legend><br />";
		echo "<div style='font-size:14px; clear:both; font-family:Verdana, Arial, Helvetica, sans-serif;'>";
		echo '错误原因：'. mysql_error() . "<br /><br />";
		echo "<div style='height:20px; background:#FF0000; border:1px #FF0000 solid'>";
		echo "<font color='white'>" . $message . "</font>";
		echo "</div>";
		echo "<font color='red'><pre>" . $sql . "</pre></font>";
		echo "</div>";
		echo "</fieldset>";
	}
	
		///返回实体
	public function GetModel($sql,$nmodel)
	{
	
		try {
			$result=$this->query($sql);
			while($row=$this->fetch_array())
			{
				foreach ($nmodel as $k=>$v) {
						$nmodel->$k=$row[$k];
					//echo 'key=',$k,' value=',$v,"\n";
				}
			 }
			return $nmodel;
		} catch (Exception $e) {  
			return NULL;
		}   	 
	}
	
	///返回列表
	public function GetTableList($sql)
	{
		$rss=array();
		 
		$result=$this->query($sql);
		 while($row= $this->fetch_array())
		 {
			$rss[]=$row;
		 }
		 return $rss;			 
	}	
	

	
}

?>
