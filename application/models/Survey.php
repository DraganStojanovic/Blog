<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends CI_Model{

public $id;
public $name;
public $table = 'survey';

public function getSurveys()
{
  $this->db->select("survey.id, survey.name, SUM(survey_options.votes) as votesNumber");
  $this->db->from($this->table);
  $this->db->join('survey_options', $this->table . '.id = survey_options.survey_id');
  $this->db->group_by('survey.id, survey.name');
  return $this->db->get()->result();

}

public function getSurvey()
{
  $this->db->select("survey.id, survey.name, SUM(survey_options.votes) as votesNumber");
  $this->db->from($this->table);
  $this->db->join('survey_options', $this->table . '.id = survey_options.survey_id');
  $this->db->where($this->table . '.id', $this->id);
  $this->db->group_by('survey.id, survey.name');
  $this->db->order_by('survey.id','desc');
  return $this->db->get()->row();
}

public function getLast()
{
  $this->db->limit(1);
  $this->db->order_by('id','asc');
  return $this->db->get($this->table)->row();
}

public function update_name()
{
  $updateData = array(
    'name' => $this->name
  );

  $this->db->update($this->table, $updateData,array('id' => $this->id));
}

}
