import pymysql
import random

# Kết nối tới cơ sở dữ liệu MySQL
connection = pymysql.connect(
    host="localhost",
    user="root",
    password="",
    database="quanlibanhang"
)

# Tạo con trỏ để thực hiện các truy vấn
try:
    with connection.cursor() as cursor:
        # Lấy tất cả các OrderID từ bảng Orders
        cursor.execute("SELECT SoDonHang FROM Orders")
        orders = cursor.fetchall()

        # Danh sách các trạng thái để chọn ngẫu nhiên
        statuses = [
            'Đã giao', 'Đang giao', 'Chờ thanh toán', 'Đang vận chuyển',
            'Hoàn thành', 'Đã hủy', 'Trả hàng/Hoàn tiền', 'Chờ xử lí'
        ]

        # Danh sách các phương thức thanh toán để chọn ngẫu nhiên
        payment_methods = [
            'Thanh toán khi nhận hàng', 'Thanh toán qua ngân hàng', 'Thanh toán qua ví điện tử'
        ]

        # Cập nhật trạng thái và phương thức thanh toán ngẫu nhiên cho mỗi đơn hàng
        for order in orders:
            order_id = order[0]
            status = random.choice(statuses)
            payment_method = random.choice(payment_methods)

            update_order_query = """
                UPDATE Orders
                SET StatusName = %s,
                    PhuongThucThanhToan = %s
                WHERE SoDonHang = %s
            """
            cursor.execute(update_order_query, (status, payment_method, order_id))

        connection.commit()
        print("Đã cập nhật trạng thái và phương thức thanh toán ngẫu nhiên cho các đơn hàng thành công.")

finally:
    connection.close()
