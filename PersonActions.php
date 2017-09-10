<?php
include_once 'mysqli/CrudMysqliHelper.php';

try {
    $tableName = 'people';
    $primaryKey = 'PersonId';

    if ($_GET["action"] === "list") {
        # Untuk mendapatkan list data dari table
        $header = getHeaderTable($tableName);
        $data = getData($tableName);

        $rows = [];
        $i = 0;
        foreach ($data as $subData) {
            foreach ($subData as $key => $row) {
                $rows[$i][$key] = $row;
                $rows[$i][$header[$key]] = $row;
            }
            $i++;
        }

        $jTableResult = [];
        $jTableResult['Result'] = "OK";
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    } else if ($_GET["action"] === "create") {
        # Untuk menambahkan data
        $insert = insertData($tableName, $_POST);

        $header = getHeaderTable($tableName);
        $data = getData($tableName, '*', [$primaryKey => $insert]);

        $rows = [];
        foreach ($data as $subData) {
            foreach ($subData as $key => $row) {
                $rows[$key] = $row;
                $rows[$header[$key]] = $row;
            }
        }

        $jTableResult = [];
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $rows;
        print json_encode($jTableResult);
    } else if ($_GET["action"] === "update") {
        # Untuk mengubah Data
        $header = getHeaderTable($tableName);
        $data = [];
        foreach ($header as $key => $value) {
            if ($value !== $primaryKey) {
                if (array_key_exists($value, $_POST) === true) {
                    $data[$value] = $_POST[$value];
                }
            }
        }
        updateData($tableName, $data, [$primaryKey => $_POST[$primaryKey]]);

        $jTableResult = [];
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);
    } else if ($_GET["action"] === "delete") {
        # Untuk menghapus data
        deleteData($tableName, [$primaryKey => $_POST[$primaryKey]]);

        $jTableResult = [];
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);
    }else if ($_GET["action"] === "list_dropdown") {
        # Untuk mendapatkan list data dari table
        $header = getHeaderTable($tableName);
        $data = getData($tableName);

        $rows = [];
        $i = 0;
        foreach ($data as $row) {
            $rows[$i]['DisplayText'] = $row[1];
            $rows[$i]['Value'] = $row[0];
            $i++;
        }

        $jTableResult = [];
        $jTableResult['Result'] = "OK";
        $jTableResult['Options'] = $rows;
        print json_encode($jTableResult);
    }

} catch (Exception $ex) {
    # Menampilkan pesan error jika data tidak berhasil di load
    $jTableResult = [];
    $jTableResult['Result'] = "ERROR";
    $jTableResult['Message'] = $ex->getMessage();
    print json_encode($jTableResult);
}
