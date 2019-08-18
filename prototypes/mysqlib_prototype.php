<?php

	/**
	 * @file mysqlib_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 * @updated 05.03.18
	 * @see dbObject_class.php
	 * @todo manual
	 */

	/**
	 * MANUAL
	 *
	 *
	 *
	 */

	class mysqlib_proto {

		/**
		 * @var $conn
		 * @var $stmt
		 * @var $_query
		 * @var $_qsc
		 * @var $_bindParams
		 * @var $_methods
		 * @var $_temp
		 * @var $_subQuery
		 * @var $debugMode
		 */

		/**
		 * Mysqli object.
		 * @var object [mysqli]
		 */

		private $conn = null; // Keep mysqli object here.

		/**
		 * Prepared statement object.
		 * @var object [stmt]
		 */

		private $stmt = null; // Keep prepared statement object here.

		/**
		 * Query to be run.
		 * @var string
		 */
		private $_query = '';

		/**
		 * Is query successfull.
		 * @var bool
		 */
		private $qsc = false;

		/**
		 * If query fails, return false.
		 * It saves a lot of coding to keep $rtn as false by default.
		 * Otherwise, everytime when query fails new $rtn = false variable would have to be created.
		 * @var bool
		 */

		private $rtn = false;

		/**
		 * Parameters to be used in prepared statement.
		 * @var array
		 */

		private $_bindParams = array();

		/**
		 * Array of all used methods/conditions. 
		 * It is an multidimensional array, where the first array is a method name and second method.
		 * Ex. ['where']['item_id'][] 
		 * @see where()
		 * @var array
		 */

		private $_methods = array();

		/**
		 * Just like _methods but this array holds a temporary methods to use in sub queries. 
		 * @var array
		 */

		private $_temp = array();

		/**
		 * Sub query mode.
		 * @var bool
		 */

		private $_subQuery = false;

		/**
		 * Debug mode needs to be set to true to allow using debugging methods.
		 * To turn debug mode on, use debugMode() method.
		 * @var bool
		 */

		private $debugMode = false;

		/**
		 *
		 * @var bool
		 */

		public $reuseConditions = false;

		/**
		 * Connect to db.
		 */

		public function __construct() {
			$this->connect();
		}

		/**
		 * Setup a connection with MySQL.
		 */

		private function connect() {
			try {
				// Run only if $this->conn does not contain a connection.
				if(empty($this->conn)) {
					$this->conn = new mysqli(SERVER, USER, PASS, DB);
					if($this->conn->connect_error) {
						throw new Exception($this->conn->connect_error);
					}
				}				
			} catch(Exception $e) {
				return $this->conn = false; // Cannot setup connection.
			}
		}

		/**
		 * Query.
		 * @param $query [string]
		 * @return boolean
		 */

		public function query($query) {
			// Only if connection with db is established and there is query ready to run.
			if($this->conn !== false && !empty($this->_query)) {
				try {
					$this->stmt = $this->conn->prepare($query); // Prepare Query and start $this->stmt.
					if($this->conn->error) {
						throw new Exception("MySQL ERROR: ".$this->conn->error."<br />QUERY: ".$query, $this->conn->errno); // Query failed.
					}
					$this->bindParmas($this->_bindParams); // Check if there is any parameter to bind.
					$this->stmt->execute(); // Execute Query.
					if($this->stmt->error) {
						throw new Exception("Query failed: ".$this->stmt->error); // Execution failed.
					} 
					else {
						return $this->qsc = true; // Query was successful.
					}
				} catch(Exception $e) {
					echo $e->getMessage()."<br />";
					echo nl2br($e->getTraceAsString())."<br />";
					return $this->qsc = false; // Query failed.
				}
			}
			return $this->qsc = false; // Query failed because of no connection to db.
		}

		/**
		 * Enter the sub query mode. Use to build more advanced queries.
		 * In sub query all methods/conditions are saved into _temp array.
		 * It's impossible to load anything from database when in sub query mode. 
		 */

		public function subQuery() {
			$this->_subQuery = true;
			if(!empty(func_get_args())) {
				$this->buildQuery('', $this->_temp);
				return $this->_query;
			}
		}

		/**
		 * Exit the sub query and reset temp array.
		 */

		public function subQueryClose() {
			$this->_temp = array();
			$this->_subQuery = false;
		}

		/**
		 * This method works only on _temp array, it's objective is to help create more advanced queries.
		 * It returns a chunk (as many as arguments passed) of _temp array with dbObjects (MySQL conditions) like WHERE, BETWEEN etc.
		 * To use it, methods need to be passed as parameters.
		 * @see manual
		 * @return array
		 */

		public function returnObject() {
			if(!empty(func_get_args())) {
				$args = count(func_get_args());
				$methods = count($this->_temp);
				$slice = array_slice($this->_temp, $methods - $args, $args);
				for($i = $methods - $args; $i < $methods; $i++) {
					unset($this->_temp[$i]);
				}
				$this->subQueryClose();
				return $slice;
			}		
		}

		/**
		 * Assign parameters to $stmt->bind_Params() method.
		 * @param $_bindParams [array]
		 */

		private function bindParmas($_bindParams) {
			if(!empty($_bindParams)) {
				$paramsTypes = $this->paramsTypes($_bindParams); // Create string of param types.
				array_unshift($_bindParams, $paramsTypes); // Add $paramsTypes to $_bindParams array as first value - ['is', $user_id, $user_name].
				// Call bind_param() method with reference of $_bindParams.
				if(!call_user_func_array(array($this->stmt, 'bind_param'), $this->castToReference($_bindParams))) {
					throw new Exception("Query failed: ".$this->stmt->error); // bind_param() failed.
				}
			}
		}

		/**
		 * Loop through $_bindParams array and define type of each param.
		 * @param $_bindParams [array]
		 * @return string
		 */

		private function paramsTypes($_bindParams) {
			$temp = '';
			foreach($_bindParams as $param) {
				$temp .= $this->defineParamType($param);
			}
			return $temp;
		}

		/**
		 * Define param type.
		 * @param $value [mix]
		 * @return string
		 */

		private function defineParamType($value) {
			$valueType = gettype($value); // Get type of value.
			switch($valueType) {
				case 'boolean':
				case 'integer':
					return 'i';
				break;
				case 'double':
					return 'd';
				break;
				case 'null':
				case 'string':
					return 's';
				break;
				default:
					return 's';
				break;
			}
		}

		/**
		 * Cast $data to reference (required by call_user_func_array).
		 * @param $data [array]
		 * @return array
		 */

		private function castToReference($data) {
			$refs = array();
			foreach($data as $key => $value) {
				$refs[$key] = &$data[$key];
			}
			return $refs;
		}


		/**
		 * Custom query.
		 * @todo everything
		 * @param $query [string]
		 * @return stmt
		 */

		public function custom($query) {
			// Only if connection with db is established and there is query ready to run.
			if($this->conn !== false) {
				return $this->stmt = $this->conn->prepare($query); // Prepare Query and start $this->stmt.
			}
		}

		/**
		 * Select query. Select everything by default.
		 * It's important to close connection before returning result. This is why there is only one return and return value is assigned to $this->rtn variable.
		 * If working in a sub query mode, build query but don't run it. Return object where key is a query and value are params.
		 * @param $table [string]
		 * @param $columns [array] // "*"
		 * @return array/dbObject/false
		 */

		public function select($table, $columns = "*", $pagination = false) {
			if($pagination) $pagination = $this->pagination($table);
			$query = $this->buildQuery("SELECT ".$columns." FROM ".$table, $this->_methods);
			if($this->_subQuery === false) {
				$this->query($this->_query); // Run Query.
				// Check if flag (query) succeeded of failed.
				if($this->qsc === true) {
					$result = $this->stmt->get_result(); // Get results.
					if($result->num_rows > 0) {
						while($res = $result->fetch_object()) {
							$arr[] = $res; // Return array with data.
						}
						$this->rtn = new dataObject($arr, $pagination);
					}
					$this->close(); // Close connection and free results.
				}
			}
			else {
				$this->rtn = new dbObject('select');
				$this->rtn->setKey($this->_query);
				$this->rtn->setValue($this->_bindParams);
				$this->close();
			}
			return $this->rtn;
		}

		/**
		 * Select and limit results to one object.
		 * Limit results to 1 using limit method. Do it before buildingQuery otherwise it won't be saved in _methods array.
		 * @param $query [string]
		 * @param $_bindParams [array]
		 * @return $this->rtn [object/false]
		 */

		public function selectOne($table, $columns = "*") {
			$this->limit(1); // 
			$query = $this->buildQuery("SELECT ".$columns." FROM ".$table, $this->_methods);
			$this->query($this->_query); // Run Query.
			// Check if flag (query) succeeded of failed.
			if($this->qsc === true) {	
				$result = $this->stmt->get_result(); // Get results.
				if($result->num_rows > 0) {
					$this->rtn = $result->fetch_object(); // There is only one result. No need to run while or use array.
				}
			}
			$this->close(); // Close connection and free results.
			return $this->rtn;
		}

		/**
		 * Insert query. Don't forget to set a SET condition before running this method.
		 * @param $table [string]
		 * @return $this->rtn [boolean]
		 */

		public function insert($table) {
			$query = $this->buildQuery("INSERT INTO ".$table, $this->_methods);
			$this->query($this->_query);
			if($this->qsc) {
				$result = $this->stmt->affected_rows;
				if($result > 0) {
					$this->rtn = true;
				}
			}
			$this->close(); // Close connection and free results.
			return $this->rtn;
		}

		/**
		 * Update query. There must be a WHERE and SET condition set before running this method, otherwise it won't work.
		 * @param $table [string]
		 * @return $this->rtn [boolean]
		 */

		public function update($table) {
			// Run only when there is a WHERE condition already set.
			if(!empty($this->_methods['where']) && !empty($this->_methods['set'])) {
				$query = $this->buildQuery("UPDATE ".$table." ", $this->_methods);
				$this->query($this->_query);
				$result = $this->stmt->affected_rows;
				// When updating but no changing any data, affected rows returns 0.
				if($result >= 0) {
					$this->rtn = true;
				}
			}
			$this->close(); // Close connection and free results.
			return $this->rtn;
		}

		/**
		 * Get max value of column in table. Check if max is null to return a number (null isn't a number).
		 * MySQL will convert the string to a number for the addition. It silently ignores letters. And, the conversion stops at the first letter.
		 * To load more data than only MAX value, set $columns.
		 * If there are no digits at the beginning of the string, it will return 0.
		 * @param $table [string]
		 * @param $column [string]
		 * @param $columns [string]
		 * @return int
		 */

		public function max($table, $column, $columns = null) {
			if(!is_null($columns)) {
				$columns = $columns.','; // There must be a comma AFTER a column list.
			}
			$query = $this->buildQuery("SELECT $columns MAX($column + 0) as max FROM ".$table, $this->_methods);
			$this->query($this->_query); // Run Query.
			if($this->qsc === true) {	
				$result = $this->stmt->get_result();
				if($result->num_rows > 0) {
					$result = $result->fetch_object();
					if($result->max == null) {
						$this->rtn = 0;
					}
					else {
						$this->rtn = $result->max; // Return only max number.
					}
				}
			}
			$this->close(); // Close connection and free results.
			return $this->rtn;
		}

		/**
		 * Select a minimum value.
		 * @see max()
		 * @param $table [string]
		 * @param $column [string]
		 * @param $columns [string]
		 * @return int
		 */

		public function min($table, $column, $columns = null) {
			if(!is_null($columns)) {
				$columns = $columns.',';
			}
			$query = $this->buildQuery("SELECT $columns MIN($column + 0) as min FROM ".$table, $this->_methods);
			$this->query($this->_query); // Run Query.
			if($this->qsc === true) {	
				$result = $this->stmt->get_result();
				if($result->num_rows > 0) {
					$result = $result->fetch_object();
					if($result->min == null) {
						$this->rtn = 0;
					}
					else {
						$this->rtn = $result->min; // Return only max number.
					}
				}
			}
			$this->close(); // Close connection and free results.
			return $this->rtn;
		}

		/**
		 * Select minimum and maximum value in one query.
		 * @see max()
		 * @param $table [string]
		 * @param $column [string]
		 * @param $columns [string]
		 * @return stdObject
		 */

		public function minMax($table, $column, $columns = null) {
			if(!is_null($columns)) {
				$columns = $columns.',';
			}
			$query = $this->buildQuery("SELECT $columns MIN($column + 0) as min, MAX($column + 0) as max FROM ".$table, $this->_methods);
			$this->query($this->_query); // Run Query.
				if($this->qsc === true) {
					$result = $this->stmt->get_result(); // Get results.
					if($result->num_rows > 0) {
						while($res = $result->fetch_object()) {
							$this->rtn[] = $res; // Return array with data.
						}
					}
					$this->close(); // Close connection and free results.
				}
			$this->close(); // Close connection and free results.
			return $this->rtn;
		}

		/**
		 * Check if record exists in database. Useful to determine between updating and inserting values to db.
		 * Also it returns 1/0 or boolean. 1/0 works just like bool in if statement.
		 * @param $table [string]
		 * @return 1/0/bool
		 */

		public function checkRecordExists($table) {
			if(!empty($this->_methods['where'])) {
				$query = $this->buildQuery("SELECT EXISTS(SELECT 1 FROM ".$table, $this->_methods);
				$this->_query .= ") as bool LIMIT 1"; // Limit results to 1 and add ) to the end of where conditions.
				$this->query($this->_query);
				$result = $this->stmt->get_result();
				if($result->num_rows > 0) {
					$result = $result->fetch_object();
					$this->rtn = $result->bool;
				}
			}
			$this->close(); // Close connection and free results.
			return $this->rtn;
		}

		/**
		 * Check if table exists in database.
		 * @param $table [string]
		 * @return bool
		 */

		public function checkTableExists($table) {
			$query = $this->buildQuery("SHOW TABLES LIKE '".$table."'");
			$this->query($this->_query);
			$result = $this->stmt->get_result();
			if($result->num_rows > 0) {
				$this->rtn = true;
			}
			$this->close(); // Close connection and free results.
			return $this->rtn;
		}

		/**
		 * Count values from table. To get value use $res->amount.
		 * To count column, GROUP BY must be used.
		 * @param $table [string]
		 * @param $columns [string]
		 * @return int
		 */

		public function count($table, $columns = null) {
			if($columns !== null) $columns .= ","; // There must be comma separating columns from COUNT(*).
			$query = $this->buildQuery("SELECT $columns COUNT(*) as amount FROM $table", $this->_methods); // Amount.
			$this->query($this->_query);
			if($this->qsc === true) {
				$result = $this->stmt->get_result();
				if($result->num_rows > 0) {
					$result = $result->fetch_object();
					$this->rtn = $result->amount;
				}
			}
			$this->close(); // Close connection and free results.
			return $this->rtn;
		}

		/**
		 * Delete is not working without any WHERE conditions.
		 * @param $table [string]
		 * @return bool
		 */

		public function delete($table) {
			if(!empty($this->_methods['where'])) {
				$query = $this->buildQuery("DELETE FROM ".$table, $this->_methods);
				$this->query($this->_query);
				$result = $this->stmt->affected_rows;
				// When there is nothing to delete result is 0.
				if($result >= 0) {
					$this->rtn = true;
				}
			}
			$this->close();
			return $this->rtn;
		}

		/**
		 * Get value of field in config table. It's a static function to provide quick and reliable action.
		 * It only works with config table.
		 * @param $configName [string]
		 */

		static public function getConfig($configName) {
			$db = new self(); // Create new instance of mysqlib object.
			$db->where('config_name', $configName);
			$result = $db->selectOne('config', 'config_value as value');
			if($result) {
				return $result->value; // Return only value.
			}
		}

		/**
		 * Join table.
		 * $table is a $key in dbObject, $condition = $value, $category = $sign.
		 * @param $table [string]
		 * @param $condition [string]
		 * @param $joinType [string] = "LEFT"
		 */

		public function join($table, $condition, $joinType = "LEFT") {
			$object = new dbObject('join');
			$object->init($table, $condition, $joinType);
			$this->saveCondition('join', $object, $table);
		}

		/**
		 * Set a value to add/update record in db. Do nothing if there is no value set (isset returns false if $value = null).
		 * Keys can't repeat!
		 * @param $key [string]
		 * @param $value [string]
		 */

		public function set($key, $value = null) {
			if(isset($value)) {
				$object = new dbObject('set');
				$object->init($key, $value);
				$this->saveCondition('set', $object, $key);
			}
		}	
	
		/**
		 * Create new dbObject. Set type to where and $key, $value etc. using inti() method.
		 * Add created object to methods array using saveCondition.
		 * If any methods passed (like another where) save it into $object->methods.
		 * To create a more advanced query, sub query (like SELECT) can be passed as a value or as array of methods. 
		 * @see buildWhere();
		 * @param $key [string]
		 * @param $value [mix]
		 * @param $sign [string] // '='
		 * @param $methods [array] // null
		 */

		public function where($key, $value, $sign = '=', $methods = null) {
			$object = new dbObject('where');
			$object->init($key, $value, $sign, 'AND');
			if(!is_null($methods)) $object->methods = $methods;
			$this->saveCondition('where', $object);
		}

		/**
		 * The difference between where and whereOr lies in a different connector (OR not AND).
		 * Be sure to use at least one WHERE condition before using whereOr. Otherwise it will work like standard WHERE.
		 * @param $key [string]
		 * @param $value [mix]
		 * @param $sign [string] // '='
		 */

		public function whereOr($key, $value, $sign = '=', $methods = null) {
			$object = new dbObject('whereOr');
			$object->init($key, $value, $sign, 'OR');
			if(!is_null($methods)) $object->methods = $methods;
			$this->saveCondition('where', $object);
		}

		/**
		 * from result set.
		 * @todo description
		 * @param $key [string]
		 * @param $value [mix]
		 * @param $sign [string] // '!='
		 */

		public function whereNot($key, $value, $sign = '!=') {
			$object = new dbObject('whereNot');
			$object->init($key, $value, $sign, 'AND');
			$this->saveCondition('where', $object);
		}

		/**
		 * WHERE BETWEEN condition.
		 * As $values propety in dbObject must be an array, create it with $valueOne and $valueTwo.
		 * Ex of between. WHERE $key BETWEEN $valueOne AND $valueTwo;
		 * @param $key [string]
		 * @param $valueOne [mix]
		 * @param $valueTwo [mix]
		 */

		public function between($key, $valueOne, $valueTwo) {
			$object = new dbObject('between');
			$values = array($valueOne, $valueTwo); // This must be an array.
			$object->init($key, $values, 'BETWEEN', 'AND');
			$this->saveCondition('where', $object);
		}

		/**
		 * As above but connects to other conditions with OR not AND.
		 * @see between()
		 * @param $key [string]
		 * @param $valueOne [mix]
		 * @param $valueTwo [mix]
		 */

		public function betweenOr($key, $valueOne, $valueTwo) {
			$object = new dbObject('between');
			$values = array($valueOne, $valueTwo); // This must be an array.
			$object->init($key, $values, 'BETWEEN', 'OR');
			$this->saveCondition('where', $object);
		}

		/**
		 * Use a SOUNDEX function to search for similar results.
		 * Don't use SOUNDEX when searching value is a number.
		 * @param $key [string]
		 * @param $value [mix]
		 * @param $soundex [bool] // true
		 */

		public function like($key, $value, $soundex = true) {
			$object = new dbObject('like');
			if(!is_numeric($value)) {
				if($soundex) {
					$object->init('SOUNDEX('.$key.')', $value, 'LIKE', 'OR');
				}
				else {
					$object->init($key, $value, 'LIKE', 'OR');
				}
			}
			else {
				$object->init($key, $value, 'LIKE', 'OR');
			}
			$this->saveCondition('where', $object);
		}

		/**
		 * Order results by the column. This is a bit tricky because of sort method.
		 * @param $column [string]
		 * @param $sort [string]
		 */

		public function order($column, $sort = "ASC") {
			$object = new dbObject('order');
			$object->setValue($column);
			$object->setSign($sort);
			$this->saveCondition('order', $object);
		}

		/**
		 * Group columns by index. Usefull when selecting or couting data.
		 * Save this condition into the other group.
		 * @param $column [string]
		 */

		public function groupBy($column) {
			$object = new dbObject('groupBy');
			$object->setValue($column);
			$this->saveCondition('groupBy', $object);
		}

		/**
		 * Set limit of results. $perPage is used for pagination.
		 * Dont have to set a key, only value. That's why using setValue().
		 * If $offset param passed, this method will setup limit and offset in one go. Do it near the end to keep right order (LIMIT before OFFSET).
		 * @param $value [int]
		 * @param $perPage [int]
		 * @param $offset [int]
		 */

		public function limit($limit, $perPage = null, $offset = null) {
			$object = new dbObject('limit');
			$limit = (is_null($perPage)) ? $limit : $limit.', '.$perPage;
			$object->setValue($limit);
			$this->saveCondition('limit', $object);
			if(!is_null($offset)) $this->offset($offset);
		}

		/**
		 * Set offset of results. Usefull when paginating.
		 * Works only with limit() method and limit must be used first.
		 * @param $offset [int]
		 */

		public function offset($offset) {
			$object = new dbObject('offset');
			$object->setValue($offset);
			$this->saveCondition('offset', $object);
		}


		/**
		 * @todo description
		 * @param $offset [int]
		 */

		public function having($key, $value, $sign = '=') {
			$object = new dbObject('having');
			$object->init($key, $value, $sign);
			$this->saveCondition('having', $object);				
		}

		/**
		 * Save condition into a right place in a _methods array. If working in a sub query mode, save it to _temp array.
		 * In some cases key may repeat with different values (BETWEEN etc.). If same key shouldn't repeat (JOIN), pass it as an parameter.
		 * @param $method [string]
		 * @param $object dbObject
		 * @param $key [string] // null
		 */	 
	
		private function saveCondition($method, $object, $key = null) {
			if($this->_subQuery == false) {
				if(is_null($key)) {
					$this->_methods[$method][] = $object;
				}
				else {
					$this->_methods[$method][$key] = $object;
				}
			}
			else {
				$this->_temp[$method][] = $object;
			}
		}

		/**
		 * Build query. Start with $pre which is a short query. Then check if any conditions exist and if yes, add them to query.
		 * To build a query $method array must be passed. Normally it's gonna be $_methods but if working in sub query mode _temp array will be passed.
		 * Use echo $this->_debugMode to display full MySQL query.
		 * It is very important to run those function in right order to build valid query.
		 * @param $preQuery [string]
		 * @param $methods [array]
		 */

		public function buildQuery($preQuery, array $methods = null) {
			$this->_query .= $preQuery;
			if(isset($methods['join'])) $this->_query .= $this->buildJoin($methods['join']);	
			if(isset($methods['set'])) $this->_query .= $this->buildSet($methods['set']);	
			if(isset($methods['where'])) $this->_query .= $this->buildWhere($methods['where']);
			if(isset($methods['groupBy'])) $this->_query .= " GROUP BY ".$methods['groupBy'][0]->value[0];
			if(isset($methods['order'])) $this->_query .= " ORDER BY ".$methods['order'][0]->value[0].' '.$methods['order'][0]->sign;
			if(isset($methods['limit'])) $this->_query .= " LIMIT ".$methods['limit'][0]->value[0];
			if(isset($methods['offset'])) $this->_query .= " OFFSET ".$methods['offset'][0]->value[0];
			if(isset($methods['having'])) $this->_query .= " HAVING COUNT(DISTINCT ".$methods['having'][0]->key.') '.$methods['having'][0]->sign.' '.$methods['having'][0]->value[0];
			if($this->debugMode === true) echo $this->_query.'<br />'; // Just for debbuging.
		}

		/**
		 * Build a where conditions.
		 * If $where->methods is set, it means that whole expression contains more than on condition and everthing should be placed in bracket.
		 * To do that, method will call itself with $where->methods as parameter, and then build and return inner WHERE. 
		 * As only one WHERE per query is needed, set $start to false to ignore this keyword.
		 * If value is a object, it means that expression is a sub query.
		 * Don't use connector for the first condition.
		 * @see readme - to to learn more 
		 * @param methods [array]
		 * @param start [bool]
		 * @return string
		 */

		public function buildWhere(array $methods, $start = true) {
			$cond = ($start === true) ? ' WHERE' : '';
			foreach($methods as $key => $where) {
				if($start === false || $key > 0) {
					$cond .= ' '.$where->connector;
				}
				if(isset($where->methods)) {
					$this->bindValues($where);
					$cond .= ' ('.$where->key.' '.$where->sign.' ?'.$this->buildWhere($where->methods['where'], false).')';
				}
				else {
					$cond .= ' '.$where->key.' '.$where->sign;
					if(is_object($where->value[0])) {
						switch($where->value[0]->objectType()) {
							case 'select':
								$this->bindValues($where->value[0]);
								$cond .= ' ('.$where->value[0]->key.')';							
							break;
						}
					}
					else {
						switch($where->objectType()) {
							case 'where':
							case 'whereOr':
							case 'whereNot':
								$this->bindValues($where);
								$cond .= ' ?';
							break;
							case 'like':
								$this->bindValues($where, '%');
								$cond .= ' ?';
							break;
							case 'between':
								$this->bindValues($where);
								$cond .= ' ? AND ?';
							break; 
						}
					}
				}
			}
			return $cond;
		}

		/**
		 * Join different tables.
		 * @param methods [array]
		 * @return string
		 */

		public function buildJoin(array $methods) {
			$cond = '';
			foreach($methods as $join) {
				$cond .= ' '.$join->sign." JOIN ".$join->key." ON ".$join->value[0]." ";
			}
			return $cond;
		}

		/**
		 * Set values to insert/update. Separate them with a comma.
		 * @param methods [array]
		 * @return string
		 */

		public function buildSet(array $methods) {
			$cond = ' SET ';
			$last = end(array_keys($methods));
			foreach($methods as $key => $set) {
				$this->bindValues($set);
				$cond .= $set->key.' '.$set->sign.' ?';
				if($key != $last) {
					$cond .= ', ';
				}
			}
			return $cond;
		}

		/**
		 * Get values from an array and add them to _bindParams array.
		 * If param is used in a WHERE LIKE condition, use $pre to setup '%' sings (those needs to be before and after the param).
		 * If $pre is null, do not just add it to $value because it will always cause to change it to string even if it's int.
		 * @param $values [dbObject]
		 * @param $pre [string]
		 */

		public function bindValues(dbObject $dbObject, $pre = null) {
			foreach($dbObject->value as $value) {
				$this->_bindParams[] = (is_null($pre)) ? $value : $pre.$value.$pre;
			}
		}

		/**
		 * Use to turn on debug mode.
		 * @todo description.
		 */

		public function debugMode() {
			$this->debugMode = true;
		}

		/**
		 * Easy to use debug method. It is better to use this one than just var_dump because conn and stmt properties makes it really hard 
		 * to read (because they are objects with many properties itself).
		 * To use, debug mode must be enabled.
		 */

		public function debug() {
			if($this->debugMode == true) {
				$vars = get_object_vars($this); // Get all object properties.
				foreach($vars as $key => $value) {
					if(strpos($key, 'conn') !== false || strpos($key, 'stmt') !== false) continue; // Pass conn and stmt.
					else {
						echo $key.' => ';
						var_dump($value);
						echo '<br />'; // Make it more readable.
					}
				}
			}
		}

		/**
		 * To keep url nice and clean, instead of putting column name in it, use a friendly short name of column and then, transform it thanks to this function.
		 * In $values array, key is always short friendly name and $value is a full column name.
		 * First item of the array is always a default value.
		 * Set $direction as table is not always ordered ASCENDING by default (descending when ordering by date).
		 * Values from this array are used as parameters of ORDER condition.
		 * @param $values [array]
		 * @param $direction [string]
		 */

		public function sort(array $values, $direction = 'ASC') {
			$data = getGetValues();
			$arr = array();
			$arr['column'] = reset($values); // Default value.
			$arr['direction'] = $direction; // Default value.
			if(isset($data->sort)) {
				foreach($values as $key => $value) {
					if($data->sort == $key) {
						$arr['column'] = $value;
					}
				}
			}
			if(isset($data->direction)) {
				switch($data->direction) {
					case 'asc':
					default:
						$arr['direction'] = 'ASC';
					break;
					case 'desc':
						$arr['direction'] = 'DESC';
					break;
				}
			}
			$this->order($arr['column'], $arr['direction']);
		}

		/**
		 *
		 */

		public function pagination($table, $perPage = 5) {
			$this->reuseConditions = true;
			$count = $this->count($table);
			if($count) {
				$totalPages = ceil($count/$perPage);
				$currentPage = 1;
				if(isset($_GET['page'])) {
					$currentPage = $_GET['page'];
					$offset = (($currentPage - 1) * $perPage);
					$this->limit($offset, $perPage);
				}
				else {
					$this->limit($perPage);
				}
				return pagination_nav($totalPages, $currentPage);
			}
			else {
				return false;
			}
		}

		/**
		 * Close and reset prepared statement and connection then setup a new connection. 
		 */

		public function close() {
			$this->qsc = false;
			$this->_query = '';
			$this->_bindParams = array();
			if($this->reuseConditions !== true) {
				$this->_methods = array();
			}
			$this->_temp = array();
			$this->_subQuery = false;
			$this->reuseConditions = false;
			if($this->conn !== null)  {
				$this->conn->close(); // Close connection.
				$this->conn = null; // Free result.
			}
			if($this->stmt !== null) {
				$this->stmt->close();
				$this->stmt = null;
			}
			$this->connect();
		}
	}

?>