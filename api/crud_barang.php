<?php
include_once "_class/Koneksi.php";
//die(json_encode($_POST));
$code   = isset($_POST['code']) ? $_POST['code'] : '';
$name   = isset($_POST['name']) ? $_POST['name'] : '';
$price  = isset($_POST['price']) ? $_POST['price'] : '';
$action  = isset($_POST['action']) ? $_POST['action'] : '';

$query  = "INSERT INTO m_barang (`name`,`price`) VALUES ('$name', '$price')";
$result = [ 'isSuccess' => 0, 'isMessage' => '' ];
// cek apakah code not empty, jika iya maka saya anggap update
if(!empty($code)) {
   if($action == 'delete'){
      $query = "DELETE FROM m_barang WHERE `code`='$code'";
   }else{
      $query = "UPDATE m_barang SET `name`='$name', `price`='$price' WHERE `code`='$code'";
   }
}

$koneksi_db = Koneksi::connect();
$execQuery  = mysqli_query($koneksi_db, $query);

if($execQuery){
   $result = [ 
      'isSuccess' => 1, 
      'isMessage' => 'Success '.(!empty($code) ? 'Update' : 'Save').' barang' 
  ];
  if($action == 'delete') $result['isMessage'] = "Success delete barang";
}else{
   $result['isMessage'] = "Error MySQL: ".mysqli_error($koneksi_db);
}

Koneksi::close();
echo json_encode($result);
