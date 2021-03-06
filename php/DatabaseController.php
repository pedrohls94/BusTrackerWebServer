<?php
class DatabaseController {

	const TABLE_BUS = "Bus";
	const BUS_COL_ID = "Id";
	const BUS_COL_LINE_ID = "LineId";
	const BUS_COL_EPOS_ID = "EposId";

	const TABLE_LINE = "Line";
	const LINE_COL_ID = "Id";
	const LINE_COL_NAME = "Name";

    const TABLE_STOP = "Stop";
	const STOP_COL_ID = "Id";
	const STOP_COL_LOCATION = "Location";

    const TABLE_LINESTOP = "LineStop";
	const LINESTOP_COL_LINE_ID = "LineId";
	const LINESTOP_COL_STOP_ID = "StopId";

	const SERVERNAME = "localhost";
	const USERNAME = "root";
	const PASSWORD = "icpedu";
	const DBNAME = "bustrackerwebserver";

	function createLine($name) {
		$sql = 'INSERT INTO' . ' '.self::TABLE_LINE.' ';
		$sql .= '('.self::LINE_COL_NAME.')';
		$sql .= ' VALUES ' . '('.$name.');';
		return $this->processQueryGetId($sql);
	}

	function createBus($lineId, $eposId) {
        $sql = 'INSERT INTO' . ' '.self::TABLE_BUS.' ';
		$sql .= '('.self::BUS_COL_LINE_ID.','.self::BUS_COL_EPOS_ID.')';
		$sql .= ' VALUES ' . '("'.$lineId.'","'.$eposId.'");';
		$this->processQuery($sql);
	}

    function fetchAllLines() {
		$sql = 'SELECT * FROM' . ' '.self::TABLE_LINE.';';
        $result = $this->processQuery($sql);
        return $this->rowsToArray($result);
	}

    function findLine($id) {
		$sql = 'SELECT * FROM' . ' '.self::TABLE_LINE.' ';
        $sql .= 'WHERE ' . self::LINE_COL_ID . '=' . $id . ';';
		$result = $this->processQuery($sql);
		return $result->fetch_assoc();
	}

    function deleteStop($lineId, $location) {
        $sql = 'SELECT '.self::LINESTOP_COL_STOP_ID.' FROM' . ' '.self::TABLE_LINESTOP.' ';
        $sql .= 'WHERE ' . self::LINESTOP_COL_LINE_ID . '=' . $lineId . ';';
        $result = $this->processQuery($sql);
        $array = rowsToArray($result);

        foreach ($array as $stopId) {
            $sql = 'SELECT '.STOP_COL_ID.' FROM' . ' '.self::TABLE_STOP.' ';
            $sql .= 'WHERE ' . self::STOP_COL_ID . '=' . $stopId;
            $sql .= '" AND ' . self::STOP_COL_LOCATION . '="' . $location . '";';
    		$result = $this->processQuery($sql)->fetch_assoc();
            if(result != 0) {
                break;
            }
        }

        $sql = 'DELETE FROM' . ' '.self::TABLE_STOP.' ';
        $sql .= 'WHERE ' . self::STOP_COL_ID . '="' . $stopId . '";';
        $this->processQuery($sql);

        $sql = 'DELETE FROM' . ' '.self::TABLE_LINESTOP.' ';
        $sql .= 'WHERE ' . self::LINESTOP_COL_LINE_ID . '="' . $lineId;
        $sql .= '" AND ' . self::LINESTOP_COL_STOP_ID . '="' . $location . '";';
        $this->processQuery($sql);
    }

    function createStop($lineId, $location) {
        $sql = 'INSERT INTO' . ' '.self::TABLE_STOP.' ';
		$sql .= '('.self::STOP_COL_LOCATION.')';
		$sql .= ' VALUES ' . '('.$location.');';
		$stopId = $this->processQueryGetId($sql);

        $sql = 'INSERT INTO' . ' '.self::TABLE_LINESTOP.' ';
		$sql .= '('.self::LINESTOP_COL_LINE_ID.','.self::LINESTOP_COL_STOP_ID.')';
		$sql .= ' VALUES ' . '("'.$lineId.'","'.$stopId.'");';
		$this->processQuery($sql);
    }

    function fetchStopsByLine($lineId) {
        $sql = 'SELECT '.self::LINESTOP_COL_STOP_ID.' FROM' . ' '.self::TABLE_LINESTOP.' ';
		$sql .= 'WHERE ' . self::LINESTOP_COL_LINE_ID . '=' . $lineId . ';';
		$result = $this->processQuery($sql);
        $array = rowsToArray($result);

        $stops = array();
        foreach ($array as $stopId) {
            $sql = 'SELECT * FROM' . ' '.self::TABLE_STOP.' ';
            $sql .= 'WHERE ' . self::STOP_COL_ID . '=' . $stopId . ';';
    		$result = $this->processQuery($sql);
            $stops[] = $result->fetch_assoc();
        }
        return $stops;
    }

    function rowsToArray($rows) {
        $items = array();
        if ($rows->num_rows > 0) {
            while($row = $rows->fetch_assoc()) {
                $items[] = $row;
            }
        }
        return $items;
    }

    function processQuery($sql) {
        $conn = new mysqli(self::SERVERNAME, self::USERNAME, self::PASSWORD, self::DBNAME);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query($sql);
        return $result;
    }

    function processQueryGetId($sql) {
        $conn = new mysqli(self::SERVERNAME, self::USERNAME, self::PASSWORD, self::DBNAME);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $result = $conn->query($sql);
        $id = mysqli_insert_id($conn);
        $conn->close();
        return $id;
    }
}
?>
