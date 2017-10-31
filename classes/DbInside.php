<?php
namespace Application\Classes;

use Application\Classes\EmptyObject;
use Application\Classes\MagicObject;
use Application\Classes\Logger;

Class DBinside
{
  protected $action, $alias, $data, $from, $insert, $join, $limit, $offset, $groupby, $having, $orderby, $result, $set, $values, $what, $where, $union = '';
  protected $_doit = false, $_magic = false, $_mysqli;

  public function __construct($mysqli) {
    $this->_mysqli = $mysqli;
  }

  protected function _error($error) {
    die("<div style=\"font-family:Trebuchet MS;font-size:12px;margin:20% auto 0 auto;text-align:center;\">Ошибка! {$error}.</div>");
  }

  protected function _generateQuery($with_union = false) {
                          $query  = "{$this->action} {$this->what}";
    if ($this->from   )   $query .= " FROM {$this->from}".($this->alias ? " {$this->alias}" : '');
    if ($this->join   )   $query .=   $this->join;
    if ($this->set    )   $query .= " SET {$this->set}";
    if ($this->insert )   $query .= " ({$this->insert})";
    if ($this->values )   $query .= " VALUES {$this->values}";
    if ($this->where  )   $query .= " WHERE {$this->where}";
    if ($this->groupby)   $query .= " GROUP BY {$this->groupby}";
    if ($this->having )   $query .= " HAVING {$this->having}";
    if ($this->orderby)   $query .= " ORDER BY {$this->orderby}";
    if ($this->limit  )   $query .= " LIMIT {$this->limit}";
    if ($this->offset )   $query .= " OFFSET {$this->offset}";

    if ($this->action == 'SELECT') {
      $this->alias  = $this->what   = $this->join    = $this->set    = $this->insert  =
      $this->values = $this->where  = $this->groupby = $this->having = $this->orderby =
      $this->limit  = $this->offset = '';

      if ($with_union) {
        $query = $this->union.'('.$query.')';
        $this->union = '';
      }
    }

    return $query;
  }

  protected function _getDataForLog() {
    if ($this->action == 'INSERT INTO') {
      return $this->data;
    }

    $table = ($this->action == 'DELETE' ? $this->from : $this->what);

    $query = "SELECT * FROM {$table} WHERE {$this->where}";

    $this->result = $this->_mysqli->query($query);
    if ($this->_mysqli->errno) {
      throw new \Exception("QUERY: {$query}, ERROR: ".$this->_mysqli->error);
    }
    $this->_doit = true;

    $changes = [];
    $indexes = [];
    foreach ($this->result() as $row) {
      if ($this->action == 'DELETE') {
        $changes[$row->id] = $row;
        continue;
      }

      $row_new = [];
      foreach ($this->data as $key => $value) {
        $value = preg_replace("/^\'(.*)\'$/", "$1", $value);
        if ($value == $row->{$key}) {
          continue;
        }

        $row_new[$key] = $row->{$key}.' => '.$value;
        $indexes[]     = $key;
      }

      if (!$row_new) {
        continue;
      }

      $changes[$row->id] = $row_new;
    }

    if (!$changes || $this->action == 'DELETE') {
      return $changes;
    }

    foreach ($this->data as $key => $value) {
      if (!in_array($key, $indexes)) {
        unset($this->data[$key]);
      }
    }

    return $changes;
  }

  protected function _getResult($type, $index, $className = 'stdClass') {
    if (!$this->_doit) {
      $this->doit(!is_array($index) && !is_numeric($index) ? $index : false);
    }
    if (is_numeric($index)) {
      $index = [$index];
    }

    $one  = ($type == 'one'        );
    $type = ($one ? 'assoc' : $type);

    $result = ($one ? new EmptyObject("<document></document>") : []);

    if (!$this->result) {
      return $result;
    }

    $i = 0;
    $fetch = "fetch_{$type}";
    while ($row = ($type == 'object' ? $this->result->{$fetch}($className) : $this->result->{$fetch}())) {
      $i++;
      if (is_array($index) && !in_array($i, $index)) {
        continue;
      }

      if ($type == 'assoc' && $this->_magic) {
        $row = new MagicObject($this->_mysqli, $this->from, $row);
      }
      if ($one) {
        return (object)$row;
      }
      array_push($result, $row);
      //array_push($result, (($type == 'assoc') ? (object)$row : $row)); //немного подкорректируем метод, чтобы он воспринимал классы
    }

    return $result;
  }

  protected function _join($what, $how, $type) {
    $alias = $what;

    if (preg_match("/\s/", $what)) {
      $alias = preg_replace("/^.*\s/", '', $what);
    }

    $from = ($this->alias ? $this->alias : $this->from);

    if (is_array($how)) {
      foreach ($how as $key => $val) {
        if (is_numeric($key)) {
          continue;
        }
        $how[$key] = (!strpos($key, '.') ? "{$alias}." : '')."{$key} = ".(!strpos($val, '.') ? "{$from}." : '').$val;
      }
      $how = implode(" AND ", $how);
    }

    $this->join .= " {$type} JOIN {$what} ON {$how}";

    return $this;
  }

  public function get($id = true) {
    if (!$this->_magic) {
      $this->_magic = 'result';
    }

    if (is_array($id)) {
      return $this->where($id)->{$this->_magic}();
    }

    if ($id === true) {
      return $this->{$this->_magic}();
    }

    $row = $this->where('id', $id)->row();

    if (!(array)$row) {
      return null;
    }

    return $row;
  }

  public function one($id = true) {
    $this->_magic = 'row';
    return $this->get($id);
  }

  public function save($params) {
    $this->action = null;

    $query = $this->insert()->into($this->from)->values($params);
    $this->from = null;

    return $query->doit();
  }

  public function select($what = '*') {
    $this->action = 'SELECT';
    $this->what = $what;

    return $this;
  }

  public function from($from) {
    if (preg_match("/\s/", $from)) {
      $this->alias = preg_replace("/^.*\s/", '', $from);
      $this->from  = preg_replace("/\s.*$/", '', $from);
      return $this;
    }

    $this->from = $from;

    return $this;
  }

  public function innerjoin($what, $how) {
    return $this->_join($what, $how, 'INNER');
  }

  public function leftjoin($what, $how) {
    return $this->_join($what, $how, 'LEFT');
  }

  public function update($what) {
    if ($this->action) {
      throw new \Exception('DB::action is already set');
    }

    $this->action = 'UPDATE';

    if ($this->from && $this->where && is_array($what)) {
      $query = $this->update($this->from)->set($what);
      $this->from = null;

      return $query->doit();
    } 

    $this->what = $what;

    return $this;
  }

  public function set($values) {
    $this->data = [];
    foreach ($values as $key => $val) {
      if (strpos($key, "_wysiwyg")) {
        unset($values[$key]);
        $key = str_replace("_wysiwyg", "", $key);
        $val = preg_replace("/[\r\n]/", "", $val);
      }

      $val = ($val === null ? 'NULL' : "'".addslashes($val)."'");

      $values[$key]     = "{$key} = {$val}";
      $this->data[$key] =            $val;
    }
    $this->set = implode(", ", $values);

    return $this;
  }

  public function insert() {
    if ($this->action) {
      throw new \Exception('DB::action is already set');
    }

    $this->action = 'INSERT INTO';

    return $this;
  }

  public function into($what) {
    $this->what = $what;

    return $this;
  }

  public function values($data) {
    if (!array_key_exists(0, $data)) {
      $data = [$data];
    }

    $insert = array_keys($data[0]);
    $values = $this->data = [];
    foreach ($insert as $k => $key) {
      $wysiwyg = strpos($key, "_wysiwyg");
      if ($wysiwyg) {
        $insert[$k] = str_replace("_wysiwyg", "", $key);
      }
      foreach ($data as $i => $item) {
        $item = ($item instanceof MagicObject ? $item->__toArray() : (array)$item);

        if (!isset($values[$i])) {
          $values[$i] = $this->data[$i] = [];
        }

        $value = $item[$key];

        if ($wysiwyg) {
          $value = preg_replace("/[\r\n]/", "", $value);
        }

        $value        = ($value === null ? 'NULL' : "'".addslashes($value)."'");
        $values[$i][] = $this->data[$i][$insert[$k]] = $value;
      }
    }

    $values = array_map(function ($row) {
      return implode(", ", $row);
    }, $values);

    $this->insert =     implode(", ",   $insert);
    $this->values = "(".implode("), (", $values).")";

    return $this;
  }

  public function delete($params = []) {
    $this->action = 'DELETE';
    $this->what   = null;

    if ($this->from && $params) {
      if (is_numeric($params)) {
        $params = ['id' => $params];
      }

      return $this->where($params)->doit();
    }

    return $this;
  }

  public function where($where, $what = false, $or = false) {
    if (is_array($where)) {
      foreach ($where as $key => $val) {
        if (!is_numeric($key)) {
          if     ($val === null )  {  $val = 'IS NULL';                       }
          elseif (is_array($val))  {  $val = ' IN ('.implode(',', $val).')';  }
          else                     {  $val = "= '".addslashes($val)."'";      }

          $where[$key] = "{$key} {$val}";
        } elseif (is_array($val)) {
          foreach ($val as $k => $v) {
            $val[$k] = $this->where($v, false, true);
          }
          $where[$key] = '(('.implode(') OR (', $val).'))';
        }
      }
      $where = implode(" AND ", $where);

      if ($or) {
        return $where;
      }

    } elseif ($what !== false) {
      $get[$where] = $what;
      return $this->where($get);
    }

    if ($this->where != '') {
      $this->where .= " AND ";
    }

    $this->where .= $where;

    return $this;
  }

  public function union($param = false) {
    if ($param && $param != 'all') {
      $query = $this->_generateQuery(true);

      $this->what = '*';
      $this->from = '('.$query.') '.$param;

      return $this;
    }

    $this->union .= '('.$this->_generateQuery().') UNION '.($param ? ' ALL ' : '');

    return $this;
  }
  public function groupby($groupby) {
    $this->groupby = $groupby;

    return $this;
  }

  public function having($having) {
    $this->having = $having;

    return $this;
  }

  public function orderby($orderby, $order = '') {
    if (!is_array($orderby)) {
      $this->orderby = $orderby.($order != '' ? " {$order}" : '');

    } else {
      foreach ($orderby as $key => $val) {
        if (!is_numeric($key)) {
          $orderby[$key] = "{$key} {$val}";
        }
      }
      $this->orderby = implode(", ", $orderby);
    }

    return $this;
  }

  public function offset($offset) {
    $this->offset = $offset;

    return $this;
  }

  public function limit($limit) {
    $this->limit = $limit;

    return $this;
  }

  public function doit($debug = false) {
    $query = $this->_generateQuery(true);

    if ($debug) {
      var_dump($query);exit;
    }

    if ($this->action == 'SELECT') {
      $this->result = $this->_mysqli->query($query);

      if ($this->_mysqli->errno) {
        throw new \Exception("DB error: QUERY: {$query}, ERROR: ".$this->_mysqli->error);
      }

      $this->_doit = true;

      if ($this->result->num_rows == 0) {
        $this->result = null;
      }

    } else {
      if (class_exists('Logger')) {
        $log = [
          'data'    => $this->_getDataForLog(),
          'message' => ($this->action == 'DELETE' ? $this->from : $this->what).':'.$this->action
        ];
      }

      $this->_mysqli->query($query);

      if ($this->_mysqli->errno) {
        throw new \Exception("DB error: QUERY: {$query}, ERROR: ".$this->_mysqli->error);
      }

      if ($this->action == 'INSERT INTO') {
        if (!class_exists('Logger')) {
          return $this->_mysqli->insert_id ?: true;
        }

        if (!$this->_mysqli->insert_id) {
          foreach ($log['data'] as $row) {
            Logger::save($row, 0, $log['message']);
          }
          return true;
        }

        foreach ($log['data'] as $row) {
          Logger::save($row, $this->_mysqli->insert_id, $log['message']);
        }

        return $this->_mysqli->insert_id;

      } elseif (class_exists('Logger')) {
        foreach ($log['data'] as $id => $row) {
          Logger::save($row, $id, $log['message']);
        }
      }
    }

    return $this;
  }

  public function result($index = false) {
    return $this->_getResult('assoc', $index);
  }

  public function raw($index = false) {
    return $this->_getResult('row', $index);
  }

  public function row($index = false) {
    return $this->_getResult('one', $index);
  }

  public function begin() {
    $this->_mysqli->query("START TRANSACTION");
  }

  public function commit() {
    $this->_mysqli->query("COMMIT");
  }

  public function rollback() {
    $this->_mysqli->query("ROLLBACK");
  }

}
?>