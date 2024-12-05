<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Biên Bản Bảo Vệ Luận Văn</title>
        <style>
        body {
                font-family: DejaVu Serif, serif;
                margin: 10px;
                line-height: 1;
                font-size: 14px;
                /* Thu hẹp khoảng cách dòng */
        }

        .header {
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
        }

        .header p {
                margin: 2px 0;
                /* Giảm margin giữa các đoạn văn */
        }

        .header::after {
                content: "";
                display: table;
                clear: both;
        }

        .header-left {
                text-align: left;
                width: 50%;
                float: left;
        }

        .header-right {
                text-align: right;
                width: 50%;
                float: right;
        }

        .header-title {
                text-align: center;
                margin: 10px 0;
        }

        .row-sig {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: nowrap;
                /* Ngăn các phần tử xuống dòng */
                margin-bottom: 10px;
        }

        .left {
                flex: 1;
                /* Để nội dung bên trái chiếm không gian */
        }

        .right {
                flex: 1;
                /* Để nội dung bên phải chiếm không gian */
                text-align: right;
                /* Căn phải cho nội dung bên phải */
        }



        .left {
                float: left;

                text-align: left;

        }

        .right {
                display: inline-block;
                float: right;
                /* width: 50%; */
                text-align: right;

        }

        .row-sig::after {
                content: "";
                display: table;
                clear: both;
        }

        .row-sig p.right {
                text-align: right;
        }

        h4 {
                font-weight: bold;
        }

        h4,
        h5 {
                margin: 5px 0;
        }

        .content {
                margin-top: 30px;
        }

        table {
                width: 50%;
                border-collapse: collapse;
                margin: 20px auto;
                /* Căn giữa bảng */
                display: table;
                /* Đảm bảo bảng hiển thị là block */
        }


        th,
        td {
                border: 1px solid black;
                padding: 3px;
                text-align: left;
        }

        .signature-section {
                margin-top: 40px;
        }


        .left,
        .right {
                text-align: center;
                /* Căn giữa các nội dung trong từng phần tử */
                width: 45%;
                /* Mỗi phần tử chiếm 45% chiều rộng của khung */
        }

        .signature p {
                margin: 5px 0;
                /* Giảm khoảng cách giữa các đoạn văn */
        }


        /* Force page break after each student section */
        .signature-section {
                page-break-after: always;
        }

        .footer {
                text-align: center;
                margin-top: 20px;
        }
        </style>
</head>

<body>
        @foreach($hoiDongs as $hoiDong)
        <div class="header">
                <div class="header-left">
                        <p style="text-align:center; margin: 0;">Trường Đại Học Cần Thơ</p>
                        <p style="text-align:center; margin: 0;"><strong><u>KHOA CÔNG NGHỆ THÔNG TIN & TT</u></strong>
                        </p>
                </div>

                <div class="header-right">
                        <p style="text-align:center; margin: 0;">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
                        <p style="text-align:center; margin: 0;"><strong><u>Độc Lập - Tự Do - Hạnh Phúc</u></strong></p>
                </div>
        </div>

        <div class="header-title">
                <h4>BIÊN BẢN CỦA HỘI ĐỒNG</h4>
                <h4>CHẤM BẢO VỆ LUẬN VĂN ĐẠI HỌC</h4>
                <h4><strong>Học kỳ: 01 năm học 2023-2024</strong></h4>
                <h4 style="padding-top: 10px;">Ngành
                        <b style="color:red;">HỆ THỐNG THÔNG TIN</b>
                </h4>
        </div>

        <div class="content">
                @foreach($hoiDong->sinhViens as $sinhVien)
                <div class="student-section">
                        <div class="section">
                                <p>Họ tên sinh viên: {{$sinhVien->HOTEN_SV}}</p>
                                <p>MSSV: {{ $sinhVien->MA_SV }}</p>
                                <p>Mã lớp: {{ $sinhVien->LOP_SV }}</p>
                                <p>Giáo viên hướng dẫn: {{ $sinhVien->HOTEN_GV }}</p>
                                <p>Tên đề tài:<b> {{ $sinhVien->TEN_DT }} </b></p>
                                <p>Địa điểm bảo vệ:
                                </p>
                                <p>Thời gian:Lúc {{ $sinhVien->GIO_BAT_DAU }}, ngày 26 tháng 05 năm 2022</p>
                        </div>

                        <div class="section">
                                <h4>1. Tuyên bố lý do:</h4>
                                <p>
                                        Căn cứ vào Quyết định số ..../QĐ-CNTT&TT ngày / /2018 của Trưởng khoa CNTT&TT,
                                        Trường Đại học Cần Thơ
                                        về việc thành lập Hội đồng chấm Luận văn tốt nghiệp ngành/ chuyên ngành<b
                                                style="color:red;">Hệ thống
                                                thông tin </b> gồm các thành
                                        viên:
                                </p>
                                <ol>
                                        <li>{{ $hoiDong->CHU_TICH_TEN }}: Chủ tịch Hội đồng</li>
                                        <li>{{ $hoiDong->THUKY_TEN }}: Thư ký</li>
                                        <li>{{ $hoiDong->PHO_CHU_TICH_TEN }}: Ủy viên</li>
                                </ol>
                        </div>

                        <div class="section">
                                <h4>2. Chủ tịch Hội đồng điều khiển buổi bảo vệ luận văn:</h4>
                                <p><b>2.1 Sinh viên</b> trình bày luận văn</p>
                                <p><b>2.2 Các câu hỏi của thành viên hội đồng và trả lời của sinh viên:</b></p>
                                <p style=" padding: 10px; min-height: 100px;">(Điền thông tin
                                        các câu
                                        hỏi và trả lời tại đây)</p>
                                <p><b>2.3 Góp ý của thành viên trong hội đồng:</b></p>
                                <p style="padding: 10px; min-height: 100px;">(Điền góp ý tại
                                        đây)</p>
                                <p><b>2.4 Ý kiến nhận xét của người hướng dẫn:</b></p>
                                <p style=" padding:10px; min-height: 100px;">(Điền ý kiến tại
                                        đây)</p>
                        </div>

                        <div class="section">
                                <h4>2.5 Tổng hợp điểm của Hội đồng:</h4>
                                <table style="text-align:center">
                                        <thead>
                                                <tr>
                                                        <th>Thành viên</th>
                                                        <th>Điểm/10</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                <tr>
                                                        <td>{{ $hoiDong->CHU_TICH_TEN }}</td>
                                                        <td>(Điền điểm)</td>
                                                </tr>
                                                <tr>
                                                        <td>{{ $hoiDong->PHO_CHU_TICH_TEN }}</td>
                                                        <td>(Điền điểm)</td>
                                                </tr>
                                                <tr>
                                                        <td>{{ $hoiDong->THUKY_TEN }}</td>
                                                        <td>(Điền điểm)</td>
                                                </tr>
                                        </tbody>
                                </table>
                                <p><b>Trung bình:</b> ......................../10</p>
                                <p><b>Điểm chữ:</b> ................................</p>
                        </div>

                        <div class="section">
                                <h4>2.6 Kết luận của Hội đồng:</h4>
                                <p>Luận văn của sinh viên {{$sinhVien->HOTEN_SV}} đạt / không đạt yêu cầu.</p>
                                <p>Điểm: </p>
                                <p>Hội đồng kết thúc vào lúc {{$sinhVien->GIO_KET_THUC}} cùng ngày.</p>

                        </div>
                </div>
                @endforeach
        </div>
        <p><i>Cần Thơ, ngày...tháng... năm... </i></p>
        <div class="signature-section">
                <div class="row-sig" ">
                        <div class=" left">
                        <p><b>Chủ tịch Hội đồng</b></p>
                        <p style="margin-top: 70px; ">(Ký và ghi rõ họ tên)</p>
                        <p>{{ $hoiDong->CHU_TICH_TEN }}</p>
                </div>
                <div class="right">

                        <p><b>Thư ký Hội đồng</b></p>
                        <p style="margin-top: 70px; ">(Ký và ghi rõ họ tên)</p>
                        <p>{{ $hoiDong->THUKY_TEN }}</p>
                </div>
        </div>
        </div>

        @endforeach
</body>

</html>