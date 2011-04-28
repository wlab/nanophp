<?php
namespace nano\core\db\core;

/**
 * ORM SELECT query.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.db.core
 */
class SelectQuery {
	
	private $databaseName = null;
	
	private $from = '';
	private $join = '';
	private $joinType = 'JOIN';
	private $on = '';
	private $where = '';
	private $limit = '';
	
	private $objectsToHydrate = array();
	
	/**
	 * Constructor.
	 * @param string $databaseName Name of the database on which this query will be executed.
	 */
	public function __construct($databaseName=null) {
		$this->databaseName = $databaseName;
	}
	
	/**
	 * Set the name of the database on which this query will be executed.
	 * @param string $databaseName Database name.
	 * @return \nano\core\db\core\SelectQuery $this.
	 */
	public function setDatabase($databaseName) {
		$this->databaseName = $databaseName;
		return $this;
	}
	
	/**
	 * Set the name of the table on which the query will be performed.
	 * @param string $tableName Table name.
	 * @return \nano\core\db\core\SelectQuery $this.
	 */
	public function from($tableName) {
		$this->from = $tableName;
		return $this;
	}
	
	/**
	 * Set the type of join to use on the query.
	 * @param string $joinType Join type.
	 */
	public function joinType($joinType) {
		$this->joinType = $joinType;
	}
	
	/**
	 * Set the table(s) to join to.
	 * @param string $tableName Name of table to join to.
	 * @return \nano\core\db\core\SelectQuery $this.
	 */
	public function join($tableName) {
		$this->join = $tableName;
		return $this;
	}
	
	/**
	 * Set the column(s) to join on.
	 * @param string $on Column(s) to join on (in MySQL query syntax).
	 * @return \nano\core\db\core\SelectQuery $this.
	 */
	public function on($on) {
		$this->on = $on;
		return $this;
	}
	
	/**
	 * Set the WHERE clause for the query.
	 * @param string $where WHERE clause in MySQL query syntax.
	 * @return \nano\core\db\core\SelectQuery $this.
	 */
	public function where($where) {
		$this->where = $where;
		return $this;
	}
	
	/**
	 * Set a LIMIT for the query.
	 * @param int $limit
	 * @return \nano\core\db\core\SelectQuery $this.
	 */
	public function limit($limit) {
		///TODO:APC: This does not allow for the OFFSET,LIMIT syntax. Any reason?
		if(preg_match('/^\d+$/',$limit)){
			$this->limit = $limit;
		} else {
			throw new \nano\core\exception\DatabaseException('Limit should be of type integer');
		}
		return $this;
	}
	
	/**
	 * Get the resulting query string.
	 * @return string Query string to pass to MySQL.
	 */
	public function getQuery(){
		$query = 'SELECT';
		$mapClassName = 'project\db\om\\'.$this->databaseName.'\map\Map';
		$selectColumns = array();
		$mapTablesToColumns = $mapClassName::$mapTablesToColumns;
		$fromModel = $mapClassName::getModelNameFromTableName($this->from);
		$this->objectsToHydrate[] = $fromModel;
		foreach($mapTablesToColumns[$this->from] as $key => $col){
			$selectColumns[] = '`'.$this->from.'`.`'.$col.'` AS `'.$fromModel.'___'.$key.'`';
		}
		///TODO:APC: This && (and the others below) are redundant, as ''==null.
		if($this->join!=''&&$this->join!=null){
			$joinModel = $mapClassName::getModelNameFromTableName($this->join);
			$this->objectsToHydrate[] = $joinModel;
			foreach($mapTablesToColumns[$this->join] as $key => $col){
				$selectColumns[] = '`'.$this->join.'`.`'.$col.'` AS `'.$joinModel.'___'.$key.'`';
			}
		}
		if(count($selectColumns)>0){
			foreach($selectColumns as $col){
				$query .= ' '.$col.',';
			}
			$query = substr($query,0,-1);
		}
		$query .= ' FROM `'.$this->from.'`';
		if($this->join!=''&&$this->join!=null){
			$query .= ' '.$this->joinType.' `'.$this->join.'` ON '.$this->on;
		}
		if($this->where!=''&&$this->where!=null){
			$query .= ' WHERE '.$this->where;
		}
		if($this->limit!=''&&$this->limit!=null){
			$query .= ' LIMIT '.$this->limit;
		}
		return $query;
	}
	
	/**
	 * Get an array of the model objects to hydrate based on the content of the query.
	 * @return array Set of model class names.
	 */
	public function getObjectsToHydrate(){
		return $this->objectsToHydrate;
	}
	
}