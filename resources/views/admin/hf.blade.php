@extends('admin-layout')

@section('admin_content')
<div class="container">
        <h2 class="text-center">DANH SÁCH HỘI ĐỒNG BẢO VỆ LUẬN VĂN TỐT NGHIỆP</h2>
        <p>Bộ môn: HỆ THỐNG THÔNG TIN</p>
        <p>(Kèm theo quyết định số ... /QĐ-CNTT&TT ngày .../.../....)</p>

        <table class="table table-bordered mt-4">
                <thead>
                        <tr>
                                <th>STT</th>
                                <th>MSSV</th>
                                <th>Họ tên SV</th>
                                <th>Tên đề tài</th>
                                <th>GVHD</th>
                                <th>Giờ bảo vệ</th>
                                <th>Hội đồng</th>
                        </tr>
                </thead>
                <tbody>
                        @foreach($hoiDongs as $index => $hoiDong)
                        <tr>
                                <td colspan="7" class="font-weight-bold">
                                        Hội đồng {{ $index + 1 }}
                                        <br>
                                        Chủ tịch: {{ $hoiDong->CHU_TICH_TEN }},
                                        Ủy viên: {{ $hoiDong->PHO_CHU_TICH_TEN }},
                                        Thư ký: {{ $hoiDong->THUKY_TEN }}
                                </td>
                        </tr>
                        @foreach($hoiDong->sinhViens as $key => $sinhVien)
                        <tr>
                                <td>{{ $sinhVien->MA_SV }}</td>
                                <td>{{ $sinhVien->HOTEN_SV }}</td>
                                <td>{{ $sinhVien->TEN_DT }}</td>
                                <td>{{$sinhVien->HOTEN_GV}}</td> <!-- Thêm tên giảng viên hướng dẫn nếu có -->
                                <td>{{$sinhVien->GIO_BAT_DAU}}</td> <!-- Thêm giờ bảo vệ nếu có -->
                        </tr>
                        @endforeach
                        @endforeach
                </tbody>
        </table>
</div>
<style>
.container {
        margin-top: 30px;
}

h2.text-center {
        font-weight: bold;
        color: #2c3e50;
}

.table-bordered {
        border: 2px solid #2c3e50;
}

.table th,
.table td {
        vertical-align: middle;
        text-align: center;
}

.font-weight-bold {
        background-color: #f8f9fa;
}
</style>

@endsection