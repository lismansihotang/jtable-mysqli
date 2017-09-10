<?php
/**
 * @author  : Lisman Tua Sihotang
 * @email   : lisman.sihotang@gmail.com
 */
/**
 * @define dbHost
 */
define('dbHost', 'localhost');
/**
 * @define dbUser
 */
define('dbUser', 'root');
/**
 * @define dbPass
 */
define('dbPass', '');
/**
 * @define dbName
 */
define('dbName', 'jtabletestdb');
if (function_exists('connDb') === false) {
    /**
     * connection into database.
     *
     * @return \mysqli
     */
    function connDb()
    {
        return new mysqli(dbHost, dbUser, dbPass, dbName);
    }
}
if (function_exists('insertData') === false) {

    /**
     * @param string $tblName
     * @param array $data
     * @return bool|mysqli_result
     */
    function insertData($tblName = '', array $data = [])
    {
        $field = implode(',', array_keys($data));
        $values = [];
        foreach (array_values($data) as $value) {
            $values[] = '"' . $value . '"';
        }
        $dataValues = implode(', ', $values);
        $strSQL = 'INSERT INTO ' . $tblName . '(' . $field . ') VALUES (' . $dataValues . ') ';
        $conn = connDb();
        if ($conn->query($strSQL) !== false) {
            return mysqli_insert_id($conn);
        } else {
            return $conn->query($strSQL);
        }

    }
}
if (function_exists('updateData') === false) {
    /**
     * update data in table.
     *
     * @param string $tblName
     * @param array $data
     * @param array $condition
     *
     * @return boolean
     */
    function updateData($tblName = '', array $data = [], array $condition = [])
    {
        # params for update data
        $fieldKeyData = array_keys($data);
        $arrData = [];
        for ($i = 0; $i < count($fieldKeyData); ++$i) {
            $arrData[$i] = $fieldKeyData[$i] . '="' . $data[$fieldKeyData[$i]] . '"';
        }
        $updateData = implode(', ', $arrData);
        # params for condition update data
        $fieldKeyCondition = array_keys($condition);
        $arrCondition = [];
        for ($i = 0; $i < count($fieldKeyCondition); ++$i) {
            $arrCondition[$i] = $fieldKeyCondition[$i] . '="' . $condition[$fieldKeyCondition[$i]] . '"';
        }
        $where = implode(', ', $arrCondition);
        $strSQL = 'UPDATE ' . $tblName . ' SET ' . $updateData . ' WHERE ' . $where . ' ';
        $conn = connDb();
        return $conn->query($strSQL);
    }
}
if (function_exists('deleteData') === false) {
    /**
     * delete data in table.
     *
     * @param string $tblName
     * @param array $condition
     *
     * @return boolean
     */
    function deleteData($tblName = '', array $condition = [])
    {
        # params for condition update data
        $fieldKeyCondition = array_keys($condition);
        $arrCondition = [];
        for ($i = 0; $i < count($fieldKeyCondition); ++$i) {
            $arrCondition[$i] = $fieldKeyCondition[$i] . '="' . $condition[$fieldKeyCondition[$i]] . '"';
        }
        $where = implode(', ', $arrCondition);
        $strSQL = 'DELETE FROM ' . $tblName . ' WHERE ' . $where . ' ';
        $conn = connDb();
        return $conn->query($strSQL);
    }
}
if (function_exists('getData') === false) {
    /**
     * get data from table.
     *
     * @param string $tblName
     * @param string $field
     * @param array $condition
     * @param string $result
     *
     * @return array
     */
    function getData($tblName = '', $field = '*', array $condition = [], $result = 'result')
    {
        $strSQL = 'SELECT ' . $field . ' FROM ' . $tblName . ' ';
        if (count($condition) > 0) {
            # params for condition select data
            $fieldKeyCondition = array_keys($condition);
            $arrCondition = [];
            for ($i = 0; $i < count($fieldKeyCondition); ++$i) {
                $arrCondition[$i] = $fieldKeyCondition[$i] . '="' . $condition[$fieldKeyCondition[$i]] . '"';
            }
            $where = implode(' and ', $arrCondition);
            $strSQL .= ' WHERE ' . $where . ' ';
        }
        $conn = connDb();
        $records = $conn->query($strSQL);
        $data = [];
        if ($records !== false) {
            if ($records->num_rows > 0) {
                switch (strtolower($result)) {
                    case 'result':
                        $data = $records->fetch_all();
                        break;
                    case 'row':
                        $data = $records->fetch_assoc();
                        break;
                }
            }
        }

        return $data;
    }
}

if (function_exists('getHeaderTable') === false) {
    /**
     * @param $tableName
     * @return array
     */
    function getHeaderTable($tableName)
    {
        $strSQL = 'SHOW COLUMNS FROM ' . $tableName . ' ';
        $conn = connDb();
        $records = $conn->query($strSQL);
        $data = [];
        if ($records->num_rows > 0) {
            foreach ($records->fetch_all() as $row) {
                $data[] = $row[0];
            }
        }
        return $data;
    }
}


