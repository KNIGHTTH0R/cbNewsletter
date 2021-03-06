<?php

  $Debugout->add("<pre><b>[ bootstrap.common ]</b>");

  date_default_timezone_set('Europe/Berlin');
  $Debugout->add(
    "date_default_timezone set to:",
    date_default_timezone_get()
  );




  include_once(checkout("/lib/classes/Router.class.php"));

  include_once(checkout("/lib/classes/Request.class.php"));

  DIC::add("view", Request::view());

  $Debugout->add("DIC::[\"view\"] set to", DIC::get("view"));



  // General functions
  include_once(checkout("/lib/classes/HTML.class.php"));
  $HTML = new HTML;

  include_once(checkout("/lib/functions.php"));


  // Other classes
  include_once(checkout("/lib/classes/Subscriber.class.php"));


  // gettext
  include_once(checkout("/lib/initGettext.php"));


  if (!isset($_GET["view"]) or $_GET["view"] != "config") {

    // Database related
    include_once(checkout("/lib/bootstrap.database.common.php"));


    $subdir = str_replace(DIC::get("basedir"), "", DIC::get("calldir"));

    include_once(checkout($subdir . "/lib/bootstrap.database.php"));

    include_once(checkout("/lib/bootstrap.maintenance.php"));

  } else {

    $Debugout->add("skipping database bootstrapping...");

  }




  $Debugout->add("</pre>");


?>
