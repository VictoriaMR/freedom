<?php

namespace frame;

Class Query
{
	private static $_instance = null;
	private $_transaction = false;
	public $_table = null;
	protected $_connect = null;
	protected $_columns = null;
	protected $_where = [];
	protected $_whereStr = '';
	protected $_param = [];
	protected $_groupBy = '';
	protected $_orderBy = '';
	protected $_offset = null;
	protected $_limit = null;

	public static function getInstance() 
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
            $this->_connect = \frame\Connection::getInstance();
        }
        return self::$_instance;
    }

	public function table($table = '')
	{
		$this->_table = $table;
		return $this;
	}

	public function where($column, $operator = null, $value = null)
	{
		if (empty($column)) return $this;
		//简单处理 where条件
		if (is_array($column)) {
			foreach ($column as $key => $value) {
				if (is_array($value)) {
					$this->_where[] = [$key, $value[0], $value[1]];
				} else {
					$this->_where[] = [$key, '=', $value];
				}
			}
		} else {
			if ($value === null) {
				$value = $operator;
				$operator = '=';
			}
			$this->_where[] = [$column, $operator, $value];
		}
		return $this;
	}

	public function whereIn($column, $value = [])
	{
		return $this->where($column, 'IN', $value);
	}

	public function select($columns = ['*'])
	{
        $this->_columns = is_array($columns) ? $columns : func_get_args();
        return $this;
	}

	public function value($columns)
	{
		$this->_columns = is_array($columns) ? $columns : [$columns];
		$result = $this->get();
		if (empty($result)) return '';
		$result = array_column($result, $columns);
		if (count($result) == 1) return $result[0];
		return $result;
	}

	public function groupBy($value = '')
	{
		if (!empty($value)) {
			if (!is_array($value))
				$value = explode(',', $value);
			$value = array_map('trim', $value);
			$this->_groupBy = '`'.implode('`,`', $value).'`';
		}
		return $this;
	}

	public function orderBy($value = '', $type = 'DESC')
	{
		if (is_array($value)) {
			foreach ($value as $k => $v) {
				$this->_orderBy .= ', `'.$v[0] . '` ' . strtoupper($v[1] ?? 'desc');
			}
		} else {
			$this->_orderBy .= ', `'.$value . '` ' . strtoupper($type);
		}
		$this->_orderBy = trim(trim($this->_orderBy, ','));
		return $this;
	}

	public function limit($value)
    {
    	$value = (int) $value;
        $this->_limit = $value > 0 ? $value : 0;
        return $this;
    }

    public function page($page, $size=20)
	{
		$page = (int) $page;
		$size = (int) $size;
		$this->_offset = ($page - 1) * $size;
		$this->_limit = $size;
		return $this;
	}

	public function offset($value)
    {
    	$value = (int) $value;
        $this->_offset = $value > 0 ? $value : 0;
        return $this;
    }

	public function get()
	{
		return $this->getResult();
	}

	public function find()
	{
		$this->_offset = 0;
		$this->_limit = 1;
		return $this->get()[0] ?? [];
	}

	public function count()
	{
		$this->_columns = ['COUNT(*) as count'];
		$this->_offset = 0;
		$this->_limit = 1;
		$result = $this->get();
		return $result[0]['count'] ?? 0;
	}

	public function getResult()
	{
		$sql = $this->getSql();
		return $this->getQuery($sql, $this->_param);
	}

	public function getSql()
	{
		if (empty($this->_table)) 
			throw new \Exception('MySQL SELECT QUERY, table not exist!', 1);
		//解析条件
		$this->analyzeWhere();
		$sql = sprintf('SELECT %s FROM `%s`', !empty($this->_columns) ? implode(', ', $this->_columns) : '*', $this->_table ?? '');
		if (!empty($this->_whereStr)) 
			$sql .= ' WHERE ' . $this->_whereStr;
		if (!empty($this->_groupBy)) 
			$sql .= ' GROUP BY ' . $this->_groupBy;
		if (!empty($this->_orderBy)) 
			$sql .= ' ORDER BY ' . $this->_orderBy;
		if ($this->_offset !== null) 
			$sql .= ' LIMIT ' . (int) $this->_offset;
		if ($this->_limit !== null ) 
			$sql .= ',' . (int) $this->_limit;
		return $sql;
	}

	public function insert($data = [])
	{
		//多条插入
		if (empty($data)) return false;
		if (empty($data[0])) $data = [$data];

		$fields = array_keys($data[0]);
		$data = array_map(function($value){
			return "'".implode("', '", $value)."'";
		}, $data);
		$sql = sprintf('INSERT INTO %s (`%s`) VALUES %s', $this->_table, implode('`, `', $fields), '(' . implode('), (', $data).')');
		return $this->getQuery($sql);
	}

	/**
	 * @method 新增并返回自增主键
	 * @author LiaoMingRong
	 * @date   2020-07-21
	 * @return integer
	 */
	public function insertGetId($data)
	{
		$result = $this->insert($data);
		if (!$result) return 0;
		$result = $this->getQuery('SELECT LAST_INSERT_ID() AS last_insert_id');
		$result = $result[0] ?? 0;
		return $result['last_insert_id'] ?? 0;
	}

	public function update($data = [])
	{
		if (empty($data)) return false;
		$tempArr = [];
		foreach ($data as $key => $value) {
			$tempArr[] = "`".$key."`="."'".$value."'";
		}
		$this->analyzeWhere();
		if (!empty($this->_whereStr))
			$sql = sprintf('UPDATE %s SET %s WHERE %s', $this->_table, implode(', ', $tempArr), $this->_whereStr);
		else 
			$sql = sprintf('UPDATE %s SET %s', $this->_table, implode(', ', $tempArr));

		return $this->getQuery($sql, $this->_param);
	}

	public function delete()
	{		
		$this->analyzeWhere();
		if (empty($this->_whereStr)) return false;

		$sql = sprintf('DELETE FROM %s WHERE %s', $this->_table, $this->_whereStr);

		return $this->getQuery($sql, $this->_param);
	}

	public function increment($value, $num = 1) 
	{
		$this->analyzeWhere();
		if (empty($this->_whereStr)) return false;
		$sql = sprintf('UPDATE %s SET %s WHERE %s', $this->_table, $value.'='.$value.' + '.$num, $this->_whereStr);
		return $this->getQuery($sql, $this->_param);
	}

	public function decrement($value, $num = 1) 
	{
		$this->analyzeWhere();
		if (empty($this->_whereStr)) return false;
		$sql = sprintf('UPDATE %s SET %s WHERE %s', $this->_table, $value.'='.$value.' - '.$num, $this->_whereStr);
		return $this->getQuery($sql, $this->_param);
	}

	public function begin()
	{
		$this->connectCheck();
		$this->_transaction = true;
		return $this->_connect->query('BEGIN');
	}

	public function end()
	{
		if (is_null($this->_connect)) return false;
		return $this->_connect->query('END');
	}

	public function rollback()
	{
		if (is_null($this->_connect)) return false;
		return $this->_connect->query('ROLLBACK');
	}

	public function commit()
	{
		if (is_null($this->_connect)) return false;
		return $this->_connect->query('COMMIT');
	}

	public function getQuery($sql = '', $params = [])
	{
		$returnData = [];
		if (empty($sql)) return $returnData;
		$this->connectCheck();
		if (env('APP_DEBUG')) {
			$GLOBALS['exec_sql'][] = sprintf(str_replace('?', '%s', $sql), ...$params);
		}
		if (!empty($params)) {
			if ($stmt = $this->_connect->prepare($sql)) {
				//这里是引用传递参数
			    if(is_array($params))
		        {
		        	if (!is_array(current($params))) {
		        		reset($params);
		        		$params = [$params];
		        	}
		        	foreach ($params as $key => $value) {
		        		$type = $this->analyzeType($value);
			            $bind_names[] = $type;
			            for ($i=0; $i < count($value); $i++) 
			            {
			                $bind_name = 'bind_' . $i;
			                $$bind_name = $value[$i];
			                $bind_names[] = &$$bind_name;
			            }
		            	call_user_func_array(array($stmt, 'bind_param'), $bind_names);
		            	$stmt->execute();
				        $meta = $stmt->result_metadata();
				        if ($meta && $meta->type) {
					        $variables = [];
					        $data = [];
					        while ($field = $meta->fetch_field()) { 
					            $variables[] = &$data[$field->name];
					        }
					        call_user_func_array(array($stmt, 'bind_result'), $variables); 
					        $i=0;
					        while($stmt->fetch()) {
					            $returnData[$i] = [];
					            foreach($data as $k => $v) {
					                $returnData[$i][$k] = $v;
					            }
					            $i++;
					        }
				        } else {
				        	$returnData = mysqli_affected_rows($this->_connect);
				        	$returnData = $returnData > 0 ? $returnData : 1;
				        }
		        	}
		        } else {
		        	if ($this->_transaction) $this->rollback();
		        	throw new \Exception($this->_connect->error, 1);
		        }
			    $stmt->free_result();
			    $stmt->close();
			} else {
				throw new \Exception($this->_connect->error, 1);
			}
		} else {
			if ($stmt = $this->_connect->query($sql)) {
				if (is_bool($stmt)) {
					$returnData =  mysqli_affected_rows($this->_connect);
				} else {
					while ($row = $stmt->fetch_assoc()){
					 	$returnData[] = $row;
					}
					$stmt->free();
				}
			} else {
				if ($this->_transaction) $this->rollback();
				throw new \Exception($this->_connect->error, 1);
			}
		}
		$this->clear();
		return $returnData;
	}

	protected function connectCheck()
	{
		if (is_null($this->_connect))
			$this->_connect = \frame\Connection::getInstance();
		return true;
	}

	/**
	 * @method 清除储存数据
	 * @author LiaoMingRong
	 * @date   2020-07-13
	 * @return boolean
	 */
	private function clear()
	{
		//清空储存数据
		$this->_columns = null;
		$this->_where = [];
		$this->_whereStr = '';
		$this->_param = [];
		$this->_groupBy = '';
		$this->_orderBy = '';
		$this->_offset = null;
		$this->_limit = null;
		return true;
	}

	/**
	 * @method 参数匹配 ? 占位符
	 * @date   2020-05-19
	 * @return string
	 */
	private function analyzeMatch($data)
	{
		if (empty($data) || !is_array($data)) return '';
		$str = '';
		for ($i=0; $i < count($data); $i++) { 
			$str .= '? ,';
		}
		return trim(trim(trim($str), ','));
	}

	/**
	 * @method 处理 where 条件
	 * @date   2020-05-19
	 * @return array
	 */
	private function analyzeWhere()
	{
		$returnData = ['where'=>'', 'param' => []];
		if (empty($this->_where)) return ['where'=>'', 'param' => []];
		$where = '';
		$param = [];
		foreach ($this->_where as $item) {
			$fields = $item[0];
			$operator = $item[1];
			$value = $item[2];
			if (empty($fields) || empty($operator)) continue;
			$operator = strtoupper($operator);
			$fields = explode(',', $fields);
			if (count($fields) > 1) {
				$where .= ' AND (';
				$type = ' OR';
			} else {
				$type = ' AND';
			}
			$tempStr = '';
			foreach ($fields as $fk => $fv) {
				$fv = trim($fv);
				switch ($operator) {
					case 'IN':
						if (!is_array($value)) $value = explode(',', $value);
						$inStr = '';
						foreach ($value as $invalue) {
							$inStr .= '?, ';
						}
						$inStr = trim(trim($inStr), ',');
						$tempStr .= sprintf('%s `%s` %s (%s)', $fk == 0 && count($fields) > 1 ? '' : $type, $fv, $operator, $inStr);
						$param = array_merge($param, $value);
						break;
					default:
						$tempStr .= sprintf('%s `%s` %s ?', $fk == 0 && count($fields) > 1 ? '' : $type, $fv, $operator);
						$param[] = $value;
						break;
				}
			}
			$where .= $tempStr;
			if (count($fields) > 1) {
				$where .= ' )';
			}
		}
		$this->_whereStr = trim(trim(trim($where), 'AND'));
		$this->_param = $param;
		return $this;
	}

	private function analyzeType()
	{
		if (empty($this->_param)) return '';
		$typeStr = '';
		foreach ($this->_param as $key => $value) {
			if (is_numeric($value)) $typeStr .= 'd';
			else $typeStr .= 's';
		}
		return $typeStr;
	}
}