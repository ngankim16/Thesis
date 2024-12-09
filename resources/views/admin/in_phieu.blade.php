<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Phiếu Chấm Luận Văn</title>
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
                margin-bottom: 9px;
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

        h4,
        h5 {
                margin: 5px 0;
        }

        .content {
                margin-top: 30px;
        }

        table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
        }

        th,
        td {
                border: 1px solid black;
                padding: 3px;
                text-align: left;
        }

        .info {
                font-size: 14px;
                /* Điều chỉnh kích thước chữ */
                line-height: 0.5;
                /* Khoảng cách dòng */
                margin: 10px 0;
        }

        .row {
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

        .row::after {
                content: "";
                display: table;
                clear: both;
        }

        .row p.right {
                text-align: right;
        }

        strong {
                font-weight: bold;
                /* Làm đậm tiêu đề */
        }

        /* 
        .row .left,
        .row .right {
                margin: 0 5px;
               
        } */

        .info,
        .row p,
        .content p {
                margin: 1px 0;
                /* Thu hẹp khoảng cách giữa các phần tử */
        }

        .student-section:last-of-type {
                page-break-after: always;
        }

        .footer {
                text-align: center;
                margin-top: 20px;
        }
        </style>
</head>

<body>
        @foreach($allPhieu as $phieu)
        <div class="header">
                <div class="header-left">
                        <p>Trường Đại Học Cần Thơ</p>
                        <p><strong>KHOA CÔNG NGHỆ THÔNG TIN & TT</strong></p>
                </div>
                <div class="header-right">
                        <p>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
                        <p>—— Độc Lập - Tự Do - Hạnh Phúc ——</p>
                </div>
        </div>

        <div class="header-title">
                <h4><strong>PHIẾU CHẤM LUẬN VĂN TỐT NGHIỆP ĐẠI HỌC</strong></h4>
                <h4><strong>Ngành: HỆ THỐNG THÔNG TIN</strong></h4>
                <p style="margin: 5px 0">(Dùng cho thành viên hội đồng chấm LVTN)</p>
        </div>

        <div class="content">
                <p><strong>Tên đề tài: </strong> {{ $phieu['sinhVien']->TEN_DT }}</p>
                <div class="col-12">
                        <div class="row">
                                <p class="left"><strong>Họ và tên sinh viên thực hiện:</strong>
                                        {{ $phieu['sinhVien']->HOTEN_SV }}
                                </p>
                                <p class="right"><strong>MSSV:</strong> {{ $phieu['sinhVien']->MA_SV }}</p>
                        </div>

                        <!-- Flex container for Người chấm and MSBC -->
                        <div class="row">
                                <p class="left"><strong>Tên người chấm:</strong>{{ $phieu['nguoiCham'] }}</p>
                                <p class="right"><strong>MSBC:</strong> 12344</p>
                        </div>

                        <!-- Flex container for Thời gian bắt đầu và kết thúc -->
                        <div class="row">
                                <p class="left"><strong>Thời gian bắt đầu báo cáo:</strong>
                                        {{ $phieu['sinhVien']->GIO_BAT_DAU }}
                                </p>
                                <p class="right"><strong>Thời gian kết thúc báo cáo:</strong>
                                        {{ $phieu['sinhVien']->GIO_KET_THUC }}
                                </p>
                        </div>

                </div>
                <p>Người chấm xem cuốn báo cáo, xem demo và dự buổi bảo vệ của sinh viên để cho điểm theo các
                        mục:</p>

                <table>
                        <thead>
                                <tr>
                                        <th width="10%">TT</th>
                                        <th width="40%">Nội dung</th>
                                        <th width="10%">Điểm tối đa</th>
                                        <th width="10%">Điểm chấm</th>
                                        <th width="30%">Ghi chú</th>
                                </tr>
                        </thead>
                        <tbody>
                                <tr>
                                        <td>1</td>
                                        <td>Hình thức quyển báo cáo (đúng mẫu LV, trích dẫn, tham chiếu hình
                                                ảnh,...)
                                        </td>
                                        <td>1.0</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td>2</td>
                                        <td>Nội dung báo cáo</td>
                                        <td>7.0</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td>2.1</td>
                                        <td>Tóm tắt (tiếng Việt, tiếng Anh)</td>
                                        <td>0.5</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td>2.2</td>
                                        <td>Phần giới thiệu (tính cần thiết, nghiên cứu liên quan, mục tiêu bài
                                                toán)
                                        </td>
                                        <td>1.0</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td>2.3</td>
                                        <td>Mô tả bài toán</td>
                                        <td>1.0</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td>2.4</td>
                                        <td>Thiết kế & cài đặt giải pháp</td>
                                        <td>2.0</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td>2.5</td>
                                        <td>Kiểm thử & đánh giá</td>
                                        <td>2.0</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td>2.6</td>
                                        <td>Phần kết luận và hướng phát triển</td>
                                        <td>0.5</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td>3</td>
                                        <td>Trình bày trả lời chất vấn</td>
                                        <td>2.0</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td>3.1</td>
                                        <td>Trình bày báo cáo</td>
                                        <td>1.0</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td>3</td>
                                        <td>Trả lời chất vấn</td>
                                        <td>1.0</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td>4</td>
                                        <td> <i>Cộng thêm điểm thưởng bài báo (sau khi đã chấm các mục trên và điểm còn
                                                        < 10)</i>
                                        </td>
                                        <td>2.0</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td></td>
                                        <td> <i>TỔNG CỘNG</i>
                                        </td>
                                        <td>10</td>
                                        <td></td>
                                        <td></td>
                                </tr>
                        </tbody>
                </table>
                <p><strong>Đánh giá (điểm chữ): </strong>
                </p>
                <div class=" student-section" style=" text-align: right;">
                        <p>Ngày {{ now()->day }} tháng {{ now()->month }} năm {{ now()->year }}
                        </p>
                        <p><b>NGƯỜI CHẤM</b></p>
                        <p style="margin-top: 40px; ">{{ $phieu['nguoiCham'] }}</p>
                        <p style="text-align:center; color:red; font-size:11px;">Lưu ý: Sinh viên trình bày và demo luận
                                văn trong thời
                                gian 20 phút. Hội đồng đánh giá và góp ý trong vòng 15 phút. </p>
                </div>


        </div>
        @endforeach
</body>

</html>