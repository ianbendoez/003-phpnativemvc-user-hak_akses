<?php
require_once("../../../config/database.php");
require_once("model.php");
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
session_start(); 
if ( !isset($_SESSION['session_username']) or !isset($_SESSION['session_id']) or !isset($_SESSION['session_level']) or !isset($_SESSION['session_kode_akses']) or !isset($_SESSION['session_hak_akses']) )
{
  echo '<div class="callout callout-danger">
          <h4>Session Berakhir!!!</h4>
          <p>Silahkan logout dan login kembali. Terimakasih.</p>
        </div>';
} else {
$db = new db();
if(isset($_POST['controller'])) {
  $controller = $_POST['controller'];
} else {
  $controller = "";
}
$username = $_SESSION['session_username'];
$id_menus = 2; 
$cekMenusUser = $db->cekMenusUser($username,$id_menus); 
    foreach($cekMenusUser[1] as $data){
      $create = $data['c'];
      $read = $data['r'];
      $update = $data['u'];
      $delete = $data['d'];
      $nama_menus = $data['nama_menus'];
      $keterangan = $data['keterangan'];
    }
if($cekMenusUser[2] == 1) {

// start - controller
if($controller == 'get_hak_akses'){
  if (isset($_POST['kriteria'])) {
    $kriteria = $_POST['kriteria'];
    $getHakAkses = $db->getHakAkses($kriteria);
    $data = array();
    foreach($getHakAkses[1] as $option){
      $data[] = array("id"=>$option['item'], "text"=>$option['keterangan']);
    } 
    echo json_encode($data);
  }
} else if($controller == 'menu_hak_akses' && $update == "y"){
  $aksi = $_POST['aksi'];
  $id = $_POST['id'];
  $status = $_POST['status'];

  if($aksi == "c") {
    $run = $db->edit_c_menu($id, $status);
    $retval['status'] = $run[0];
    $retval['message'] = $run[1];
  } else if($aksi == "r") {
    $run = $db->edit_r_menu($id, $status);
    $retval['status'] = $run[0];
    $retval['message'] = $run[1];
  } else if($aksi == "u") {
    $run = $db->edit_u_menu($id, $status);
    $retval['status'] = $run[0];
    $retval['message'] = $run[1];
  } else if($aksi == "d") {
    $run = $db->edit_d_menu($id, $status);
    $retval['status'] = $run[0];
    $retval['message'] = $run[1];
  }
    echo json_encode($retval);  
} else if($controller == 'create' && $create == "y"){
  $hak_akses = $_POST['hak_akses'];
  $idMenus= $_POST['id_menus'];
  $id = $hak_akses.".".$idMenus;

  $run = $db->create($id, $hak_akses, $idMenus);
  $retval['status'] = $run[0];
  $retval['title'] = $run[1];
  $retval['message'] = $run[2];
  echo json_encode($retval);  
} else if($controller == 'delete' && $delete == "y"){
  $id = $_POST['id'];

  $run = $db->delete($id);
  $retval['status'] = $run[0];
  $retval['title'] = $run[1];
  $retval['message'] = $run[2];
  echo json_encode($retval);  
} else if($controller == 'update_menu' && $update == "y"){
  $hak_akses = $_POST['hak_akses'];

  $deleteMenu = $db->deleteMenu($hak_akses);
  $selectMenu = $db->selectMenu($hak_akses);
  foreach($selectMenu[1] as $data){
    $username = $data['username'];
    $hak_akses = $data['hak_akses'];
    $idMenus = $data['id_menus'];
    $id = $username.".".$idMenus;
    $c = $data['c'];
    $r = $data['r'];
    $u = $data['u'];
    $d = $data['d'];

    $run = $db->createMenu($id,$username,$idMenus,$c,$r,$u,$d);
  }

  $retval['status'] = $run[0];
  $retval['title'] = $run[1];
  $retval['message'] = $run[2];
  echo json_encode($retval);  
} else {
  $retval['status'] = false;
  $retval['message'] = "Tidak memiliki hak akses.";
  $retval['title'] = "Error!";
  echo json_encode($retval);
}
// end - controller

}}} else {
  header("HTTP/1.1 401 Unauthorized");
  exit;
} ?>