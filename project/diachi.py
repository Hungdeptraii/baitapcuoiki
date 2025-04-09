import mysql.connector
import random
from datetime import datetime, timedelta

# Kết nối tới MySQL
db = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='quanlibanhang',
    charset='utf8mb4',
)

cursor = db.cursor()

# Lấy danh sách khách hàng từ bảng khach_hang
cursor.execute("SELECT Id FROM users")
khach_hangs = cursor.fetchall()

# Các loại thẻ ngân hàng mẫu
loai_the_options = ["Visa", "MasterCard", "JCB", "Amex"]

# Hàm tạo số thẻ ngẫu nhiên
def generate_card_number():
    return ''.join(random.choice('0123456789') for _ in range(16))

# Hàm tạo ngày phát hành ngẫu nhiên trong 5 năm gần đây
def generate_ngay_phat_hanh():
    start_date = datetime.now() - timedelta(days=5*365)
    return start_date + timedelta(days=random.randint(0, 5*365))

# Hàm tạo ngày hết hạn dựa trên ngày phát hành (cộng thêm 3 năm)
def generate_ngay_het_han(ngay_phat_hanh):
    return ngay_phat_hanh + timedelta(days=3*365)

# Tạo dữ liệu ngẫu nhiên và chèn vào bảng the_ngan_hang
for khach_hang in khach_hangs:
    MaKhachHang = khach_hang[0]
    SoThe = generate_card_number()
    NgayPhatHanh = generate_ngay_phat_hanh()
    NgayHetHan = generate_ngay_het_han(NgayPhatHanh)
    LoaiThe = random.choice(loai_the_options)
    
    cursor.execute("""
        INSERT INTO cart (MaKhachHang, SoThe, NgayPhatHanh, NgayHetHan, LoaiThe)
        VALUES (%s, %s, %s, %s, %s)
    """, (MaKhachHang, SoThe, NgayPhatHanh.strftime('%Y-%m-%d'), NgayHetHan.strftime('%Y-%m-%d'), LoaiThe))

# Lưu các thay đổi và đóng kết nối
db.commit()
cursor.close()
db.close()