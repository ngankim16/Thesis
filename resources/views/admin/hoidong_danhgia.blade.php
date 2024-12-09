@extends('admin-layout')

@section('admin_content')

<div class="col-lg-12">
        <div class="card card-outline">
                <div class="card-header">
                        <div class="container">

                                <h2 class="title">TRƯỜNG ĐẠI HỌC CẦN THƠ</h2>
                                <h3 class="sub-title">KHOA CÔNG NGHỆ THÔNG TIN & TRUYỀN THÔNG</h3>
                                <h4 class="text-center document-title">DANH SÁCH HỘI ĐỒNG BẢO VỆ LUẬN VĂN TỐT NGHIỆP
                                </h4>
                                <h3 class="text-center sub-title">Bộ môn: HỆ THỐNG THÔNG TIN</h3>
                                <p class="text-center document-subtitle">(Kèm theo quyết định số ... /QĐ-CNTT&TT ngày
                                        .../.../...)</p>
                                <p><strong>Ghi chú:</strong> Vai trò các thành viên Hội đồng: (1) Chủ tịch, (2) Ủy viên,
                                        (3) Thư ký</p>

                                @if($hoiDongs->isNotEmpty())
                                @php
                                $globalIndex = 1;
                                // Sắp xếp theo ngày, phòng và giờ bắt đầu
                                $groupedHoidongs = $hoiDongs->sortBy(['NGAY_BV', 'TEN_PH',
                                'GIO_BAT_DAU'])->groupBy(function($item) {
                                return $item->NGAY_BV . ',' . $item->TEN_PH;
                                });
                                @endphp

                                @foreach($groupedHoidongs as $dateRoom => $hoiDongsByDateRoom)
                                @php
                                list($ngay, $phong) = explode(",", $dateRoom);
                                $ngay = \Carbon\Carbon::parse($ngay)->format('d/m/Y');
                                $totalStudents = $hoiDongsByDateRoom->flatMap(function ($hoiDong) {
                                return $hoiDong->sinhViens;
                                })->count();
                                @endphp

                                <div class="mb-3">
                                        <p><strong>Ngày:</strong> {{ $ngay }} – <strong>Phòng:</strong> {{ $phong }} –
                                                <strong>Tổng số sinh viên:</strong> {{ $totalStudents }}
                                        </p>
                                </div>

                                <table class="table table-bordered mt-3 table-content">
                                        <thead>
                                                <tr>
                                                        <th style="width: 50px;">STT</th>
                                                        <th style="width: 100px;">MSSV</th>
                                                        <th style="width: 200px;">Họ tên SV</th>
                                                        <th style="width: 250px;">Tên đề tài</th>
                                                        <th style="width: 200px;">GVHD</th>
                                                        <th style="width: 120px;">Giờ bảo vệ</th>
                                                        <th style="width: 250px;">Hội đồng</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                @foreach($hoiDongsByDateRoom as $hoiDong)
                                                @foreach($hoiDong->sinhViens->sortBy('GIO_BAT_DAU') as $sinhVien)
                                                <tr>
                                                        <td>{{ $globalIndex++ }}</td>
                                                        <td>{{ $sinhVien->MA_SV }}</td>
                                                        <td>{{ $sinhVien->HOTEN_SV }}</td>
                                                        <td>{{ $sinhVien->TEN_DT }}</td>
                                                        <td>{{ $sinhVien->HOTEN_GV }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($sinhVien->GIO_BAT_DAU)->format('H:i') }}
                                                        </td>
                                                        <td>
                                                                {{ $hoiDong->CHU_TICH_TEN }}<br>
                                                                {{ $hoiDong->PHO_CHU_TICH_TEN }}<br>
                                                                {{ $hoiDong->THUKY_TEN }}
                                                        </td>
                                                </tr>
                                                @endforeach
                                                @endforeach
                                        </tbody>
                                </table>
                                @endforeach
                                @else
                                <p>Không có hội đồng nào được tạo.</p>
                                @endif

                                @if($hoiDongs->isNotEmpty())
                                <a href="{{ route('hoidong.danh_gia', $hoiDongs->first()->MA_HD,['hoc_ky' => request('hoc_ky'), 'nam_hoc' => request('nam_hoc')] ) }}"
                                        class="btn btn-info" target="_blank"> <i class="fa fa-print"></i>In Phiếu</a>
                                @else
                                <p>Không có hội đồng nào để in phiếu.</p>
                                @endif

                        </div>
                </div>
        </div>
</div>

<style>
.title {
        font-size: 26px;
        font-weight: bold;
}

.card h3 {
        font-size: 22px;
        font-weight: bold;
        color: #262020;
        padding: 1.1rem 1.70rem 1.25rem;
}

.sub-title {
        font-size: 22px;
        font-weight: bold;
}

.document-title {
        font-size: 20px;
        font-weight: bold;
        margin-top: 10px;
}

.document-subtitle {
        font-size: 18px;
        margin-top: 5px;
}

.table-content th,
.table-content td {
        font-size: 16px;
        font-weight: normal;
}

.text-center {
        text-align: center;
}

.table-bordered {
        border: 1px solid #2c3e50;
}

.table th,
.table td {
        vertical-align: middle;
        text-align: center;
}
</style>

@endsection