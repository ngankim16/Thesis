@extends('admin-layout-sv')
@section('admin_content')
<div class="col-lg-12">
        <div class="card card-outline">
                <div class="card-header">
                        <div class="container">
                                <h2 class="text-center">Danh Sách Hội Đồng Đánh Giá</h2>
                                @if (Session::has('thongbao'))
                                <div class="alert alert-success" id="thongbao">
                                        {{ Session::get('thongbao') }}
                                </div>
                                @endif

                                <!-- Table Hiển Thị Danh Sách Hội Đồng -->
                                @if($hoiDongs->isEmpty())
                                <p>Không có hội đồng nào được tạo.</p>
                                @else
                                <table class="table table-striped table-hover table-center" id="list">
                                        <thead>
                                                <tr>
                                                        <th>Mã Hội Đồng</th>
                                                        <th>Chủ Tịch</th>
                                                        <th>Ủy Viên</th>
                                                        <th>Thư Ký</th>
                                                        <th>Ngày Tạo</th>
                                                        <th>Sinh Viên</th>
                                                        <th>Action</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                @foreach($hoiDongs as $hoiDong)
                                                <tr>
                                                        <td>{{ $hoiDong->MA_HD }}</td>
                                                        <td>{{ $hoiDong->CHU_TICH_TEN }}</td>
                                                        <td>{{ $hoiDong->PHO_CHU_TICH_TEN }}</td>
                                                        <td>{{ $hoiDong->THUKY_TEN }}</td>
                                                        <td>{{ $hoiDong->NGAY_TAO }}</td>
                                                        <td>
                                                                @php
                                                                $sinhViens = DB::table('duoc_danh_gia')
                                                                ->join('sinh_vien', 'duoc_danh_gia.MA_DT', '=',
                                                                'sinh_vien.MA_DT')
                                                                ->where('duoc_danh_gia.MA_HD', $hoiDong->MA_HD)
                                                                ->select('sinh_vien.HOTEN_SV')
                                                                ->get();
                                                                @endphp
                                                                @if($sinhViens->isNotEmpty())
                                                                @foreach($sinhViens as $sinhVien)
                                                                <div>{{ $sinhVien->HOTEN_SV }}</div>
                                                                @endforeach
                                                                @else
                                                                <div>Không có sinh viên nào.</div>
                                                                @endif
                                                        </td>
                                                        <td class="text-center">
                                                                <div class="btn-group">
                                                                        <button type="button" class="view view_parcel"
                                                                                data-id="{{ $hoiDong->MA_HD }}"
                                                                                data-toggle="modal"
                                                                                data-target="#modal-detail">
                                                                                <i class="fas fa-eye"></i>
                                                                        </button>

                                                                </div>
                                                        </td>
                                                </tr>
                                                @endforeach
                                        </tbody>
                                </table>
                                @endif



                        </div>
                </div>
        </div>
</div>

<!-- Modal Chi Tiết Hội Đồng -->
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title text-center" id="modalLabel">Chi Tiết Hội Đồng</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                                <div class="info-section mb-3">
                                        <h4>Thông Tin Hội Đồng</h4>
                                        <div class="row">
                                                <div class="col-md-6">
                                                        <p><strong>Mã Hội Đồng:</strong> <span id="maHd"></span></p>
                                                        <p><strong>Chủ Tịch:</strong> <span id="chuTich"></span></p>
                                                        <p><strong>Ủy Viên:</strong> <span id="phoChuTich"></span>
                                                        </p>
                                                        <p><strong>Thư Ký:</strong> <span id="thuKy"></span></p>
                                                </div>
                                                <div class="col-md-6">
                                                        <p><strong>Mã Phòng:</strong> <span id="maPhong"></span></p>
                                                        <p><strong>Ngày Bảo Vệ:</strong> <span id="ngayBaoVe"></span>
                                                        </p>
                                                        <p><strong>Thời Lượng: </strong> 40 phút</p>
                                                </div>
                                        </div>
                                </div>

                                <div class="student-section">
                                        <h5>Sinh Viên Bảo Vệ</h5>
                                        <ul id="sinhVienList" class="list-group list-group-flush"></ul>
                                </div>
                        </div>
                        <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        </div>
                </div>
        </div>
</div>

<script>
$(document).ready(function() {
        $('.view_parcel').on('click', function() {
                var maHd = $(this).data('id'); // Lấy mã hội đồng từ data-id

                $.ajax({
                        url: '/hoidong/details/' +
                                maHd, // Đảm bảo đường dẫn là đúng
                        method: 'GET',
                        success: function(response) {
                                if (response.error) {
                                        alert(response.error);
                                        return;
                                }

                                // Điền dữ liệu vào modal
                                $('#modal-detail #maHd').text(
                                        response.hoiDong
                                        .MA_HD);
                                $('#modal-detail #chuTich')
                                        .text(response.hoiDong
                                                .CHU_TICH_TEN
                                        ); // Cập nhật tên trường
                                $('#modal-detail #phoChuTich')
                                        .text(response.hoiDong
                                                .PHO_CHU_TICH_TEN
                                        ); // Cập nhật tên trường
                                $('#modal-detail #thuKy').text(
                                        response.hoiDong
                                        .THUKY_TEN
                                ); // Cập nhật tên trường
                                $('#modal-detail #maPhong')
                                        .text(response.hoiDong
                                                .MA_PH);
                                $('#modal-detail #ngayBaoVe')
                                        .text(response.hoiDong
                                                .NGAY_BV);
                                // Xóa nội dung cũ của danh sách sinh viên
                                $('#sinhVienList').empty();

                                // Lặp qua danh sách sinh viên và thêm vào modal
                                response.sinhVien.forEach(
                                        function(
                                                sinhVien
                                        ) {
                                                $('#sinhVienList')
                                                        .append(`
                        <li class="list-group-item">
                            <strong>Mã SV:</strong> ${sinhVien.MA_SV} <br>
                            <strong>Tên:</strong> ${sinhVien.HOTEN_SV} <br>
                            <strong>Đề tài:</strong> ${sinhVien.TEN_DT} <br>
                            <strong>GVHD:</strong> ${sinhVien.HOTEN_GV} <br>
                            <strong>Giờ bắt đầu:</strong> ${sinhVien.GIO_BAT_DAU} <br>
                            <strong>Giờ kết thúc:</strong> ${sinhVien.GIO_KET_THUC}
                        </li>
                    `);
                                        });

                                // Hiển thị modal
                                $('#modal-detail').modal(
                                        'show');
                        },
                        error: function(xhr, status, error) {
                                console.error('Lỗi khi tải dữ liệu:',
                                        error);
                                alert(
                                        'Lỗi khi tải dữ liệu. Vui lòng thử lại sau.'
                                );
                        }
                });
        });
});
</script>

<style>
<style>.modal-header {
        background-color: #487eb0;
        /* Màu nền cho tiêu đề modal */
        color: white;
        /* Màu chữ cho tiêu đề */

}

.modal-title {
        font-weight: bold;
}

.info-section h4,
.student-section h5 {
        color: #192a56;
        /* Màu xanh đậm để làm nổi bật */
        font-weight: bold;
}

.list-group-item {
        background-color: #f8f9fa;
        /* Màu nền nhẹ cho các mục */
        border: 1px solid #dee2e6;
        /* Viền nhẹ để chia cách */
        padding: 10px 20px;
}

ul#sinhVienList li {
        padding: 8px 12px;
        border-bottom: 1px solid #e9ecef;
}

ul#sinhVienList li:last-child {
        border-bottom: none;
}

.modal-body {
        padding: 20px 30px;
}

.modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 15px 30px;
}

.view_parcel {
        background-color: transparent;
        /* Nền trong suốt */
        border: none;
        /* Loại bỏ viền */
        color: inherit;
        /* Giữ nguyên màu sắc */
        cursor: pointer;
        /* Hiển thị con trỏ pointer */
}

.view_parcel:focus {
        outline: none;
        /* Loại bỏ đường viền khi focus */
}
</style>

@endsection