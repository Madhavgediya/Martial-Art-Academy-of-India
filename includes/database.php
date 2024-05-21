<?php

$c_file = basename($_SERVER['SCRIPT_FILENAME']);
include_once("connection.php");

function db_perform($table, $data, $action = 'insert', $parameters = '', $orderby = '', $cquery = '') {
    global $dbh,$c_file;
    if ($action == 'insert') {
        reset($data);
        if ($cquery == '') {
            $query = 'INSERT INTO ' . $table . ' (';
           /* while (list($columns, ) = each($data)) {
                $query .= $columns . ', ';
            }*/
            foreach($data as $columns=>$value) {
                $query .= $columns . ', ';
            }
            $query = substr($query, 0, -2) . ') VALUES (';
            reset($data);

            /*while (list(, $value) = each($data)) {
                switch ((string) $value) {
                    case 'now()':
                        $query .= 'now(), ';
                        break;
                    case 'null':
                        $query .= 'null, ';
                        break;
                    default:
                        $query .= '\'' . $value . '\', ';
                        break;
                }
            }*/
            foreach($data as $columns=>$value) {
                switch ((string) $value) {
                    case 'now()':
                        $query .= 'now(), ';
                        break;
                    case 'null':
                        $query .= 'null, ';
                        break;
                    default:
                        $query .= '\'' . $value . '\', ';
                        break;
                }
            }
            $query = substr($query, 0, -2) . ')';
        } else {
            $query = $cquery;
        }

        //echo $query; die;
    } elseif ($action == 'update') {
        reset($data);
        if ($cquery == '') {
            $query = 'UPDATE ' . $table . ' SET ';
            /*while (list($columns, $value) = each($data)) {
                switch ((string) $value) {
                    case 'now()':
                        $query .= $columns . ' = now(), ';
                        break;
                    case 'null':
                        $query .= $columns .= ' = null, ';
                        break;
                    default:
                        $query .= $columns . ' = \'' . $value . '\', ';
                        break;
                }
            }*/
            foreach($data as $columns=>$value) {
                switch ((string) $value) {
                    case 'now()':
                        $query .= $columns . ' = now(), ';
                        break;
                    case 'null':
                        $query .= $columns .= ' = null, ';
                        break;
                    default:
                        $query .= $columns . ' = \'' . $value . '\', ';
                        break;
                }
            }
            $query = substr($query, 0, -2);
            if ($parameters != '') {
                $query .= ' WHERE ' . $parameters;
            }
        } else {
            $query = $cquery;
        }
    } elseif ($action == 'delete') {
        $query = 'delete from ' . $table;
        if ($parameters != '') {
            $query .= ' WHERE ' . $parameters;
        }
    } elseif ($action == 'get') {
        if ($cquery == '') {
            reset($data);

            $query = 'SELECT ';
            foreach($data as $d_key => $d_value)
            {
                $query .= $d_key . ', ';
            }
            /*
            while (list($columns, ) = each($data)) {
                $query .= $columns . ', ';
            }
            */
            $query = substr($query, 0, -2);
            $query .= ' FROM ' . $table;
            if ($parameters != '') {
                $query .= ' WHERE ' . $parameters;
            }

            if ($orderby != '') {
                $query .= ' ORDER BY ' . $orderby;
            }
        } else {
            $query = $cquery;
        }
        //echo $query;
    }
    // echo $query;
// exit(0);
    try {
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$result = $sth->fetch(PDO::FETCH_ASSOC);
//"res" => $sth->fetchAll()
        $dbh->beginTransaction();

        if ($action == 'insert') {
            $dbh->exec($query);
            $result = array("errormsg" => "", "id" => $dbh->lastInsertId(), "status" => "success");
        } else if ($action == 'update' || $action == 'delete') {
            $dbh->exec($query);
            $result = array("errormsg" => "", "id" => 0, "status" => "success");
        } else if ($action == 'get') {
            $sth = $dbh->prepare($query);
            $sth->execute();
            $result = array("errormsg" => "", "id" => 0, "status" => "success", "res" => $sth->fetchAll(PDO::FETCH_ASSOC), "count" => $sth->rowCount());
        } else {
            $result = array("errormsg" => "", "status" => "success");
        }
        $dbh->commit();
    } catch (Exception $e) {
        $dbh->rollBack();
        $result = array("errormsg" => $e->getMessage(), "status" => "failure");
    }
if (IS_LOCAL == TRUE)
	{
		add_log_txt($c_file. '--'.$query);
	}
    return $result;
}
function db_dispose_connection()
{
    	global $dbh;
		if (isset($dbh))
		{
			$dbh = NULL;
		}
}
?>
