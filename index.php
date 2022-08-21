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
?>
<div class="modal fade modal-default" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myLargeModalLabel">Tambah Menu Akses</h4>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo $nama_menus;?></h3>
        <div class="box-tools pull-right">
          <?php if($create == "y") { ?>
          <button type="button" class="btn btn-box-tool text-red" id="btn-update">
            <i class="fa fa-fw fa-retweet"></i> Perbaharui Menu Akses
          </button>
          <?php } ?>
          <?php if($update == "y") { ?>
          <button type="button" class="btn btn-box-tool" id="btn-create">
            <i class="fa fa-plus"></i> Tambah
          </button>
          <?php } ?>
        </div>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <select class="form-control select2" id="hak_akses" name="hak_akses" style="width: 100%;">
                <option value="">-- Pilih --</option>
              </select>
              <small class="text-aqua"><?php echo $keterangan;?></small>
            </div>
            <!-- /.form-group -->
          </div>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <div id="pages"></div>

  </section>
  <!-- /.content -->
</div>
<script>
  // Data tabel
  function loadTable() {
    let value = {
      view : 'table',
      hak_akses : $('#hak_akses').val(),
    }
    $.ajax({
      url:"menus/<?php echo $id_menus;?>/view.php",
      type: "POST",
      data: value,
      success: function(data, textStatus, jqXHR)
      { 
        $('#pages').html(data);
        Swal.close()
      },
      error: function (request, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: textStatus,
          didOpen: () => {
            Swal.hideLoading()
          }
        });
      }
    }); 
  }

  function modalCreateMenu(hak_akses) {
    let value = {
      view : 'modal_tambah',
      hak_akses : hak_akses,
    }
    $.ajax({
      url:"menus/<?php echo $id_menus;?>/view.php",
      type: "POST",
      data: value,
      success: function(data, textStatus, jqXHR)
      { 
        Swal.close()
        $('.modal-body').html(data);
        $("#modal-tambah").modal("show");
      },
      error: function (request, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: textStatus,
          didOpen: () => {
            Swal.hideLoading()
          }
        });
      }
    }); 
  }

  function updateMenu(hak_akses) {
    let value = {
      controller : 'update_menu',
      hak_akses : hak_akses,
    }
    $.ajax({
      url:"menus/<?php echo $id_menus;?>/controller.php",
      type: "POST",
      data: value,
      success: function(data, textStatus, jqXHR)
      { 
        Swal.close()
        $resp = JSON.parse(data);
        if($resp['status'] == true){
          toastr.success($resp['message'], $resp['title'], {timeOut: 2000, progressBar: true});
        } else {
          toastr.error($resp['message'], $resp['title'], {closeButton: true});
        }
      },
      error: function (request, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: textStatus,
          didOpen: () => {
            Swal.hideLoading()
          }
        });
      }
    }); 
  }

  $('#hak_akses').select2({
    selectOnClose: true,
    ajax: {
      url: "menus/<?php echo $id_menus;?>/controller.php",
      type: "POST",
      dataType: "JSON",
      delay: 250,
      data: function (params) {
        return {
          controller: 'get_hak_akses',
          kriteria: params.term // search term
        };
      },
      processResults: function (response) {
        return {
          results: response
        };
      },
      cache: false
    }
  });
  // pencarian data
  $('#hak_akses').change(function(){
    Swal.fire({
      title: 'Loading...',
      html: 'Mohon menunggu sebentar...',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    });
    loadTable();
  });

  $('#btn-create').click(function() {
    if($('#hak_akses').val() == ''){
      $('#hak_akses').focus();
      Swal.fire("Validasi!", "Hak Akses wajib diisi.");
      return;
    }
    Swal.fire({
      title: 'Loading...',
      html: 'Mohon menunggu sebentar...',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    });
    modalCreateMenu($('#hak_akses').val());
  });

  $('#btn-update').click(function() {
    if($('#hak_akses').val() == ''){
      $('#hak_akses').focus();
      Swal.fire("Validasi!", "Hak Akses wajib diisi.");
      return;
    }
  
    Swal.fire({
      title: 'Perbaharui?',
      text: "Menu Akses semua user akan diperbaharui!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Perbaharui',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Loading...',
          html: 'Mohon menunggu sebentar...',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading()
            updateMenu($('#hak_akses').val());
          }
        });
      }
    })
  });
</script>

<?php 
}}} else {
  header("HTTP/1.1 401 Unauthorized");
  exit;
} ?>