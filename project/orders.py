import mysql.connector
from mysql.connector import Error
import random
from datetime import datetime, timedelta
from faker import Faker

fake = Faker()

def create_connection(host_name, user_name, user_password, db_name):
    connection = None
    try:
        connection = mysql.connector.connect(
            host=host_name,
            user=user_name,
            passwd=user_password,
            database=db_name
        )
        print("Connection to MySQL DB successful")
    except Error as e:
        print(f"The error '{e}' occurred")
    return connection

def execute_query(connection, query):
    cursor = connection.cursor()
    try:
        cursor.execute(query)
        connection.commit()
        print("Query executed successfully")
    except Error as e:
        print(f"The error '{e}' occurred")

def create_orders(connection, num_orders):
    cursor = connection.cursor()
    cursor.execute("SELECT Id FROM users")
    user_ids = cursor.fetchall()
    user_ids = [id[0] for id in user_ids]
    
    for _ in range(num_orders):
        ma_khach_hang = random.choice(user_ids)
        order_date = datetime(2024, 1, 1) + timedelta(days=random.randint(0, 364))
        dia_chi_dat_hang = fake.address().replace('\n', ', ')
        query = f"""
        INSERT INTO Orders (MaKhachHang, NgayDatHang, DiaChiDatHang)
        VALUES ({ma_khach_hang}, '{order_date.strftime('%Y-%m-%d')}', '{dia_chi_dat_hang}')
        """
        try:
            cursor.execute(query)
        except Error as e:
            print(f"The error '{e}' occurred while inserting order")
    connection.commit()

def create_order_details(connection):
    cursor = connection.cursor()
    cursor.execute("SELECT SoDonHang FROM Orders")
    order_ids = cursor.fetchall()
    order_ids = [id[0] for id in order_ids]
    
    cursor.execute("SELECT MaHang, GiaNhap FROM Products")
    products = cursor.fetchall()
    product_dict = {product[0]: product[1] for product in products}

    for order_id in order_ids:
        num_products = random.randint(1, 2)  # Mỗi đơn hàng sẽ có từ 1 đến 2 sản phẩm
        selected_products = random.sample(list(product_dict.keys()), num_products)
        for ma_hang in selected_products:
            so_luong = random.randint(1, 2)  # Số lượng từ 1 đến 2
            don_gia = product_dict[ma_hang]
            query = f"""
            INSERT INTO OrderDetails (SoDonHang, MaHang, SoLuong, DonGia)
            VALUES ({order_id}, '{ma_hang}', {so_luong}, {don_gia})
            """
            try:
                cursor.execute(query)
            except Error as e:
                print(f"The error '{e}' occurred while inserting order details")
    connection.commit()

# Kết nối đến cơ sở dữ liệu
connection = create_connection("localhost", "root", "", "quanlibanhang")

# Tạo đơn hàng cho các khách hàng hiện có
create_orders(connection, 2000)

# Tạo chi tiết đơn hàng cho mỗi đơn hàng hiện có
create_order_details(connection)
