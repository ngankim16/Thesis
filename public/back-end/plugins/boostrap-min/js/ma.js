$(document).ready(function() {
    // Xử lý sự kiện click cho nút mở/thu gọn sidebar
    $('[data-widget="pushmenu"]').on('click', function(event) {
        event.preventDefault(); // Ngăn chặn hành động mặc định của liên kết
        $('body').toggleClass('sidebar-collapse'); // Thêm hoặc xóa lớp 'sidebar-collapse' trên body
    });

    // Xử lý sự kiện click cho các nút dropdown trong sidebar
    $('.nav-item.dropdown > a').on('click', function(event) {
        event.preventDefault(); // Ngăn chặn hành động mặc định của liên kết
        event.stopPropagation(); // Ngăn chặn sự kiện click lan truyền

        // Chuyển đổi trạng thái hiển thị của submenu
        const $submenu = $(this).siblings('.nav-treeview');
        $submenu.toggle(); // Chuyển đổi trạng thái hiển thị của menu con

        // Đóng tất cả các menu khác
        $('.nav-treeview').not($submenu).hide();
    });

    // Đóng dropdown nếu click bên ngoài
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.nav-item.dropdown').length) {
            $('.nav-treeview').hide(); // Ẩn tất cả menu khi click bên ngoài
        }
    });
});