<?php

class Subscriber {

  protected $id;
  protected $name;
  protected $email;
  protected $hash;
  protected $verified;

  public function getdata() {

    $data = array(
      "id"       => $this->id,
      "name"     => $this->name,
      "email"    => $this->email,
      "hash"     => $this->hash,
    );

    switch (intval($this->verified)) {

      case 0: $data["verified"] = false; break;
      case 1: $data["verified"] = true;  break;

    }

    return $data;

  }

  public function correctTypes() {

    $this->id = intval($this->id);

    switch (intval($this->verified)) {

      case 0: $this->verified = false; break;
      case 1: $this->verified = true;  break;

    }

  }

  public function setId($id) {

    $this->id = $id;

  }


  public function setData($name, $email) {

    $this->id       = null;
    $this->name     = $name;
    $this->email    = $email;
    $this->hash     = $this->generate_hash($email);
    $this->verified = false;

  }


  private function generate_hash($string) {

    return hash('sha256', time() . $string);

  }

}

?>