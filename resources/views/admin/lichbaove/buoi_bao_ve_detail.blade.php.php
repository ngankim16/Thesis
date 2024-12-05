@extends('admin-layout')

@section('admin_content')
<div class="container">
        <h1>Chi tiết Buổi Bảo Vệ</h1>

        <p><strong>Mã buổi bảo vệ:</strong> {{ $buoiBaoVe->MA_BV }}</p>
        <p><strong>Ngày bảo vệ:</strong> {{ $buoiBaoVe->NGAY_BV }}</p>
        <p><strong>Giờ bắt đầu:</strong> {{ $buoiBaoVe->GIO_BATDAU_BV }}</p>
        <p><strong>Phòng bảo vệ:</strong> {{ $buoiBaoVe->TEN_PH }}</p>

        <h2>Hội đồng đánh giá</h2>
        <p><strong>Mã hội đồng:</strong> {{ $buoiBaoVe->MA_HD }}</p>
        <p><strong>Chủ tịch hội đồng:</strong> {{ $buoiBaoVe->CHU_TICH_HD }}</p>
        <p><strong>Phó chủ tịch hội đồng:</strong> {{ $buoiBaoVe->PHO_CHU_TICH_HD }}</p>
        <p><strong>Thư ký hội đồng:</strong> {{ $buoiBaoVe->THU_HD }}</p>
</div>@endsection