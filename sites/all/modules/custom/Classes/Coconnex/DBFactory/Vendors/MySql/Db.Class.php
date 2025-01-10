<?php
/*
Name of File :- DBFunctions.Class.php
Module Name :- DB functions Class
Purpose :- Contains the essential database functions like getting the connection, closing the connection, insert, update and delete
File Created By :- Abhijeet Gogte
File Created On :- Monday, March 21, 2011
File Modified By :-
Last Modified On :-
Version :- 1
*/

namespace Coconnex\DBFactory\Vendors\MySql;

include_once(dirname(dirname(dirname(__FILE__))) . "/Interfaces/I_DB_Operation.Interface.php");

use Coconnex\DBFactory\Interfaces\I_DB_Operation;

class Db implements I_DB_Operation
{

	public $meta;

	// Function declarations
	public function __construct($id = null)
	{
		if (is_numeric($id)) $this->load($id);
	}

	public function configureMeta($uid, $tablename, $primarykey, $createdonFldName = null, $createdbyFldName = null, $modifiedonFldName = null, $modifiedbyFldName = null)
	{
		$this->meta = new \stdClass();
		$this->meta->uid = $uid;
		$this->meta->tablename = $tablename;
		$this->meta->primarykey = $primarykey;
		$this->meta->createdonFldName = $createdonFldName;
		$this->meta->createdbyFldName = $createdbyFldName;
		$this->meta->modifiedonFldName = $modifiedonFldName;
		$this->meta->modifiedbyFldName = $modifiedbyFldName;
	}

	public function load($id = null)
	{
		if (is_numeric($id)) {
			$vars = get_object_vars($this);
			unset($vars['meta']);
			$cmd = "SELECT ";
			$fields = "";
			$i = 0;
			foreach ($vars as $key => $val) {
				$fields .= $this->meta->tablename . "." . $key . (($i < count($vars) - 1) ? "," : "");
				$i++;
			}
			$table = "FROM " . $this->meta->tablename . " ";
			$cond = "WHERE " . $this->meta->primarykey . " = " . $id;
			$sql = $cmd . " " . $fields . " " . $table . " " . $cond;
			$result = $this->RetrieveRecord($sql);
			$this->setProps($result);
		}
	}

	public function save($_arrData = array())
	{
		$arrData = array();
		$response = 0;

		if ($this->meta->tablename !== '' && $this->meta->primarykey !== '') {
			if (empty($_arrData)) {
				$arrData = get_object_vars($this);
				unset($arrData['meta']);
			} else {
				$arrData = $_arrData;
			}

			if ($this->meta->modifiedonFldName != null) {
				$arrData[$this->meta->modifiedonFldName] = date('Y-m-d H:i:s');
			}
			if (
				$this->meta->modifiedbyFldName != null
				&& is_numeric($this->meta->uid)
			) {
				$arrData[$this->meta->modifiedbyFldName] = $this->meta->uid;
			}

			if (is_numeric($arrData[$this->meta->primarykey])) {
				$condn = $this->meta->primarykey . " = " . $arrData[$this->meta->primarykey];
				unset($arrData[$this->meta->primarykey]);
				$response = $this->UpdateRecord($arrData, $this->meta->tablename, $condn);
			} else {
				if ($this->meta->createdonFldName != null) {
					$arrData[$this->meta->createdonFldName] = date('Y-m-d H:i:s');
				}
				if (
					$this->meta->createdbyFldName != null
					&& is_numeric($this->meta->uid)
				) {
					$arrData[$this->meta->createdbyFldName] = $this->meta->uid;
				}
				unset($arrData[$this->meta->primarykey]);
				$response = $this->InsertRecord($arrData, $this->meta->tablename);
			}
		}
		return $response;
	}

	public function add($_arrData = array())
	{
		$this->save($_arrData);
	}

	public function modify($_arrData = array())
	{
		$this->save($_arrData);
	}

	public function remove($param = null)
	{
		$this->RemoveRecord($param);
	}

	public function setProps($props)
	{
		if (!(count($props) > 0)) return;
		foreach ($props[0] as $prop => $val) {
			$this->{$prop} = $val;
		}
	}

	public function executeCommand($txtCommand)
	{
		$result = $this->getResultset($txtCommand);
		return;
	}

	public function getResultset($strquery)
	{
		$result = \mysql_query($strquery);
		return $result;
	}

	public function getResultRow($resultSet, $returntype = 'object')
	{
		switch ($returntype) {
			case 'object':
				return db_fetch_object($resultSet);
				break;
			case 'array':
				return db_fetch_array($resultSet);
				break;
			default:
				return db_fetch_object($resultSet);
				break;
		}
		return;
	}

	public function InsertRecord($savearray, $tablename, $title = NULL, $node = NULL)
	{
		$uid = 0;
		if (isset($_SESSION['UserId'])) $uid = $_SESSION['UserId'];
		$strquery = "INSERT INTO " . $tablename;
		$strcolumns = " (";
		$strvalues = " VALUES (";
		foreach ($savearray as $key => $val) {
			$strcolumns .= $key . ",";
			$strvalues .= "'" . \mysql_real_escape_string($val) . "',";
		}
		$strcolumns = rtrim($strcolumns);
		$strvalues = rtrim($strvalues);
		$strcolumns = substr($strcolumns, 0, strlen($strcolumns) - 1);
		$strvalues = substr($strvalues, 0, strlen($strvalues) - 1);
		$strcolumns .= ") ";
		$strvalues .= ");";
		$strquery = $strquery . $strcolumns . $strvalues;

		// $ExeQuery = "Executed on ".date('y-m-d h:i:s')." \r\n" .$strquery;
		// $fp = file_put_contents('QueryLogger.log', $ExeQuery . "\r\n", FILE_APPEND);

		$result = $this->getResultset($strquery);
		if (!$result)
			return -1;
		else {
			$LastInsertQry = "SELECT LAST_INSERT_ID() as InsertId";
			$result = $this->getResultset($LastInsertQry);
			$row = \mysql_fetch_assoc($result);
			$ID = $row['InsertId'];
			return $ID;
		}
	}

	public function UpdateRecord($savearray, $tablename, $conditions)
	{
		$uid = 0;
		if (isset($_SESSION['UserId'])) $uid = $_SESSION['UserId'];
		$strquery = "UPDATE " . $tablename;
		$strcolumns = " SET ";
		$strcondns = " WHERE ";
		foreach ($savearray as $key => $val) {
			$flag = strpos($val, $key);
			if (!($flag === false))
				$strcolumns .= $key . "=" . \mysql_real_escape_string($val) . ",";
			else
				$strcolumns .= $key . "=" . "'" . \mysql_real_escape_string($val) . "',";
		}
		$strcolumns = rtrim($strcolumns);
		$strcolumns = substr($strcolumns, 0, strlen($strcolumns) - 1);

		$strcondns .= $conditions . ";";

		$strquery = $strquery . $strcolumns . $strcondns;

		// $ExeQuery = "Executed on ".date('y-m-d h:i:s')." \r\n" .$strquery;
		// $fp = file_put_contents('QueryLogger.log', $ExeQuery . "\r\n", FILE_APPEND);

		$result = $this->getResultset($strquery);
		return $result;
	}

	public function DeleteRecord($param = null)
	{
		if ($this->meta->tablename !== '' && $this->meta->primarykey !== '') {
			$arrData = get_object_vars($this);
			$strquery = "delete from " . $this->meta->tablename;
			$strcondns = " where ";
			$strcondns .= " " . $this->meta->primarykey . " = " . $arrData[$this->meta->primarykey] . " ";
			if (isset($param['conditions'])) $strcondns .= " AND " . $param['conditions'];

			$strquery = $strquery . $strcondns;
			$result = $this->getResultset($strquery);
			return $result;
		}
		return null;
	}

	public function DeleteRecordWithArr($param = null)
	{
		if ($param !== null) {
			$strquery = "delete from ".$param['table'];
			$strcondns = " where ";
			$strcondns .= $param['conditions'];

			$strquery = $strquery.$strcondns;
			$result = $this->getResultset($strquery);
			return $result;
		}
		return null;
	}

	public function RetrieveRecord($strquery)
	{
		$returnarray = array();
		$result = $this->getResultset($strquery);
		if ($result) {
			while ($row = mysql_fetch_assoc($result)) {
				array_push($returnarray, $row);
			}
			$result = NULL;
		}
		return $returnarray;
	}

	public function ResultXML($strquery, $nodename = "ROWS")
	{
		$returnarray = $this->RetrieveRecord($strquery);
		$xmldoc = new \DomDocument('1.0');
		$rows = $xmldoc->createElement($nodename);
		$xmldoc->appendChild($rows);
		foreach ($returnarray as $arraykey => $subarray) {
			$row = $xmldoc->createElement("ROW");
			foreach ($subarray as $key => $val) {
				$row->setAttribute($key, $val);
			}
			$rows->appendChild($row);
		}
		unset($returnarray);
		return $rows;
	}

	public function ResultXMLNode($strquery, $nodename = "ROWS", $arrNode = array())
	{
		$returnarray = $this->RetrieveRecord($strquery);

		$xmldoc = new \DOMDocument('1.0');
		$rows = $xmldoc->createElement($nodename);
		foreach ($arrNode as $key => $val) {
			$rows->setAttribute($key, $val);
		}
		$xmldoc->appendChild($rows);
		foreach ($returnarray as $arraykey => $subarray) {

			foreach ($subarray as $key => $val) {
				$row = $xmldoc->createElement($key);
				$rowtext = $xmldoc->createTextNode($val);
				$row->appendChild($rowtext);
				$rows->appendChild($row);
			}
		}
		unset($returnarray);
		return $rows;
	}

	public function RemoveRecord($id)
	{
		$arrParam = array();
		$arrParam['deleted'] = "1";
		$arrParam['id'] = $id;
		$result = $this->save($arrParam);
		return $result;
	}

	public function RevokeRecord($id)
	{
		$arrParam = array();
		$arrParam['deleted'] = "0";
		$arrParam['id'] = $id;
		$result = $this->save($arrParam);
		return $result;
	}
}
