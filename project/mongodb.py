import pymongo
import mysql.connector
from decimal import Decimal
from datetime import date, datetime

# Kết nối tới MySQL
mysql_conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="quanlibanhang"
)
mysql_cursor = mysql_conn.cursor(dictionary=True)

# Truy vấn dữ liệu từ MySQL
mysql_cursor.execute("SELECT * FROM products")
rows = mysql_cursor.fetchall()

def convert_data(data):
    if isinstance(data, list):
        return [convert_data(item) for item in data]
    elif isinstance(data, dict):
        return {key: convert_data(value) for key, value in data.items()}
    elif isinstance(data, Decimal):
        return float(data)
    elif isinstance(data, date):
        return datetime.combine(data, datetime.min.time())
    else:
        return data

# Chuyển đổi dữ liệu
converted_rows = convert_data(rows)

# Kết nối tới MongoDB
mongo_client = pymongo.MongoClient("mongodb://localhost:27017/")
mongo_db = mongo_client["quanlibanhang"]
mongo_collection = mongo_db["products"]

# Nhập dữ liệu vào MongoDB
mongo_collection.insert_many(converted_rows)

# Đóng kết nối
mysql_cursor.close()
mysql_conn.close()
mongo_client.close()

print("Nhập dữ liệu thành công!")