<?php

/**
 *
 */
class QueryBuilder
{
  private $selectClause = "";
  private $fromClause = "";
  private $whereClause = "";
  private $groupClause = "";
  private $orderClause = "";
  private $modifiers = [];

  function __construct($array)
  {
    $this->selectClause = $array['query']['selectClause'];
    $this->fromClause = $array['query']['fromClause'];
    $this->whereClause = $array['query']['whereClause'];
    $this->groupClause = $array['query']['groupClause'];
    $this->orderClause = $array['query']['orderClause'];

    foreach ($array['with'] as $modifier => $data) {
      $this->modifiers[$modifier] = $data;
    }

  }
  public function addSelect($select)
  {
    if ($select != ""){
      if ($this->selectClause != ""){
        $this->selectClause .= ", $select";
      } else {
        $this->selectClause = "select $select";
      }
    }
  }

  public function addFrom($from)
  {
    if ($from != ""){
      if ($this->fromClause != ""){
        $this->fromClause .= " $from";
      } else {
        $this->fromClause = "from $from";
      }
    }
  }

  public function addWhereM($where)
  {
    if ($where != "") {
      if ($this->whereClause != ""){
        $this->whereClause .= " $where";
      } else {
        $this->whereClause = "where $where";
      }
    }
  }

  public function addWhere($key, $value, $operator = "=")
  {
    if ($key != "")
      if ($this->whereClause != ""){
        $this->whereClause .= " and $key $operator $value";
      } else {
        $this->whereClause = "Where $key $operator $value";
      }
  }

  public function addGroup($group)
  {
    if ($group != "") {
      if ($this->groupClause != ""){
        $this->groupClause .= ", $group";
      } else {
        $this->groupClause = "group by $group";
      }
    }
  }

  public function addOrder($order)
  {
    if ($order != "") {
      if ($this->orderClause != ""){
        $this->orderClause .= ", $order";
      } else {
        $this->orderClause = "order by $order";
      }
    }
  }
  public function getM()
  {
    return $this->modifiers;
  }
  public function addModifiers($array)
  {
    foreach ($array as $key => $modifier) {
      foreach ($this->modifiers as $tModifier => $data) {
        if ($tModifier == $modifier){
          $this->addSelect($this->modifiers[$tModifier]['selectClause']);
          $this->addFrom($this->modifiers[$tModifier]['fromClause']);
          $this->addWhereM($this->modifiers[$tModifier]['whereClause']);
          $this->addGroup($this->modifiers[$tModifier]['groupClause']);
          $this->addOrder($this->modifiers[$tModifier]['orderClause']);
        }
      }
    }
  }

  public function toString(){
    return $this->selectClause . " " . $this->fromClause . " " . $this->whereClause . " " .
    $this->groupClause . " " . $this->orderClause;
  }
}
