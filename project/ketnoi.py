import mysql.connector
import csv

# Kết nối tới cơ sở dữ liệu MySQL
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="quanlibanhang"
)
cursor = conn.cursor()

# Xóa tất cả dữ liệu cũ trong bảng users


# Đọc dữ liệu từ tệp CSV và chèn vào bảng users
with open('customers.csv', newline='') as file:
    reader = csv.reader(file)
    next(reader)  # Bỏ qua dòng tiêu đề
    for row in reader:
        cursor.execute(
            "INSERT INTO users (Username, Email, Age, Password, PhoneNumber, Address, Role) VALUES (%s, %s, %s, %s, %s, %s, %s)",
            row
        )

# Lưu thay đổi và đóng kết nối
conn.commit()
cursor.close()
conn.close()

print("Dữ liệu đã được chèn vào bảng users")
