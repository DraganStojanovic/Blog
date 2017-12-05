<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey_options extends CI_Model{

public $id;
public $name;
public $votes;
public $survey_id;
public $table = 'survey_options';


public function getSurveyOptions()
{
  $this->db->select("*");
  $this->db->from($this->table);
  $this->db->where('survey_id',$this->survey_id);
  return $this->db->get()->result();
}


public function vote()
{
  $upit = "UPDATE survey_options SET votes=votes +1 WHERE id = $this->id";
  return $this->db->query($upit);
}

//Funkcija za batch upis u bazu, prima niz imena novih opcija za zadatu anketu
public function insertOptions($insertArray)
{
  //Pravljenje i popunjavanje asocijativnog niza za insert
  $insertData = array();
  foreach($insertArray as $i)
  {
    array_push($insertData, array('name' => $i, 'survey_id' => $this->survey_id, 'color' => 'danger'));
  }
  return $this->db->insert_batch($this->table, $insertData);
}

public function updateOptions($updateArray)
{
  $updateData = array();
  foreach($updateArray as $u)
  {
    array_push($updateData, array('id' => $u[0], 'name' => $u[1]));
  }
  return $this->db->update_batch($this->table, $updateData, 'id');
}

public function delete()
{
  $this->db->where('id', $this->id);
  return $this->db->delete($this->table);
}

public function insertOption()
{
  $insertData = array(
    'name' => $this->name,
    'survey_id' => $this->survey_id
  );

  return $this->db->insert($this->table, $insertData);
}

public function getName()
{
  $this->db->select('name');
  $this->db->from($this->table);
  $this->db->where('id', $this->id);
  return $this->db->get()->row()->name;
}

public function updateOption()
{
  $updateData = array(
    'name' => $this->name
  );

  $this->db->where('id', $this->id);
  $this->db->update($this->table, $updateData);
}

}
