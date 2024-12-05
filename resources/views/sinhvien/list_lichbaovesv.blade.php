@extends('admin-layout-sv')
@section('admin_content')
<div class="container mt-5">
        <div class="card">
                <div class="card-header bg-primary text-white text-center"
                        style="padding: 1rem; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                        <h4 class="mb-0 font-weight-bold">Danh Sách Lịch Bảo Vệ</h4>
                </div>
                <div class="card-body">
                        @if (Session::has('thongbao'))
                        <div class="alert alert-success" id="thongbao">
                                {{ Session::get('thongbao') }}
                        </div>
                        @endif
                        <style>
                        #thongbao {
                                font-size: 15px;
                                /* Giảm kích thước chữ */
                                padding: 8px 16px;
                                /* Thu nhỏ khoảng cách bên trong */
                                width: 300px;
                                /* Giới hạn chiều rộng của thanh thông báo */
                                margin: 10px auto;
                                /* Căn giữa và cách đều các thành phần khác */
                                text-align: center;
                                /* Căn giữa nội dung */
                        }
                        </style>

                        <script>
                        // Sau 3 giây (3000 milliseconds), thông báo sẽ tự động biến mất
                        setTimeout(function() {
                                $('#thongbao').fadeOut('slow');
                        }, 3000);
                        </script>
                        <div class="table-responsive">
                                <table class="table table-hover text-center" id="list">
                                        <thead>
                                                <tr>
                                                        <th>Mã Buổi Bảo Vệ</th>
                                                        <th>Phòng Học</th>
                                                        <th>Giờ Bắt Đầu</th>
                                                        <th>Ngày Bảo Vệ</th>
                                                        <th>Thời Lượng</th>
                                                        <th>Số Lượng</th>
                                                        <th>Chi Tiết Sinh Viên</th>

                                                </tr>
                                        </thead>
                                        <tbody>
                                                @foreach($buoi_bao_ve as $bbv)
                                                <tr>
                                                        <td class="align-middle">{{ $bbv->MA_BV }}</td>
                                                        <td class="align-middle">{{ $bbv->MA_PH }}</td>
                                                        <td class="align-middle">{{ $bbv->GIO_BATDAU_BV }}</td>
                                                        <td class="align-middle">{{ $bbv->NGAY_BV }}</td>
                                                        <td class="align-middle">{{ $bbv->THOILUONG_BV }} phút</td>
                                                        <td class="align-middle">{{ $bbv->SO_LUONG_BV }}</td>

                                                        <td class="align-middle">
                                                                <button class="btn btn-info btn-sm view-students"
                                                                        data-id="{{ $bbv->MA_BV }}">
                                                                        Xem
                                                                </button>
                                                        </td>

                                                </tr>
                                                <tr class="student-details" id="student-details-{{ $bbv->MA_BV }}"
                                                        style="display: none;">
                                                        <td colspan="7">
                                                                <ul class="list-group list-group-flush"
                                                                        id="student-list-{{ $bbv->MA_BV }}">
                                                                        <!-- Danh sách sinh viên sẽ được tải qua Ajax -->
                                                                </ul>
                                                        </td>
                                                </tr>
                                                @endforeach
                                        </tbody>
                                </table>

                        </div>
                </div>
        </div>
</div>
<!-- <script>
$.noConflict();
jQuery(document).ready(function($) {
        $('#list').DataTable();
});
</script> -->

<script>
var jq = jQuery.noConflict();

jq(document).ready(function() {
        jq('.view-students').on('click', function() {
                const ma_bv = jq(this).data('id');
                const $detailsRow = jq('#student-details-' + ma_bv);
                const $studentList = jq('#student-list-' + ma_bv);

                // Toggle visibility of the details row  
                if ($detailsRow.is(':visible')) {
                        $detailsRow.hide();
                        return;
                }

                // Make the AJAX request to fetch student details  
                jq.ajax({
                        url: `/lichbaove/detail_bv/${ma_bv}`,
                        method: 'GET',
                        success: function(response) {
                                $studentList.empty();

                                // Check if there are students to display  
                                if (response.sinhVien.length ===
                                        0) {
                                        $studentList.append(
                                                '<li class="list-group-item">Không có sinh viên tham gia.</li>'
                                        );
                                } else {
                                        response.sinhVien
                                                .forEach(
                                                        function(
                                                                sv
                                                        ) {
                                                                $studentList
                                                                        .append(`  
                            <li class="list-group-item">  
                                <strong>Mã SV:</strong> ${sv.MA_SV} -   
                                <strong>Họ Tên:</strong> ${sv.HOTEN_SV} -   
                                <strong>Đề Tài:</strong> ${sv.TEN_DT} -   
                                <strong>Chủ Tịch Hội Đồng:</strong> ${sv.TEN_CHU_TICH} -   
                                <strong>Ủy Viên:</strong> ${sv.TEN_PHO_CHU_TICH} -   
                                <strong>Thư Ký:</strong> ${sv.TEN_THU_KY}  
                            </li>  
                        `);
                                                        });
                                }

                                // Show the details row after loading data  
                                $detailsRow.show();
                        },
                        error: function(xhr, status, error) {
                                console.error('Lỗi khi tải danh sách sinh viên:',
                                        error);
                                alert(
                                        'Không thể tải danh sách sinh viên. Vui lòng thử lại sau.'
                                );
                        }
                });
        });
});
</script>


@endsection