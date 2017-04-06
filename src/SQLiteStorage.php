<?php
namespace buildok\logger;

use buildok\logger\exceptions\LoggerException;
use \SQLite3;

/**
 *
 */
class SQLiteStorage
{
	const DB_FILE = '/source/logger.db';

	private $db;

	public function __construct($config = null)
	{
		$this->connect();
	}

	public function show($startID = 0, $limit = 100)
	{
		$stmt = $this->db->prepare('SELECT * FROM stream WHERE id > :startID LIMIT :limit');
		$stmt->bindValue(':startID', $startID, SQLITE3_INTEGER);
		$stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);

		$ret = [];
		$result = $stmt->execute();
		while($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$ret[] = $row;
		}

		return $ret;
	}

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



	private function connect()
	{
		try {
			$this->db = new SQLite3(self::DB_FILE);

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