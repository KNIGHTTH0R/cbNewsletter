<?php

  $cbNewsletter_startTime = microtime();

  $cbNewsletter["calldir"] = __DIR__;

  include_once(__DIR__ . "/../lib/classes/Debugout.class.php");
  $Debugout = new Debugout;

  include_once(__DIR__ . "/../lib/checkout.function.php");



  $Debugout->add("<pre><b>[ index ]</b>");

  // set basedir to /path/to/newsletter/ , not /path/to/newsletter/admin
  $Debugout->add("setting \$cbNewsletter[\"config\"][\"basedir\"] to ", realpath(dirname(__FILE__) . "/../"));
  $cbNewsletter["config"]["basedir"] = realpath(dirname(__FILE__) . "/../");


  //load config
  $cbNewsletter["config"]["general"] = include(realpath($cbNewsletter["config"]["basedir"] . "/admin/config/general.php"));
  if (count($cbNewsletter["config"]["general"]) > 0)
       $result = "OK";
  else $result = "FAILED";
  $Debugout->add("loading \$cbNewsletter[\"config\"][\"general\"] from /admin/config/general.php: ", $result);

  include_once(checkout("/lib/error-reporting.php"));





  $debug = $cbNewsletter["config"]["general"]["debug"];

  // override debug_level for php error messages [off|warn|full] (default: off)
  //   $cbNewsletter["config"]["general"]["debug_level"] = "full";

  // override debug messages by cbNewsletter
  //   $debug = true;

  $Debugout->add(
    "setting \$debug to ",
    ($debug) ? "true" : "false"
  );





  include_once(checkout("/admin/lib/bootstrap.php"));


  include_once(checkout("/admin/lib/routing.php"));


  $Debugout->add("</pre>");

  if ($debug) {

    echo $HTML->infobox("<h3>PHP debug output</h3>\n<p>$Debugout</p>\n" . $Debugout->output(), "debug");

  }

  if (isset($error)) {

    cbNewsletter_showErrors($error);

  }



  $cbNewsletter_endTime = microtime();

  if ($cbNewsletter["config"]["general"]["show_processing_time"]) {

    echo $HTML->infobox(
      sprintf(
        gettext("processing needed %s"),
        prettyTime($cbNewsletter_endTime - $cbNewsletter_startTime)
      ),
      "notes center"
    );
  }

?>

  </body>
</html>
