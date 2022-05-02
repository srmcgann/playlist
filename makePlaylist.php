<?
  require('db.php');
  $data = json_decode(file_get_contents('php://input'));
  $title = mysqli_real_escape_string($link, $data->{'title'});
  $ret = [
    escapeshellarg($title)
  ];
  echo json_encode($ret);
?>
