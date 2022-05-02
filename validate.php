<?
  require('db.php');
  $data = json_decode(file_get_contents('php://input'));
  $title = mysqli_real_escape_string($link, $data->{'title'});
  if(!$title) die();
  $ar = glob($title, GLOB_ONLYDIR);
  echo json_encode([!!sizeof($ar)]);
?>
