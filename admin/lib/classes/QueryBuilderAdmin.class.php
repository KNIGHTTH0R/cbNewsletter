<?php

class QueryBuilderAdmin extends QueryBuilder {

    private function callExecution($statement) {

    try {
      $result = $statement->execute();

    } catch(PDOException $e) {
      $error["Database"]["callPDOexecution"] = gettext("The PDO call returned an error: ") . $e->getMessage();
//       die($e->getMessage());
      echo $e->getMessage() . "<br>\n";
      return($error);
    }

    return $result;
  }


  public function get_subscribers($filter = "", $order = "email") {

    if ($filter == "") {

      $statement = $this->Database->prepare(
        "SELECT * FROM `cbNewsletter_subscribers`
         ORDER BY `$order` ASC;"
      );

    } else {

      $statement = $this->Database->prepare(
        "SELECT * FROM `cbNewsletter_subscribers`
        WHERE MATCH ( `name`, `email` )
        AGAINST ( :filter IN NATURAL LANGUAGE MODE )
        ORDER BY `$order` ASC ;"
      );

      $statement->bindParam(':filter', $filter);

    }

    $result = $this->callExecution($statement);

    return $statement->fetchAll(PDO::FETCH_CLASS, "Subscriber");

  }


  public function optimize_tables() {

    $statement = $this->Database->prepare("OPTIMIZE TABLE `cbNewsletter_subscribers`;");

    $result["optimize_cbNewsletter_subscribers"] = $this->callExecution($statement);



    $statement = $this->Database->prepare("OPTIMIZE TABLE `cbNewsletter_archiv`;");

    $result["optimize_cbNewsletter_archiv"] = $this->callExecution($statement);

    return $result;

  }


  public function check_for_tables() {

    $statement = $this->Database->prepare("SHOW TABLES LIKE 'cbNewsletter_%' ;");

    $result = $this->callExecution($statement);

    $result = $statement->fetchAll(PDO::FETCH_COLUMN, 0);



    if (count($result) < 2) {

      $tablenames = array("cbNewsletter_subscribers", "cbNewsletter_archiv");

      foreach($tablenames as $name) {

        if (strlen($name) < 1) continue;

        if (!in_array($name, $result)) {

          $list[] = $name;
          $this->{"init_$name"}();

        }

      }

      $HTML = new HTML;

      echo $HTML->infobox(gettext("Creating database tables:") . "\n" . $HTML->ul($list));

    }

  }

  private function init_cbNewsletter_subscribers() {

    $statement = $this->Database->prepare(
      "CREATE TABLE IF NOT EXISTS `cbNewsletter_subscribers` (
        `id` INT UNSIGNED NULL AUTO_INCREMENT ,
        `name` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        `email` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        `verified` BOOLEAN NOT NULL,
        `hash` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
        PRIMARY KEY (`id`)
      )
      ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

      ALTER TABLE `cbNewsletter_subscribers` ADD FULLTEXT KEY `index_subscribers` (`name`,`email`);"
    );

    $result = $this->callExecution($statement);

    return $result;

  }


  private function init_cbNewsletter_archiv () {

    $statement = $this->Database->prepare(
      "CREATE TABLE IF NOT EXISTS `cbNewsletter_archiv` (
        `id` INT UNSIGNED NULL AUTO_INCREMENT ,
        `date` INT UNSIGNED NOT NULL ,
        `subject` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        `text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        PRIMARY KEY (`id`)
      )
      ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

      ALTER TABLE `cbNewsletter_archiv` ADD FULLTEXT KEY `index_archiv` (`subject`,`text`);"
    );

    $result = $this->callExecution($statement);

    return $result;

  }

}

?>
