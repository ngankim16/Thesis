  <!DOCTYPE html>
  <html lang="en">

  <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">


          <link rel="icon" type="image/x-icon" href="{{asset('./back-end/uploads/ctu.png')}}">

          <!-- Thêm CSS của Bootstrap Select -->
          <link rel="stylesheet"
                  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">



          <link rel="stylesheet"
                  href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

          <link rel="stylesheet"
                  href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

          <link rel="stylesheet" href="{{asset('./back-end/plugins/fontawesome-free/css/all.min.css')}}">

          <link rel="stylesheet" href="{{asset('./back-end/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">

          <link rel="stylesheet"
                  href="{{asset('./back-end/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
          <link rel="stylesheet"
                  href="{{asset('./back-end/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
          <link rel="stylesheet"
                  href="{{asset('./back-end/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
          <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
          <link rel="stylesheet" href="{{asset('./back-end/plugins/select2/css/select2.min.css')}}">
          <link rel="stylesheet"
                  href="{{asset('./back-end/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

          <link rel="stylesheet"
                  href="{{asset('./back-end/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">

          <link rel="stylesheet" href="{{asset('./back-end/plugins/toastr/toastr.min.css')}}">

          <link rel="stylesheet" href="{{asset('./back-end/plugins/dropzone/min/dropzone.min.css')}}">

          <link rel="stylesheet" href="{{asset('./back-end/plugins/boostrap-min/css/jquery.datetimepicker.min.css')}}">

          <link rel="stylesheet" href="{{asset('./back-end/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

          <link rel="stylesheet" href="{{asset('./back-end/plugins/bootstrap4-toggle/css/bootstrap4-toggle.min.css')}}">

          <link rel="stylesheet" href="{{asset('./back-end/plugins/boostrap-min/css/main.min.css')}}">
          <link rel="stylesheet" href="{{asset('./back-end/plugins/boostrap-min/css/styles.css')}}">
          <script src="{{asset('./back-end/plugins/jquery/jquery.min.js')}}"></script>
          <link rel="stylesheet" href="{{asset('./back-end/plugins/boostrap-min/css/calander.css')}}">
          <script src="{{asset('./back-end/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

          <link rel="stylesheet" href="{{asset('./back-end/plugins/summernote/summernote-bs4.min.css')}}">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">

  </head>

  <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
          <div class="wrapper">
                  <div id="page"></div>
                  <div id="loading"></div>

                  <nav class="main-header navbar navbar-expand ">

                          <ul class="navbar-nav">

                                  <li class="nav-item">
                                          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                                                          class="fas fa-bars"></i></a>
                                  </li>


                          </ul>

                          <ul class="navbar-nav ml-auto">

                                  <li class="nav-item dropdown">
                                          <a class="nav-link dropdown-toggle" href="#" id="accountDropdown"
                                                  role="button" data-toggle="dropdown" aria-haspopup="true"
                                                  aria-expanded="false">
                                                  <div class="d-felx badge-pill">

                                                          <p class="fa fa-user mr-2"></p>


                                                          <!-- Hiển thị tên người dùng -->
                                                          <span class="mr-2"> <?php
			                                         $HOTEN_GV = Session::get('HOTEN_GV');
			                                         if($HOTEN_GV){
				                                        echo $HOTEN_GV; }
                                                                ?></span>
                                                          <!-- Hiển thị mã giảng viên -->
                                                          <span class="mr-2 text-muted">(<?php
    $MA_GV = Session::get('MA_GV');
    if ($MA_GV) {
        echo $MA_GV;
    }
?>)</span>
                                                          <i class="fa fa-angle-down"></i>
                                                  </div>
                                          </a>
                                          <!-- Điều chỉnh lại vị trí của dropdown menu -->
                                          <div class="dropdown-menu dropdown-menu-right"
                                                  aria-labelledby="accountDropdown">
                                                  <!-- <a class="dropdown-item">
                                                          <i class="fa fa-cog mr-2"></i> Manage Account
                                                  </a> -->
                                                  <a class="dropdown-item" href="{{ url('/logout') }}">
                                                          <i class="fa fa-power-off mr-2"></i> Logout
                                                  </a>
                                          </div>
                                  </li>
                                  <style>
                                  #accountDropdown::after {
                                          display: none;
                                  }
                                  </style>


                          </ul>
                  </nav>
                  <style>
                  .sidebar-collapse .nav-sidebar {
                          display: none;
                          /* Ẩn các mục trong sidebar khi ở trạng thái thu gọn */
                  }

                  .sidebar-collapse .nav-item {
                          text-align: center;
                          /* Căn giữa các mục trong sidebar khi thu gọn */
                  }

                  .sidebar-collapse .nav-link {
                          padding: 10px;
                          /* Điều chỉnh padding cho các mục trong sidebar */
                  }
                  </style>
                  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                  <script>
                  $('#manage_account').click(function() {
                          uni_modal('Manage Account', 'manage_user.php?id=')
                  })
                  </script>

                  <aside class="main-sidebar  ">
                          <div class="dropdown">
                                  <a href="./" class="brand-link">
                                          <div class="logo-container">
                                                  <img src="{{ asset('back-end/uploads/logo1.png') }}" class="logo">
                                          </div>
                                  </a>

                                  <style>
                                  .logo-container {
                                          width: 190px;
                                          /* Chiều rộng khung chứa logo */
                                          height: 130px;
                                          /* Chiều cao khung chứa logo */
                                          display: flex;
                                          justify-content: center;
                                          /* Căn giữa logo theo chiều ngang */
                                          align-items: center;
                                          /* Căn giữa logo theo chiều dọc */
                                          overflow: hidden;
                                          /* Giới hạn logo không tràn ra khỏi khung */

                                  }

                                  .logo {
                                          max-width: 100%;
                                          /* Giới hạn chiều rộng tối đa logo */
                                          max-height: 100%;
                                          /* Giới hạn chiều cao tối đa logo */
                                          object-fit: contain;
                                          /* Đảm bảo logo giữ nguyên tỷ lệ và không bị méo */

                                  }
                                  </style>
                          </div>

                          <div class="sidebar pb-4 mb-4">
                                  <nav class="mt-4">
                                          <ul class="nav nav-pills nav-sidebar flex-column nav-flat"
                                                  data-widget="treeview" role="menu" data-accordion="false">
                                                  <li class="nav-item">
                                                          <a href="{{URL::to('/dashboard')}}" class="nav-link nav-home">
                                                                  <i class="nav-icon fas fa-tachometer-alt"></i>
                                                                  <p>Bảng điều khiển</p>
                                                          </a>
                                                  </li>

                                                  <li class="nav-item">
                                                          <a href="{{route('giangvien.duyet_hoidong')}}"
                                                                  class="nav-link nav-home">
                                                                  <i class="nav-icon fas fa-clipboard-check"></i>
                                                                  <p>Duyệt hội đồng</p>
                                                          </a>
                                                  </li>


                                                  <li class="nav-item dropdown">
                                                          <a href="#" class="nav-link nav-edit_branch"
                                                                  id="studentDropdown" data-toggle="dropdown">
                                                                  <i class="nav-icon fas fa-user-graduate"></i>
                                                                  <p>
                                                                          Sinh viên

                                                                  </p>
                                                          </a>
                                                          <ul class="nav nav-treeview">
                                                                  <li class="nav-item">
                                                                          <a href="{{route('student.add_st')}}"
                                                                                  class="nav-link nav-new_branch tree-item">
                                                                                  <i
                                                                                          class="fas fa-angle-right nav-icon"></i>
                                                                                  <p>Thêm sinh viên</p>
                                                                          </a>
                                                                  </li>
                                                                  <li class="nav-item">
                                                                          <a href="{{route('student.up')}}"
                                                                                  class="nav-link nav-new_staff tree-item">
                                                                                  <i
                                                                                          class="fas fa-angle-right nav-icon"></i>
                                                                                  <p>Import Sinh viên</p>
                                                                          </a>
                                                                  </li>
                                                                  <li class="nav-item">
                                                                          <a href="{{route('student.list_st')}}"
                                                                                  class="nav-link nav-branch_list tree-item">
                                                                                  <i
                                                                                          class="fas fa-angle-right nav-icon"></i>
                                                                                  <p>Danh sách sinh viên</p>
                                                                          </a>
                                                                  </li>
                                                          </ul>
                                                  </li>

                                                  <li class="nav-item dropdown">
                                                          <a href="#" class="nav-link nav-edit_staff" id="staffDropdown"
                                                                  data-toggle="dropdown">
                                                                  <i class="nav-icon fas fa-users"></i>
                                                                  <p>
                                                                          Giảng viên

                                                                  </p>
                                                          </a>
                                                          <ul class="nav nav-treeview">
                                                                  <li class="nav-item">
                                                                          <a href="{{route('giangvien.add_gv')}}"
                                                                                  class="nav-link nav-new_staff tree-item">
                                                                                  <i
                                                                                          class="fas fa-angle-right nav-icon"></i>
                                                                                  <p>Thêm giảng viên</p>
                                                                          </a>
                                                                  </li>
                                                                  <li class="nav-item">
                                                                          <a href="{{route('giangvien.list_gv')}}"
                                                                                  class="nav-link nav-staff_list tree-item">
                                                                                  <i
                                                                                          class="fas fa-angle-right nav-icon"></i>
                                                                                  <p>Danh sách giảng viên</p>
                                                                          </a>
                                                                  </li>
                                                          </ul>
                                                  </li>



                                                  <li class="nav-item dropdown">
                                                          <a href="#" class="nav-link nav-edit_staff"
                                                                  id="lichbaoveDropdown" data-toggle="dropdown">
                                                                  <i class="nav-icon fas fa-calendar-alt"></i>
                                                                  <p>
                                                                          Hội đồng luận văn

                                                                  </p>
                                                          </a>
                                                          <ul class="nav nav-treeview">
                                                                  <li class="nav-item">
                                                                          <a href="{{route('hoidong.index')}}"
                                                                                  class="nav-link nav-new_staff tree-item">
                                                                                  <i
                                                                                          class="fas fa-angle-right nav-icon"></i>
                                                                                  <p>Danh sách hội đồng</p>
                                                                          </a>
                                                                  </li>
                                                                  <li class="nav-item">
                                                                          <a href="{{route('hoidong.create_hoidong')}}"
                                                                                  class="nav-link nav-staff_list tree-item">
                                                                                  <i
                                                                                          class="fas fa-angle-right nav-icon"></i>
                                                                                  <p>Tạo</p>
                                                                          </a>
                                                                  </li>
                                                          </ul>
                                                  </li>


                                                  <li class="nav-item dropdown">
                                                          <a href="#" class="nav-link nav-edit_staff" id="detaiDropdown"
                                                                  data-toggle="dropdown">
                                                                  <i class="nav-icon fas fa-book"></i>
                                                                  <p>
                                                                          Đề Tài

                                                                  </p>
                                                          </a>
                                                          <ul class="nav nav-treeview">
                                                                  <li class="nav-item">
                                                                          <a href="{{route('detai.add_dt')}}"
                                                                                  class="nav-link nav-new_staff tree-item">
                                                                                  <i
                                                                                          class="fas fa-angle-right nav-icon"></i>
                                                                                  <p>Thêm đề tài</p>
                                                                          </a>
                                                                  </li>

                                                                  <li class="nav-item">
                                                                          <a href="{{route('detai.list_dt')}}"
                                                                                  class="nav-link nav-staff_list tree-item">
                                                                                  <i
                                                                                          class="fas fa-angle-right nav-icon"></i>
                                                                                  <p>Danh sách đề tài</p>
                                                                          </a>
                                                                  </li>
                                                          </ul>
                                                  </li>

                                                  <li class="nav-item dropdown">
                                                          <a href="#" class="nav-link nav-edit_staff"
                                                                  id="lichbaoveDropdown" data-toggle="dropdown">
                                                                  <i class="nav-icon fas fa-calendar-alt"></i>
                                                                  <p>
                                                                          Lịch Bảo Vệ

                                                                  </p>
                                                          </a>
                                                          <ul class="nav nav-treeview">
                                                                  <li class="nav-item">
                                                                          <a href="{{route('lichbaove.add_lbv')}}"
                                                                                  class="nav-link nav-new_staff tree-item">
                                                                                  <i
                                                                                          class="fas fa-angle-right nav-icon"></i>
                                                                                  <p>Thêm lịch bảo vệ</p>
                                                                          </a>
                                                                  </li>
                                                                  <li class="nav-item">
                                                                          <a href="{{route('lichbaove.list_lbv')}}"
                                                                                  class="nav-link nav-staff_list tree-item">
                                                                                  <i
                                                                                          class="fas fa-angle-right nav-icon"></i>
                                                                                  <p>Lịch bảo vệ</p>
                                                                          </a>
                                                                  </li>
                                                          </ul>
                                                  </li>

                                                  <li class="nav-item">
                                                          <a href="https://www.ctu.edu.vn/" class="nav-link"
                                                                  target="_blank">
                                                                  <img src="{{asset('back-end/uploads/ctu.png')}}"
                                                                          alt="CTU Logo" class="img-responsive">
                                                          </a>
                                                  </li>

                                          </ul>
                                  </nav>
                          </div>
                  </aside>

                  <div class="content-wrapper">
                          <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
                                  <div class="toast-body text-white">
                                  </div>
                          </div>
                          <div id="toastsContainerTopRight" class="toasts-top-right fixed"></div>

                          <div class="content-header">
                                  <div class="container-fluid">
                                          <div class="row mb-2">


                                          </div>

                                  </div>
                          </div>



                          <section class="content">
                                  <div class="container-fluid">
                                          @yield('admin_content')
                                  </div>
                          </section>


                          <div class="modal fade" id="confirm_modal" role='dialog'>
                                  <div class="modal-dialog modal-md" role="document">
                                          <div class="modal-content">
                                                  <div class="modal-header">
                                                          <h5 class="modal-title">Confirmation</h5>
                                                  </div>
                                                  <div class="modal-body">
                                                          <div id="delete_content"></div>
                                                  </div>
                                                  <div class="modal-footer">
                                                          <button type="button" class="btn btn-primary" id='confirm'
                                                                  onclick="">Continue</button>
                                                          <button type="button" class="btn btn-secondary"
                                                                  data-dismiss="modal">Close</button>
                                                  </div>
                                          </div>
                                  </div>
                          </div>
                          <div class="modal fade" id="uni_modal" role='dialog'>
                                  <div class="modal-dialog modal-md" role="document">
                                          <div class="modal-content">
                                                  <div class="modal-header">
                                                          <h5 class="modal-title"></h5>
                                                  </div>
                                                  <div class="modal-body">
                                                  </div>
                                                  <div class="modal-footer">
                                                          <button type="button" class="btn btn-primary" id='submit'
                                                                  onclick="$('#uni_modal form').submit()">Save</button>
                                                          <button type="button" class="btn btn-secondary"
                                                                  data-dismiss="modal">Cancel</button>
                                                  </div>
                                          </div>
                                  </div>
                          </div>
                          <div class="modal fade" id="uni_modal_right" role='dialog'>
                                  <div class="modal-dialog modal-full-height  modal-md" role="document">
                                          <div class="modal-content">
                                                  <div class="modal-header">
                                                          <h5 class="modal-title"></h5>
                                                          <button type="button" class="close" data-dismiss="modal"
                                                                  aria-label="Close">
                                                                  <span class="fa fa-arrow-right"></span>
                                                          </button>
                                                  </div>
                                                  <div class="modal-body">
                                                  </div>
                                          </div>
                                  </div>
                          </div>
                          <div class="modal fade" id="viewer_modal" role='dialog'>
                                  <div class="modal-dialog modal-md" role="document">
                                          <div class="modal-content">
                                                  <button type="button" class="btn-close" data-dismiss="modal"><span
                                                                  class="fa fa-times"></span></button>
                                                  <img src="" alt="">
                                          </div>
                                  </div>
                          </div>
                  </div>



                  <aside class="control-sidebar control-sidebar-dark">

                  </aside>



          </div>




          <script src="{{asset('./back-end/plugins/sweetalert2/sweetalert2.min.js')}}"></script>

          <script src="{{asset('./back-end/plugins/toastr/toastr.min.js')}}"></script>

          <script src="{{asset('./back-end/plugins/bootstrap4-toggle/js/bootstrap4-toggle.min.js')}}"></script>

          <script src="{{asset('./back-end/plugins/select2/js/select2.full.min.js')}}"></script>

          <script src="{{asset('./back-end/plugins/summernote/summernote-bs4.min.js')}}"></script>

          <script src="{{asset('./back-end/plugins/dropzone/min/dropzone.min.js')}}"></script>
          <script src="{{asset('./back-end/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>

          <script src="{{asset('./back-end/plugins/boostrap-min/js/jquery.datetimepicker.full.min.js')}}"></script>

          <script src="{{asset('./back-end/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
          <script>
          $(document).ready(function() {
                  $('.select2').select2({
                          placeholder: "Please select here",
                          width: "100%"
                  });
          })

          window.viewer_modal = function($src = '') {

                  var t = $src.split('.')
                  t = t[1]
                  if (t == 'mp4') {
                          var view = $("<video src='" + $src + "' controls autoplay></video>")
                  } else {
                          var view = $("<img src='" + $src + "' />")
                  }
                  $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
                  $('#viewer_modal .modal-content').append(view)
                  $('#viewer_modal').modal({
                          show: true,
                          backdrop: 'static',
                          keyboard: false,
                          focus: true
                  })


          }
          window.uni_modal = function($title = '', $url = '', $size = "") {
                  start_load()
                  $.ajax({
                          url: $url,
                          error: err => {
                                  console.log()
                                  alert("An error occured")
                          },
                          success: function(resp) {
                                  if (resp) {
                                          $('#uni_modal .modal-title').html($title)
                                          $('#uni_modal .modal-body').html(resp)
                                          if ($size != '') {
                                                  $('#uni_modal .modal-dialog')
                                                          .addClass($size)
                                          } else {
                                                  $('#uni_modal .modal-dialog')
                                                          .removeAttr("class").addClass(
                                                                  "modal-dialog modal-md"
                                                          )
                                          }
                                          $('#uni_modal').modal({
                                                  show: true,
                                                  backdrop: 'static',
                                                  keyboard: false,
                                                  focus: true
                                          })
                                          end_load()
                                  }
                          }
                  })
          }
          window._conf = function($msg = '', $func = '', $params = []) {
                  $('#confirm_modal #confirm').attr('onclick', $func + "(" + $params.join(',') + ")")
                  $('#confirm_modal .modal-body').html($msg)
                  $('#confirm_modal').modal('show')
          }
          window.alert_toast = function($msg = 'TEST', $bg = 'success', $pos = '') {
                  var Toast = Swal.mixin({
                          toast: true,
                          position: $pos || 'top-end',
                          showConfirmButton: false,
                          timer: 5000
                  });
                  Toast.fire({
                          icon: $bg,
                          title: $msg
                  })
          }
          $(function() {
                  bsCustomFileInput.init();

                  $('.summernote').summernote({
                          height: 300,
                          toolbar: [
                                  ['style', ['style']],
                                  ['font', ['bold', 'italic', 'underline',
                                          'strikethrough',
                                          'superscript',
                                          'subscript', 'clear'
                                  ]],
                                  ['fontname', ['fontname']],
                                  ['fontsize', ['fontsize']],
                                  ['color', ['color']],
                                  ['para', ['ol', 'ul', 'paragraph', 'height']],
                                  ['table', ['table']],
                                  ['view', ['undo', 'redo', 'fullscreen',
                                          'codeview', 'help'
                                  ]]
                          ]
                  })

                  $('.datetimepicker').datetimepicker({
                          format: 'Y/m/d H:i',
                  })


          })
          $(".switch-toggle").bootstrapToggle();
          $('.number').on('input keyup keypress', function() {
                  var val = $(this).val()
                  val = val.replace(/[^0-9]/, '');
                  val = val.replace(/,/g, '');
                  val = val > 0 ? parseFloat(val).toLocaleString("en-US") : 0;
                  $(this).val(val)
          })
          </script>

          <script>
          // preloader

          function show(id, value) {
                  document.getElementById(id).style.display = value ? 'block' : 'none';
          }

          show('page', true);
          show('loading', false);
          </script>


          <script src="{{asset('./back-end/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

          <script src="{{asset('./back-end/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>

          <script src="{{asset('./back-end/plugins/boostrap-min/js/ma.js')}}"></script>
          <!-- <script src="{{asset('./back-end/plugins/boostrap-min/js/main.js')}}"></script> -->
          <!-- <script src="{{asset('./back-end/plugins/boostrap-min/js/calendar.js')}}"></script> -->
          <script>
          const marqueeContainer = document.getElementById('marqueeContainer');

          marqueeContainer.addEventListener('mouseenter', () => {
                  marqueeContainer.style.animationPlayState = 'paused';
          });

          marqueeContainer.addEventListener('mouseleave', () => {
                  marqueeContainer.style.animationPlayState = 'running';
          });
          </script>
          <script src="{{asset('./back-end/plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
          <script src="{{asset('./back-end/plugins/raphael/raphael.min.js')}}"></script>
          <script src="{{asset('./back-end/plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
          <script src="{{asset('./back-end/plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>



          <script src="{{asset('./back-end/plugins/boostrap-min/js/demo.js')}}"></script>

          <script src="{{asset('./back-end/plugins/boostrap-min/js/pages/dashboard2.js')}}"></script>

          <script src="{{asset('./back-end/plugins/datatables/jquery.dataTables.min.js')}}"></script>
          <script src="{{asset('./back-end/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
          <script src="{{asset('./back-end/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
          <script src="{{asset('./back-end/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
          <script src="{{asset('./back-endpu/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
          <script src="{{asset('./back-end/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
          <script src="{{asset('./back-end/plugins/jszip/jszip.min.js')}}"></script>
          <script src="{{asset('./back-end/plugins/pdfmake/pdfmake.min.js')}}"></script>
          <script src="{{asset('./back-end/plugins/pdfmake/vfs_fonts.js')}}"></script>
          <script src="{{asset('./back-end/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
          <script src="{{asset('./back-end/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
          <script src="{{asset('./back-end/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


  </body>

  </html>