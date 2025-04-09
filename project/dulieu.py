import csv
import random
import string
from faker import Faker

# Tạo đối tượng Faker với ngôn ngữ Việt Nam
fake = Faker('vi_VN')

# Hàm tạo mật khẩu với chữ cái và số, viết hoa chữ cái đầu
def generate_password(length=10):
    password = ''.join(random.choices(string.ascii_lowercase + string.digits, k=length-1))
    return random.choice(string.ascii_uppercase) + password

# Tạo 10.000 dữ liệu khách hàng
data = []
for _ in range(10000):
    data.append([
        fake.user_name(),
        fake.email(),
        fake.random_int(min=18, max=90, step=1),
        generate_password(),
        fake.phone_number(),
        fake.address().replace('\n', ', '),
        0  # Giá trị mặc định cho cột role
    ])

# Lưu dữ liệu vào tệp CSV
with open('customers.csv', 'w', newline='') as file:
    writer = csv.writer(file)
    writer.writerow(["Username", "Email", "Age", "Password", "PhoneNumber", "Address", "Role"])
    writer.writerows(data)

print("Dữ liệu đã được tạo và lưu vào customers.csv")
