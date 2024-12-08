@extends('admin-layout')

@section('admin_content')
<h3 class="text-center">Duyệt Tham Gia Hội Đồng</h3>

<div class="container mt-4">
        <!-- Hội Đồng Chưa Duyệt -->
        <h4 class="text-center">Hội Đồng Chưa Duyệt</h4>


        @if ($hoidongs->where('DUYET_THAM_GIA', null)->isNotEmpty())
        <div class="mb-4">
                <form action="{{ route('giangvien.duyetTatCaHoiDong') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info">Duyệt Tất Cả</button>
                </form>
        </div>
        @endif

        @if ($hoidongs->where('DUYET_THAM_GIA', null)->isEmpty())
        <div class="alert alert-warning">Không có hội đồng nào chưa được duyệt.</div>
        @else
        @foreach ($hoidongs as $hoidong)
        @if (is_null($hoidong->DUYET_THAM_GIA))
        <div class="hoidong-item card mb-3 p-3">

                <div class="card-body">
                        <h5 class="card-title">Mã Hội Đồng: {{ $hoidong->MA_HD }}</h5>
                        <p class="card-text">Ngày bảo vệ: {{ $hoidong->NGAY_BV }}</p>
                        <h6>Danh sách sinh viên tham gia:</h6>
                        <ul>
                                @foreach ($hoidong->sinhViens as $sinhVien)
                                <li>
                                        {{ $sinhVien->HOTEN_SV }} (Đề tài: {{ $sinhVien->TEN_DT }}) - Giờ bảo vệ:
                                        {{ $sinhVien->GIO_BAT_DAU }}
                                </li>
                                @endforeach
                        </ul>
                        <form action="{{ route('giangvien.duyetHoiDong', $hoidong->MA_HD) }}" method="POST">
                                @csrf
                                <button type="submit" name="duyet" value="1" class="btn btn-success">Đồng Ý</button>
                                <button type="submit" name="duyet" value="0" class="btn btn-danger">Từ Chối</button>
                        </form>
                </div>
        </div>
        @endif
        @endforeach
        @endif
        <!-- Hội Đồng Đã Duyệt -->
        <h4 class="text-center mt-5">Hội Đồng Đã Duyệt</h4>

        <!-- Hội Đồng Đã Đồng Ý -->
        @if ($hoidongs->where('DUYET_THAM_GIA', 1)->isEmpty())
        <div class="alert alert-info">Không có hội đồng nào đã đồng ý tham gia.</div>
        @else
        <h5>Đã Đồng Ý</h5>
        @foreach ($hoidongs as $hoidong)
        @if ($hoidong->DUYET_THAM_GIA === 1)
        <div class="hoidong-item card mb-3 p-3">
                <div class="card-body">
                        <h5 class="card-title">Mã Hội Đồng: {{ $hoidong->MA_HD }}</h5>
                        <p class="card-text">Ngày: {{ $hoidong->NGAY_BV }}</p>
                        <h6>Sinh viên tham gia:</h6>
                        <ul>
                                @foreach ($hoidong->sinhViens as $sinhVien)
                                <li>
                                        {{ $sinhVien->HOTEN_SV }} (Đề tài: {{ $sinhVien->TEN_DT }})
                                        <div><strong>Giờ bảo vệ: {{ $sinhVien->GIO_BAT_DAU }}</strong></div>
                                </li>
                                @endforeach
                        </ul>

                        <p class="card-text">
                                Trạng thái: <span class="text-success">Đã Đồng Ý</span>
                        </p>
                </div>
        </div>
        @endif
        @endforeach
        @endif

        <!-- Hội Đồng Đã Từ Chối -->
        @if ($hoidongs->where('DUYET_THAM_GIA', 0)->isEmpty())
        <div class="alert alert-info">Không có hội đồng nào đã bị từ chối.</div>
        @else
        <h5>Đã Từ Chối</h5>
        @foreach ($hoidongs as $hoidong)
        @if ($hoidong->DUYET_THAM_GIA === 0)
        <div class="hoidong-item card mb-3 p-3">
                <div class="card-body">
                        <h5 class="card-title">Mã Hội Đồng: {{ $hoidong->MA_HD }}</h5>
                        <p class="card-text">Ngày: {{ $hoidong->NGAY_BV }}</p>
                        <h6>Sinh viên tham gia:</h6>
                        <ul>
                                @foreach ($hoidong->sinhViens as $sinhVien)
                                <li>
                                        {{ $sinhVien->HOTEN_SV }} (Đề tài: {{ $sinhVien->TEN_DT }})
                                        <div><strong>Giờ bảo vệ: {{ $sinhVien->GIO_BAT_DAU }}</strong></div>
                                </li>
                                @endforeach
                        </ul>

                        <p class="card-text">
                                Trạng thái: <span class="text-danger">Từ Chối</span>
                        </p>
                </div>
        </div>
        @endif
        @endforeach
        @endif

</div>
@if(session('thongbao'))
<div id="thongbao" class="alert alert-info custom-alert">
        {{ session('thongbao') }}
</div>
@endif

<script>
// Sau 3 giây (3000 milliseconds), thông báo sẽ tự động biến mất
setTimeout(function() {
        $('#thongbao').fadeOut('slow');
}, 9000);
</script>
<script>
document.querySelectorAll('.hoidong-item button').forEach(function(button) {
        button.addEventListener('click', function() {
                var item = this.closest('.hoidong-item');
                item.style.display = 'none'; // Ẩn hội đồng sau khi duyệt
        });
});
</script>

<style>
.alert {
        padding: 15px;
        border-radius: 5px;
        font-weight: bold;
        margin-bottom: 15px;
}

.alert-warning {
        background-color: #fff3cd;
        color: #856404;
}

.alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
}
</style>
@endsection