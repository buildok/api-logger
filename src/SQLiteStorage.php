<?php
namespace buildok\logger;

use buildok\logger\exceptions\LoggerException;
use \SQLite3;

/**
 * SQLiteStorage class
 */
class SQLiteStorage
{
	/**
	 * Log filename
	 * @var string
	 */
	private $logFile;

	/**
	 * DB connection object
	 * @var \SQLite3
	 */
	private $db;

	/**
	 * Initialization
	 * @param string $logFile Log filename
	 */
	public function __construct($logFile)
	{
		$dir = substr($logFile, 0, strrpos($logFile, '/'));
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}

		$this->logFile = $logFile;
		$this->connect();
	}

	/**
	 * Select records
	 * @param  integer $startID Offset
	 * @param  integer $limit   Limit
	 * @return array
	 */
	public function show($startID = 0, $limit = 100)
	{
		// $stmt = $this->db->prepare('SELECT * FROM stream WHERE id > :startID LIMIT :limit');
		$stmt = $this->db->prepare('SELECT * FROM stream WHERE id > :startID');
		$stmt->bindValue(':startID', $startID, \SQLITE3_INTEGER);
		$stmt->bindValue(':limit', $limit, \SQLITE3_INTEGER);

		$ret = [];
		$result = $stmt->execute();
		while($row = $result->fetchArray(\SQLITE3_ASSOC)) {
			$ret[] = $row;
		}

		return $ret;
	}

	/**
	 * Insert records
	 * @param  array $rows Array of log items
	 */
	public function save($rows)
	{
		$serialize = '';
		$stmt = $this->db->prepare('INSERT INTO stream (json) VALUES(:json)');
		$stmt->bindParam(':json', $serialize);

		foreach ($rows as $key => $row) {
			$serialize = json_encode($row);
			$stmt->execute();
		}
	}

	/**
	 * Create DB connection
	 * @throws LoggerException
	 */
	private function connect()
	{
		try {
			$this->db = new \SQLite3($this->logFile);

			$tblStreamLog = 'CREATE TABLE IF NOT EXISTS stream (
				id 			INTEGER 	PRIMARY KEY 	AUTOINCREMENT,
				json 		TEXT 		NOT NULL
			)';

			$this->db->exec($tblStreamLog);
		} catch (\Exception $e) {

			throw new LoggerException($e->getMessage());
		}

	}
}