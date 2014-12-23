<?php 
	/**
	 * PDO DATABASE CLASS
	 * 
	 * Handling and Managing of database connection and access to database  
	 *
	 * @author Arvin Kent Lazaga <arvinkent17@gmail.com>
	 * @since November 18, 2014
	 * @copyright 2014
	 **/
	class Database {
		/**
 		 * Database Class Data Variables.
 		 *
 		 * @access private
		 **/
		private $server = "SERVER NAME HERE";
		private $username = "SERVER USERNAME HERE";
		private $password = "SERVER PASSWORD HERE";
		private $database = "DATABASE NAME HERE";
		private $server_version;
		private $server_info;
		private $server_status;
		private $dbh;
		private $object;

		/**
		 * Automatically Opens Database Connection when Database Class is being instantiated.
		 * 
		 * @access public
		 **/
		public function __construct() {
			$this->openConnection();
		}

		/**
		 * Opens Server and Database Connection.
		 *
		 * @return error message
		 * @access public
		 **/
		public function openConnection() {
			try {
				$this->dbh = new PDO("mysql:host=" . $this->server . ";dbname=" . $this->database, $this->username, $this->password);
				$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->setServerDetails(PDO::ATTR_SERVER_VERSION, PDO::ATTR_SERVER_INFO, PDO::ATTR_CONNECTION_STATUS);
			} 
			catch (PDOException $e) {
				die("Connection to database failed because: " . $e->getMessage());
			}
		}

		public function setServerDetails($server_version, $server_info, $server_status) {
			$this->server_version = $this->dbh->getAttribute($server_version);
			$this->server_info = $this->dbh->getAttribute($server_info);
			$this->server_status = $this->dbh->getAttribute($server_status);
		}
		
		public function getServerVersion() {
			return $this->server_version;
		}

		public function getServerInfo() {
			return $this->server_info;
		}

		public function getServerStatus() {
			return $this->server_status;
		}

		/**
		 * MySQL Injection Handler.
		 *
		 * Dynamically Handles and Executes any MySQL Scripts with the features of Sanitizing  
		 * and Binding of Parameters to avoid any SQL Injections.
		 *
		 * @param string $sql
		 * @param array $param
		 * @access public
		 **/
		public function executeMySQL($sql, $param = null, $search = false) {
			if (is_null($param)) {
				try {
					$this->object = $this->dbh->prepare($sql);
					$this->object->execute();
				}
				catch (PDOException $e) {
					die("Failed to execute MySQL script due to: " . $e->getMessage());
				}
			}
			else {
				try {
					$this->object = $this->dbh->prepare($sql);
					for ($index = 0; $index < count($param); ++$index) {
						if (is_string($param[$index])) {
							if ($search == true) {
								$this->object->bindValue($index + 1, "$param[$index]%", PDO::PARAM_STR);
							} 
							else {
								$this->object->bindParam($index + 1, $param[$index], PDO::PARAM_STR);
							}
						}
						elseif (is_bool($param[$index])) {
							$this->object->bindParam($index + 1, $param[$index], PDO::PARAM_BOOL);
						}
						else {
							$this->object->bindParam($index + 1, $param[$index], PDO::PARAM_INT);
						}
					}
					$this->object->execute();
				}
				catch (PDOException $e) {
					die("Failed to execute MySQL script with parameters due to: " . $e->getMessage());
				}
			}
		}

		/**
		 * Dynamically Counts the total rows of the current object.
		 *
		 * @return number of rows
		 * @access public
		 **/
		public function rowCount() {
			return $this->object->rowCount();
		}

		/**
		 * Dynamically Fetch the rows of the current object.
		 *
		 * @return array of data
		 * @access public
		 **/
		public function rowFetch() {
			return $this->object->fetch(PDO::FETCH_ASSOC);
		}

		/**
		 * Dynamically checks if there is a item inserted on the current object's database.
		 *
		 * @return true or false
		 * @access public
		 **/
		public function isInserted() {
			return $this->dbh->lastInsertId();
		}

		public function PDOError() {
			return $this->dbh->errorCode();
		}

		/**
		 * Automatically closes connection for security purposes.
		 *
		 * @access public
		 **/
		public function closeConnection() {
			if (isset($this->openConnection)) {
				$this->dbh = null;
				unset($this->openConnection);
			}
		}
	}
	$db = new Database();
?>