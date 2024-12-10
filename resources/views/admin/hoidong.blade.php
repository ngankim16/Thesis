@extends('admin-layout')
@section('admin_content')



<div class="col-lg-12">
        <div class="card card-outline">
                <a href="{{ route('hoidong.add_hoidong') }}" class="btn btn-info"> Thêm</a>
                <div class="card-header">


                        <!-- <style>
                        .card-tools .btn {
                                margin-top: 70px;
                                /* Điều chỉnh giá trị theo ý bạn */
                        }
                        </style> -->


                        <div class="container">
                                <h2 class="text-center" style="margin-bottom: 80px; text-align: center;">Danh Sách Hội
                                        Đồng Đánh Giá</h2>

                                @if (Session::has('thongbao'))
                                <div class="alert alert-success" id="thongbao">
                                        {{ Session::get('thongbao') }}
                                </div>
                                @endif

                                <!-- Table Hiển Thị Danh Sách Hội Đồng -->
                                @if($hoiDongs->isEmpty())
                                <p>Không có hội đồng nào được tạo.</p>
                                @else
                                <script>
                                // Sau 3 giây (3000 milliseconds), thông báo sẽ tự động biến mất
                                setTimeout(function() {
                                        $('#thongbao').fadeOut('slow');
                                }, 7000);
                                </script>
                                <style>
                                body {
                                        font-family: DejaVu Sans, sans-serif;
                                }

                                .alert-container {
                                        display: flex;
                                        justify-content: center;

                                }

                                .alert {
                                        padding: 15px;
                                        border-radius: 5px;
                                        margin-bottom: 15px;
                                        font-weight: bold;
                                        background-color: #d1ecf1;
                                        color: #0c5460;
                                        width: max-content;
                                }
                                </style>
                                <div class="card-tools card-tools text-right">
                                        @if($hoiDongs->isNotEmpty())
                                        <!-- Chỉ cần 1 nút In Phiếu cho tất cả sinh viên trong hội đồng đầu tiên -->
                                        <a href="{{ route('hoidong.in_phieu', ['hoc_ky' => request('hoc_ky'), 'nam_hoc' => request('nam_hoc')] ) }}"
                                                class="btn btn-info" target="_blank">
                                                <i class="fa fa-print"></i> Phiếu đánh giá
                                        </a>


                                        <a href="{{ route('hoidong.bien_ban', ['hoc_ky' => request('hoc_ky'), 'nam_hoc' => request('nam_hoc')] )}}"
                                                class="btn btn-info" target="_blank"> <i class="fa fa-print"></i> Biên
                                                bản</a>
                                        @else
                                        <p>Không có hội đồng nào để in phiếu.</p>
                                        @endif

                                </div>
                                <div class="container mt-4">
                                        <form class="d-flex justify-content-start mb-3" method="GET"
                                                action="{{ route('hoidong.index') }}">
                                                <!-- Năm học -->
                                                <div class="me-3">
                                                        <label for="nam_hoc" class="form-label">Năm học:</label>
                                                        <select class="form-select form-select-sm" id="nam_hoc"
                                                                name="nam_hoc" onchange="location = this.value;">
                                                                <!-- Lấy giá trị hiện tại từ GET -->
                                                                <option value="{{ route('hoidong.index', ['hoc_ky' => 1, 'nam_hoc' => '2024']) }}"
                                                                        @if(request()->get('nam_hoc') == '2024')
                                                                        selected @endif>2024</option>
                                                                <option value="{{ route('hoidong.index', ['hoc_ky' => 1, 'nam_hoc' => '2025']) }}"
                                                                        @if(request()->get('nam_hoc') == '2025')
                                                                        selected @endif>2025</option>
                                                                <!-- Add more options as needed -->
                                                        </select>
                                                </div>

                                                <span class="mx-2"></span>

                                                <!-- Học kỳ -->
                                                <div class="me-3">
                                                        <label for="hoc_ky" class="form-label">Học kỳ:</label>
                                                        <select class="form-select form-select-sm" id="hoc_ky"
                                                                name="hoc_ky" onchange="location = this.value;">
                                                                <!-- Lấy giá trị hiện tại từ GET -->
                                                                <option value="{{ route('hoidong.index', ['hoc_ky' => 1, 'nam_hoc' => request()->get('nam_hoc')]) }}"
                                                                        @if(request()->get('hoc_ky') == 1) selected
                                                                        @endif>Học kỳ 1</option>
                                                                <option value="{{ route('hoidong.index', ['hoc_ky' => 2, 'nam_hoc' => request()->get('nam_hoc')]) }}"
                                                                        @if(request()->get('hoc_ky') == 2) selected
                                                                        @endif>Học kỳ 2</option>
                                                        </select>
                                                </div>

                                        </form>
                                </div>




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
                                                        <td>
                                                                @php
                                                                $chuTich = DB::table('gom')
                                                                ->join('giang_vien', 'gom.MA_GV', '=',
                                                                'giang_vien.MA_GV')
                                                                ->where('gom.MA_HD', $hoiDong->MA_HD)
                                                                ->where('gom.DUYET_THAM_GIA', '0')
                                                                ->where('giang_vien.HOTEN_GV', $hoiDong->CHU_TICH_TEN)
                                                                // So khớp tên Chủ Tịch
                                                                ->select('giang_vien.HOTEN_GV')
                                                                ->first();
                                                                @endphp
                                                                <span class="{{ $chuTich ? 'text-danger' : '' }}">
                                                                        {{ $hoiDong->CHU_TICH_TEN }}
                                                                </span>
                                                        </td>
                                                        <td>
                                                                @php
                                                                $phoChuTich = DB::table('gom')
                                                                ->join('giang_vien', 'gom.MA_GV', '=',
                                                                'giang_vien.MA_GV')
                                                                ->where('gom.MA_HD', $hoiDong->MA_HD)
                                                                ->where('gom.DUYET_THAM_GIA', '0')
                                                                ->where('giang_vien.HOTEN_GV',
                                                                $hoiDong->PHO_CHU_TICH_TEN) // So khớp tên Phó Chủ Tịch
                                                                ->select('giang_vien.HOTEN_GV')
                                                                ->first();
                                                                @endphp
                                                                <span class="{{ $phoChuTich ? 'text-danger' : '' }}">
                                                                        {{ $hoiDong->PHO_CHU_TICH_TEN }}
                                                                </span>
                                                        </td>
                                                        <td>
                                                                @php

                                                                $thuKy = DB::table('gom')
                                                                ->join('giang_vien', 'gom.MA_GV', '=',
                                                                'giang_vien.MA_GV')
                                                                ->where('gom.MA_HD', $hoiDong->MA_HD)
                                                                ->where('gom.DUYET_THAM_GIA', '0')
                                                                ->where('giang_vien.HOTEN_GV', $hoiDong->THUKY_TEN)
                                                                ->select('giang_vien.HOTEN_GV')
                                                                ->first();
                                                                @endphp
                                                                <span class="{{ $thuKy ? 'text-danger' : '' }}">
                                                                        {{ $hoiDong->THUKY_TEN }}
                                                                </span>
                                                        </td>
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
                                                                        <a href="{{route('hoidong.edit_hd', $hoiDong->MA_HD)}}"
                                                                                class="edit">
                                                                                <i class="fas fa-edit"></i>
                                                                        </a>
                                                                        <a href="javascript:void(0)"
                                                                                onclick="if(confirm('Bạn có chắc chắn muốn xóa hội đồng này?')) { document.getElementById('delete-form-{{ $hoiDong->MA_HD }}').submit(); }"
                                                                                class="delete delete_parcel">
                                                                                <form id="delete-form-{{ $hoiDong->MA_HD }}"
                                                                                        action="{{ route('hoidong.delete_hd', $hoiDong->MA_HD) }}"
                                                                                        method="POST"
                                                                                        style="display: none;">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                </form>
                                                                                <i class="fas fa-trash"></i>
                                                                        </a>
                                                                </div>
                                                        </td>
                                                </tr>
                                                @endforeach
                                        </tbody>
                                </table>
                                @endif
                        </div>
                        <a href="{{ route('hoidong.hoidong_danhgia', ['hoc_ky' => request('hoc_ky'), 'nam_hoc' => request('nam_hoc')]) }}"
                                class="btn btn-info"> Xem Danh Sách</a>


                        <!-- Nút Tạo Hội Đồng -->
                        <!-- 
                        <a href="javascript:void(0)" class="btn btn-info"
                                onclick="if(confirm('Bạn có chắc chắn muốn xóa tất cả hội đồng?')) { document.getElementById('delete-all-form').submit(); }">
                                <form id="delete-all-form"
                                        action="{{ route('hoidong.delete_all') }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                </form>
                                <i class="fas fa-trash"></i>
                        </a> -->





                </div>
        </div>
</div>

<script>
document.getElementById('createHoiDongButton').addEventListener('click', function() {
        this.disabled = true; // Vô hiệu hóa nút sau khi nhấn
});
</script>


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
                                                        <p><strong>Thời Lượng:</strong> <span id="thoiLuong"></span>
                                                                phút</p>
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
                                $('#modal-detail #thoiLuong')
                                        .text(response.hoiDong
                                                .THOILUONG_BV);
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
.modal-header {
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
<script>
$.noConflict();
jQuery(document).ready(function($) {
        $('#list').DataTable();
});
</script>

@endsection