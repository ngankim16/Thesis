<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Danh Sách Hội Đồng Bảo Vệ</title>
        <style>
        body {
                font-family: DejaVu Serif, serif;
                margin: 10px;
                line-height: 1.2;
                font-size: 12px;
        }

        .title {
                font-size: 20px;
                font-weight: bold;
        }

        .sub-title {
                font-size: 18px;
                font-weight: bold;
        }

        .document-title {
                font-size: 16px;
                font-weight: bold;
                margin-top: 10px;
        }

        .document-subtitle {
                font-size: 14px;
                margin-top: 5px;
        }

        .table-content th,
        .table-content td {
                font-size: 12px;
                font-weight: normal;
        }

        .text-center {
                text-align: center;
        }

        .table-content {
                width: 100%;
                border-collapse: collapse;
                /* Ensures borders between cells are merged */
        }

        .table-content th,
        .table-content td {
                border: 1px solid #aaa;
                /* Darker gray border color */
                padding: 10px;
                /* Adds padding inside cells */
                text-align: center;
                /* Centers the content inside cells */
        }

        .table-content th {
                background-color: #f4f4f4;
                /* Light gray background for header */
        }

        .table-content td {
                background-color: #ffffff;
                /* White background for data cells */
        }


        .table th,
        .table td {
                vertical-align: middle;
                text-align: center;
                padding: 5px;
                /* Thêm khoảng cách bên trong các ô */
        }
        </style>
</head>

<body>
        <h2 class="title">TRƯỜNG ĐẠI HỌC CẦN THƠ</h2>
        <h3 class="sub-title">KHOA CÔNG NGHỆ THÔNG TIN & TRUYỀN THÔNG</h3>
        <h4 class="text-center document-title">DANH SÁCH HỘI ĐỒNG BẢO VỆ LUẬN VĂN TỐT NGHIỆP</h4>
        <h3 class="text-center sub-title">Bộ môn: HỆ THỐNG THÔNG TIN</h3>
        <p class="text-center document-subtitle">(Kèm theo quyết định số ... /QĐ-CNTT&TT ngày .../.../...)</p>
        <p><strong>Ghi chú:</strong> Vai trò các thành viên Hội đồng: (1) Chủ tịch, (2) Ủy viên, (3) Thư ký</p>

        @if($hoiDongs->isNotEmpty())
        @php
        $globalIndex = 1;
        $groupedHoidongs = $hoiDongs->groupBy(function($item) {
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
                <p><strong>Ngày:</strong> {{ $ngay }} – <strong>Phòng:</strong> {{ $phong }} – <strong>Tổng số sinh
                                viên:</strong> {{ $totalStudents }}</p>
        </div>

        <table class="table table-bordered mt-3 table-content">
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
                        @foreach($hoiDongsByDateRoom as $hoiDong)
                        @foreach($hoiDong->sinhViens as $sinhVien)
                        <tr>
                                <td>{{ $globalIndex++ }}</td>
                                <td>{{ $sinhVien->MA_SV }}</td>
                                <td>{{ $sinhVien->HOTEN_SV }}</td>
                                <td>{{ $sinhVien->TEN_DT }}</td>
                                <td>{{ $sinhVien->HOTEN_GV }}</td>
                                <td>{{ $sinhVien->GIO_BAT_DAU }}</td>
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
</body>

</html>