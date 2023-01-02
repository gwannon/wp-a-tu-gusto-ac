<?php

/* ----------------- CLASS User -------------------- */
class UserAC {
  public $id;
  public $hash;
  public $nombre;
  public $apellidos;
  public $email;
  public $telefono;
  public $fields;
  public $tags;

  public function __construct($id, $createifnotexists = false) {
    $response = false;
    if (is_numeric($id)) {
      $temp = curlCallGet("/contacts?ids=".$id);
      if(isset($temp->contacts[0])) $response = $temp->contacts[0];
    } else if (filter_var($id, FILTER_VALIDATE_EMAIL)) {
      $temp = curlCallGet("/contacts?email=".$id);
      if(isset($temp->contacts[0])) $response = $temp->contacts[0];
    }

    if($response) {
      $this->id = $response->id;
      $this->hash = $response->hash;
      $this->nombre = $response->firstName;
      $this->apellidos = $response->lastName;
      $this->email = $response->email;
      $this->telefono = $response->phone;
      $this->fields = $this->getApiFields(); //Campos personalizados
      $this->tags = $this->getApiTags(); //Etiquetas
    } else if ($createifnotexists && filter_var($id, FILTER_VALIDATE_EMAIL)) { //Si no existe y tenemos el email lo creamos
      $data['contact'] = [
        'email' => $id, 
      ];
      $response = curlCallPost("/contacts", json_encode($data))->contact;
      $this->id = $response->id;
      $this->email = $response->email;
    }
  }
	
  //SETs --------------------------------	
  function setNombre($val) { $this->nombre = $val; }
  function setApellidos($val) { $this->apellidos = $val; }
  function setEmail($val) { $this->email = $val; }
  function setTelefono($val) { $this->telefono = $val; }
  function setField($field_id, $value) { 
    $this->fields[$field_id] = $value;
  } 

  function setTag($tag_id) { 
    $data['contactTag'] = [
      "contact" => $this->id,
      "tag"     => $tag_id
    ];
    $response = curlCallPost("/contactTags", json_encode($data)); 
    $this->tags[$tag_id] = $response->contactTag->id;
  } 

  //EXECUTE
  function executeAutomation ($automation_id) {
    $data['contactAutomation'] = [
      "contact" => $this->id,
      "automation" => $automation_id
    ];
    $response = curlCallPost("/contactAutomations", json_encode($data)); 
    return ($response->contactAutomation->status == 1 ? true : false );
  }

  //HAS --------------------------------
  function hasTag($tag_id) {
    if(isset($this->tags[$tag_id]) && $this->tags[$tag_id] > 0) return true;
    else return false;
  }

  //DELETE --------------------------------
  function deleteTag($tag_id) { 
    $response = curlCallDelete("/contactTags/".$this->tags[$tag_id]);
    $this->tags[$tag_id] = "";
  } 

	//UPDATEs --------------------------------
	function updateProfileAC() {
    $userFields = getFields('fields');
    foreach ($userFields as $field) {
      $myfields[] = [
        "field" => $field['id'],
        "value" => $this->fields[$field['id']]
      ];
    }
    $data['contact'] = [
      'email'       => $this->email, 
			'firstName'   => $this->nombre,
    	'lastName'    => $this->apellidos,
      'phone'       => $this->telefono,
      'fieldValues' => $myfields
		];
    $response = curlCallPut("/contacts/".$this->id, json_encode($data));
		return $response;
	}

  //APIs calls --------------------------------
  function getApiTags() {
    $tags = array_merge(getFields('langs'), getFields("interests"), getFields("companies"), getFields("newsletters"), getFields("notifications"));
    $usertags = curlCallGet("/contacts/".$this->id."/contactTags")->contactTags;
    foreach ($tags as $tag) {
      $currenttags[$tag['id']] = false;
      foreach ($usertags as $usertag) {
        if ($tag['id'] == $usertag->tag) {
          $currenttags[$tag['id']] = $usertag->id;
          break;
        }
      }
    }
    return $currenttags;
  }

  function getApiFields() {
    $userFields = getFields('fields');
    $userfields = curlCallGet("/contacts/".$this->id."/fieldValues")->fieldValues;
    foreach ($userFields as $field) {
      $currentfields[$field['id']] = false;
      foreach ($userfields as $userfield) {
        if ($field['id'] == $userfield->field) {
          $currentfields[$field['id']] = $userfield->value;
          break;
        }
      }
    }
    return $currentfields;
  }
}

function existsUserAC ($email) {
  $temp = curlCallGet("/contacts?email=".$email);
  if(isset($temp->contacts[0])) return true;
	else return false;
}

