<?
  require('db.php');
  $data = json_decode(file_get_contents('php://input'));
  $playlist = mysqli_real_escape_string($link, $data->{'playlist'});
  if(!$playlist) die();
  $ret = false;
  if(strtoupper($playlist) !== '.BASE' &&
     strtoupper($playlist) !== 'ROADTRIP' &&
     strtoupper($playlist) !== 'JENIFER' &&
     strtoupper($playlist) !== 'WHR'
     ){
    $playlist = './' . $playlist;
    $playlist = escapeshellarg($playlist);
    $output = shell_exec("rm -rf $playlist");
    $ret = [true, $output];
  }
  echo json_encode($ret);
?>
