@extends('admin-layout-sv')
@section('admin_content')
<div class="row">
        <div class="col-12 col-sm-6 col-md-4">
                <div class="small-box shadow-sm border c-1">
                        <div class="icon">
                                <i class="fa fa-user-graduate"></i>
                        </div>
                        <div class="inner">
                                <p>Sinh Viên</p>
                                <h3>{{$total_sinhvien}}</h3>
                        </div>
                </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
                <div class="small-box shadow-sm border c-2">
                        <div class="icon">
                                <i class="fa fa-boxes"></i>
                        </div>
                        <div class="inner">
                                <p>Giảng Viên</p>
                                <h3>{{$total_giangvien}}</h3>
                        </div>
                </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
                <div class="small-box shadow-sm border c-3">
                        <div class="icon">
                                <i class="fa fa-users"></i>
                        </div>
                        <div class="inner">
                                <p>Hội Đồng Bảo Vệ</p>
                                <h3>{{$total_hoidong}}</h3>
                        </div>
                </div>
        </div>
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
}, 7000);
</script>


<div class="row">
        <div class="col-12 col-sm-6 col-md-4">
                <div class="calendar">
                        <header>
                                <h3 id="monthYear"></h3>
                                <nav>
                                        <button id="prev">&#10094;</button>
                                        <button id="next">&#10095;</button>
                                </nav>
                        </header>
                        <section>
                                <ul class="days">
                                        <li>Sun</li>
                                        <li>Mon</li>
                                        <li>Tue</li>
                                        <li>Wed</li>
                                        <li>Thu</li>
                                        <li>Fri</li>
                                        <li>Sat</li>
                                </ul>
                                <ul class="dates" id="dates"></ul> <!-- Thêm id cho danh sách ngày -->
                        </section>
                </div>
        </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
        // Danh sách ngày cần đánh dấu (truyền từ Laravel)
        const lichBaoVeDates = @json($lich_bao_ve_dates ?? []); // Nếu không có biến, dùng mảng rỗng
        const calendar = {
                currentMonth: new Date().getMonth(),
                currentYear: new Date().getFullYear(),
                today: new Date()
        };

        // Hàm render lịch
        function renderCalendar(month, year) {
                const datesContainer = document.getElementById("dates");
                datesContainer.innerHTML = "";

                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const prevDaysInMonth = new Date(year, month, 0).getDate();

                document.getElementById("monthYear").textContent = `${month + 1}/${year}`;

                // Tạo ô trống cho các ngày trước ngày 1
                for (let i = 0; i < firstDay; i++) {
                        const emptyCell = document.createElement("li");
                        const prevDay = prevDaysInMonth - firstDay + 1 + i;
                        emptyCell.textContent = prevDay;
                        emptyCell.classList.add("previous-month");
                        datesContainer.appendChild(emptyCell);
                }

                // Lặp qua các ngày trong tháng
                for (let day = 1; day <= daysInMonth; day++) {
                        const date = new Date(year, month, day);
                        const dateStr = date.toISOString().split('T')[0];
                        const dateItem = document.createElement("li");
                        dateItem.textContent = day;

                        // Đánh dấu ngày có lịch bảo vệ
                        if (lichBaoVeDates.includes(dateStr)) {
                                // Chỉ đánh dấu nếu ngày bảo vệ chưa qua
                                if (date >= calendar.today) {
                                        dateItem.classList.add("highlight");
                                }
                        }

                        // Đánh dấu ngày hôm nay
                        if (
                                date.getDate() === calendar.today.getDate() &&
                                date.getMonth() === calendar.today.getMonth() &&
                                date.getFullYear() === calendar.today.getFullYear()
                        ) {
                                dateItem.classList.add("today");
                        }

                        datesContainer.appendChild(dateItem);
                }
        }

        document.getElementById("prev").addEventListener("click", () => {
                if (calendar.currentMonth === 0) {
                        calendar.currentMonth = 11;
                        calendar.currentYear -= 1;
                } else {
                        calendar.currentMonth -= 1;
                }
                renderCalendar(calendar.currentMonth, calendar.currentYear);
        });

        document.getElementById("next").addEventListener("click", () => {
                if (calendar.currentMonth === 11) {
                        calendar.currentMonth = 0;
                        calendar.currentYear += 1;
                } else {
                        calendar.currentMonth += 1;
                }
                renderCalendar(calendar.currentMonth, calendar.currentYear);
        });

        renderCalendar(calendar.currentMonth, calendar.currentYear);
});
</script>



<style>
.calendar {
        max-width: 300px;
        /* Chiều rộng tối đa của lịch */
        margin: auto;
        /* Căn giữa lịch */
        border: 1px solid #ccc;
        border-radius: 8px;
        overflow: hidden;

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

.days {
        display: grid;
        /* Sử dụng grid cho danh sách ngày */
        grid-template-columns: repeat(7, 1fr);
        /* 7 cột cho 7 ngày trong tuần */
        font-weight: bold;
        /* background-color: #f0f0f0; */
        /* Bối cảnh cho các tiêu đề ngày */
}

.days li {
        padding: 6px 0;
        /* Khoảng cách trên và dưới cho tiêu đề tuần */
        text-align: center;
}

.dates {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        /* 7 cột cho 7 ngày */
        margin: 0;
        padding: 0;
        list-style: none;
        /* Bỏ dấu đầu dòng */
}

.dates li {
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 18px;
        position: relative;
        /* Để có thể sử dụng vị trí tương đối cho các hiệu ứng khác */
}

.highlight {
        background-color: red;
        color: white;
        border-radius: 50%;
        /* Bo tròn cho ô đánh dấu */
        width: 20px;
        height: 20px;
        display: flex;
        /* Căn giữa số bên trong */
        align-items: center;
        /* Căn giữa theo chiều dọc */
        justify-content: center;
        /* Căn giữa theo chiều ngang */
}

.today {

        background-color: black;
        font-weight: bold;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
}

.previous-month {

        color: gray;
        /* Màu chữ nhạt hơn */
        display: flex;
        /* Căn giữa số bên trong */
        align-items: center;
        /* Căn giữa theo chiều dọc */
        justify-content: center;
        /* Căn giữa theo chiều ngang */
}
</style>
@endsection