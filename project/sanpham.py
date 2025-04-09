import mysql.connector
from mysql.connector import Error

def insert_or_update_product(ma_hang, ten_san_pham, loai_hang, ten_danh_muc, noi_san_xuat, so_luong_nhap, gia_nhap, ngay_nhap, nha_cung_cap, hinh_anh, mo_ta):
    try:
        connection = mysql.connector.connect(
            host='localhost',
            database='quanlibanhang',
            user='root',
            password=''
        )

        if connection.is_connected():
            cursor = connection.cursor()
            connection.start_transaction()

            try:
                # Chèn sản phẩm hoặc cập nhật nếu đã tồn tại
                insert_or_update_product_query = """
                INSERT INTO products (MaHang, TenSanPham, LoaiHang, TenDanhMuc, NoiSanXuat, SoLuongNhap, GiaNhap, NgayNhap, NhaCungCap, HinhAnh, MoTa)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
                ON DUPLICATE KEY UPDATE
                TenSanPham = VALUES(TenSanPham),
                LoaiHang = VALUES(LoaiHang),
                TenDanhMuc = VALUES(TenDanhMuc),
                NoiSanXuat = VALUES(NoiSanXuat),
                SoLuongNhap = VALUES(SoLuongNhap),
                GiaNhap = VALUES(GiaNhap),
                NgayNhap = VALUES(NgayNhap),
                NhaCungCap = VALUES(NhaCungCap),
                HinhAnh = VALUES(HinhAnh),
                MoTa = VALUES(MoTa)
                """
                record_product = (ma_hang, ten_san_pham, loai_hang, ten_danh_muc, noi_san_xuat, so_luong_nhap, gia_nhap, ngay_nhap, nha_cung_cap, hinh_anh, mo_ta)
                cursor.execute(insert_or_update_product_query, record_product)

                # Chèn hoặc cập nhật bản ghi quản lý nhập hàng
                insert_or_update_quan_ly_nhap_hang_query = """
                INSERT INTO productsmanager (MaHang, SoLuongNhap, GiaNhap, NgayNhap, NhaCungCap)
                VALUES (%s, %s, %s, %s, %s)
                ON DUPLICATE KEY UPDATE
                SoLuongNhap = VALUES(SoLuongNhap),
                GiaNhap = VALUES(GiaNhap),
                NgayNhap = VALUES(NgayNhap),
                NhaCungCap = VALUES(NhaCungCap)
                """
                record_quan_ly_nhap_hang = (ma_hang, so_luong_nhap, gia_nhap, ngay_nhap, nha_cung_cap)
                cursor.execute(insert_or_update_quan_ly_nhap_hang_query, record_quan_ly_nhap_hang)

                connection.commit()
                print("Product and inventory record inserted or updated successfully")

            except Error as e:
                connection.rollback()
                print(f"Error during transaction: {e}")

    except Error as e:
        print(f"Error: {e}")
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()
            print("MySQL connection is closed")

# Thêm sản phẩm
products = [
    {'ma_hang': 'P001', 'ten_san_pham': 'LAPTOP ACER NITRO V ANV15-51-58AN (NH.QNASV.001) (I5-13420H/8GB RAM/512GB SSD/RTX2050 4GB/15.6 INCH FHD 144HZ/WIN11/ĐEN)', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Acer', 'noi_san_xuat': 'My', 'so_luong_nhap': 100, 'gia_nhap': 21499000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Acer', 'hinh_anh': '../uploads/acer.jpg', 'mo_ta': """
    CPU: Intel® Core™ i5-13420H
    RAM: 8GB SO-DIMM DDR5-5200MHz (Còn trống 1 khe, tối đa 32GB)
    Ổ cứng: 512GB PCIe NVMe SSD (Còn trống 1 khe) Tối đa 2TB
    VGA: NVIDIA® GeForce RTX™ 2050 4GB
    Màn hình: 15.6" FHD IPS 144Hz SlimBezel FHD(1920 x 1080) 45% NTSC Acer ComfyView™
    Màu: Đen
    Chất liệu : Nhựa
    OS: Windows 11 Home"""},
    {'ma_hang': 'P002', 'ten_san_pham': 'LAPTOP ACER PREDATOR HELIOS 16 PH16-71-72BV(I7 13700HX/16GB RAM/512GB SSD/RTX4070 8G/16.0 2K 240HZ/W', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Acer', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap': 52999000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Acer', 'hinh_anh': '../uploads/acer1.jpg', 'mo_ta': """
    CPU: Intel® Core™ i7-13700HX
    RAM: 16GB (2x 8GB) SO-DIMM DDR5-4800MHz (Tối đa 32GB)
    Ổ cứng: 512GB SSD M.2 2280 PCIe 4.0x4 NVMe (Tối đa 2TB) (Còn trống 1 khe)
    VGA: NVIDIA® GeForce RTX™ 4070 8GB GDDR6
    Màn hình: 16"WQXGA 240Hz DCI-P3 100% DDS,WQXGA (2560 x 1600) 500 nits,DCI-P3 100%,Acer ComfyView™ LED-backlit TFT LCD,Nvidia Advanced Optimus capable
    Chất liệu : Nhôm ( Mặt A ), nhựa
    Màu: Đen
    OS: Windows 11 Home"""},
    {'ma_hang': 'P003', 'ten_san_pham': 'LAPTOP ACER GAMING ASPIRE 7 A715-76G-59MW (NH.QMYSV.001) (I5 12450H/8GB RAM/512GB SSD/RTX2050 4G/15.', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Acer', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap': 20999000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Acer', 'hinh_anh': '../uploads/acer2.jpg', 'mo_ta': """
    CPU: Intel Core i5 12450H
    RAM: 8GB (1x 8GB) DDR4-3200MHz (2 khe) (Tối đa 32GB)
    Ổ cứng: 512GB SSD M.2 2280 PCIe NVMe
    VGA: NVIDIA GeForce RTX 2050 4GB
    Màn hình: 15.6 inch FHD IPS (1920 x 1080) Slim Benzel 144Hz; ComfyView IPS LED
    Màu: Đen
    Chất liệu: Nhôm
    OS: Windows 11 Home"""},
    {'ma_hang': 'P004', 'ten_san_pham': 'LAPTOP ACER GAMING NITRO 5 TIGER AN515-58-52SP (NH.QFHSV.001) (I5 12500H/8GB RAM/512GB SSD/RTX3050 4', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Acer', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap': 27999000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Acer', 'hinh_anh': '../uploads/acer3.jpg', 'mo_ta': """
CPU: Intel Core i5 12500H
RAM: 8GB
Ổ cứng: 512GB SSD
VGA: NVIDIA RTX3050 4G
Màn hình: 15.6 inch FHD 144Hz
Bàn phím: có đèn led
HĐH: Win 11
Màu: Đen"""},
    {'ma_hang': 'P005', 'ten_san_pham': 'LAPTOP ACER GAMING ASPIRE 5 A515-58GM-53PZ (NX.KQ4SV.008) (I5-13420H/8GB RAM/512GB SSD/RTX2050 4GB/1', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Acer', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap': 20499000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Acer', 'hinh_anh': '../uploads/acer4.jpg', 'mo_ta': """
CPU: Intel® Core™ i5-13420H
RAM: 8GB SO-DIMM DDR4-3200MHz (Nâng cấp tối đa 32GB)(Còn trống 1 khe)
Ổ cứng: 512GB PCIe NVMe SSD (Còn trống 1 khe) Tối đa 1TB
VGA: NVIDIA® GeForce® RTX™2050 4GB
Màn hình: 15.6" FHD IPS SlimBezel(1920x1080) 250nits, 60Hz,Acer ComfyView
Màu: Xám
Chất liệu : Mặt A Kim loại, Nhựa
OS: Windows 11"""},
    {'ma_hang': 'P006', 'ten_san_pham': 'LAPTOP ACER GAMING NITRO V 16 PROPANEL ANV16-41-R7EN (NH.QP2SV.004) (R7-8845HS/16GB RAM/512GB SSD/RT', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Acer', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap': 28999000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Acer', 'hinh_anh': '../uploads/acer5.jpg', 'mo_ta': """
CPU: AMD Ryzen R7-8845HS (16MB, Up to 5.10GHz)
RAM: 16GB DDR5 5600MHz (1x16GB))(Còn trống 1 khe, tối đa 32GB)
Ổ cứng: 512GB PCIe NVMe (Còn trống 1 khe)
VGA: NVIDIA GeForce RTX 3050 6GB GDDR6
Màn hình: 16.0Inch WUXGA IPS 165Hz 100%sRGB 300nits 16:10
Màu: Đen
OS: Windows 11"""},
    {'ma_hang': 'P007', 'ten_san_pham': 'LAPTOP ACER GAMING ASPIRE 7 A715-76G-5132 (NH.QMESV.002) (I5 12450H/8GB RAM/512GB SSD/GTX 1650 4G/15', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Acer', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap': 20999000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Acer', 'hinh_anh': '../uploads/acer6.jpg', 'mo_ta': ' P007'},
    {'ma_hang': 'P008', 'ten_san_pham': 'LAPTOP ACER GAMING PREDATOR HELIOS NEO PHN16-71-547E (NH.QLUSV.00A) (I5-13500HX /16GB RAM/512GB SSD/', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Acer', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap': 40999000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Acer', 'hinh_anh': '../uploads/acer7.jpg', 'mo_ta': """CPU: Intel Core i5-12450H
RAM: 8GB (2khe, tối đa 32GB)
Ổ cứng: 512GB PCIe NVMe
VGA: NVIDIA GTX 1650 4G
Màn hình: 15.6 inch FHD(1920 x 1080) IPS, 144Hz
Màu sắc: Đen
OS: Windows 11 Home"""},
    {'ma_hang': 'P009', 'ten_san_pham': 'LAPTOP ASUS GAMING TUF FA506NF-HN005W (R5 7535HS/8GB RAM/512GB SSD/15.6 FHD 144HZ/RTX2050 4GB/WIN11/', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Asus', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap': 19999000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Asus', 'hinh_anh': '../uploads/asus8 (2).jpg', 'mo_ta': """CPU: AMD Ryzen™ 5 7535HS
RAM: 8GB DDR5 4800MHz (2x SO-DIMM socket, tối đa 32 GB SDRAM)
Ổ cứng: 512GB PCIe® 4.0 NVMe™ M.2 SSD (Còn trống 1 khe SSD M.2 PCIE)
VGA: NVIDIA® GeForce RTX™ 2050 Laptop GPU
Màn hình: 15.6-inch FHD (1920 x 1080) 16:9, 144Hz, Value IPS-level, 250nits, 45% NTSC, 62.5% sRGB, Anti-glare display, Adaptive-sync
Màu: Đen
OS: Windows 11 Home"""},
    {'ma_hang': 'P010', 'ten_san_pham': 'LAPTOP ASUS GAMING TUF FX506HF-HN078W (I5 11260H/16GB RAM/512GB SSD/15.6 FHD 144HZ/RTX 2050 4GB/WIN1', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Asus', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap': 20999000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Asus', 'hinh_anh': './uploads/asus2.jpg', 'mo_ta': """CPU: Intel® Core™ i5 11260H
RAM: 16GB (8GBx2) DDR4 3200MHz (Có thể thay thế, tối đa 32 GB RAM)
Ổ cứng: 512GB PCIe® 3.0 NVMe™ M.2 SSD (Còn trống 1 khe SSD M.2 PCIE)
VGA: NVIDIA GeForce RTX 2050 4GB
Màn hình: 15.6" FHD (1920 x 1080) IPS, 144Hz, Wide View, 250nits, Narrow Bezel, Non-Glare with 72% NTSC, 100% sRGB
Màu: Đen
OS: Windows 11 Home"""},
    {'ma_hang': 'P011', 'ten_san_pham': 'LAPTOP ASUS GAMING TUF FA506NC-HN011W (R5 7535HS/8GB RAM/512GB SSD/15.6 FHD 144HZ/RTX3050 4GB/WIN11/', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Asus', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap': 21999000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Asus', 'hinh_anh': '../uploads/asus3.jpg', 'mo_ta': """CPU: AMD Ryzen™ 5 7535HS
RAM: 8GB DDR5 4800MHz (Còn trống 1 khe, tối đa 32 GB )
Ổ cứng: 512GB PCIe® 4.0 NVMe™ M.2 SSD (Còn trống 1 khe SSD M.2 PCIE)
VGA: NVIDIA® GeForce RTX™ 3050 4GB Laptop GPU
Màn hình: 15.6-inch FHD (1920 x 1080) 16:9, 144Hz, Value IPS-level, 250nits, 45% NTSC, 62.5% sRGB, Anti-glare display, Adaptive-Sync
Màu: Đen
OS: Windows 11 Home"""},
    {'ma_hang': 'P012', 'ten_san_pham': 'LAPTOP ASUS GAMING TUF FX507ZC4-HN074W (I5 12500H/8GB RAM/512GB SSD/15.6 FHD 144HZ/RTX 3050 4GB/WIN1', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Asus', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap': 18999000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Asus', 'hinh_anh': '../uploads/asus4.jpg', 'mo_ta': """CPU: Intel Core i5-12500H (3.30 GHz upto 4.50 GHz, 18MB)
RAM: 8GB (1x 8GB) DDR4-3200MHz (2 khe) (Tối đa 32GB)
Ổ cứng: 512GB SSD M.2 2280 PCIe 3.0x4 NVMe (Còn trống 1 khe)
VGA: NVIDIA GeForce RTX 3050 4GB GDDR6
Màn hình: 15.6" FHD (1920x1080) 144Hz, 62,5% sRGB
Màu: Xám
OS: Windows 11 Home"""},
    {'ma_hang': 'P013', 'ten_san_pham': 'LAPTOP ASUS GAMING TUF FA507NU-LP131W (R5 7535HS/16GB RAM/1TB SSD/15.6 FHD 144HZ/RTX 4050 6GB/WIN11/', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Asus', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap': 29499000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Asus', 'hinh_anh': '../uploads/asus5.jpg', 'mo_ta': """CPU: AMD Ryzen 5-7535H
RAM: 16GB DDR5 4800MHz SO-DIMM (tối đa 32GB)
Ổ cứng: 1TB PCIe 4.0 NVMe M.2 SSD (Còn trống 1 khe)
VGA: NVIDIA® GeForce RTX™ 4050 6GB GDDR6
Màn hình: 15.6" FHD(1920 x 1080) IPS,250 nits, sRGB 100%,NTSC 72%, anti-glare display
Màu: Xám
OS: Windows 11"""},
    {'ma_hang': 'P014', 'ten_san_pham': 'LAPTOP LENOVO GAMING LOQ 15IRX9 (83DV00D5VN) (NVIDIA GEFORCE RTX 4050 6GB/I7 13650HX/16GB RAM/512GB SSD/15.6 FHD 144HZ/WIN11/XÁM)', 'loai_hang': 'Laptop Gaming', 'ten_danh_muc': 'Lenovo', 'noi_san_xuat': 'My', 'so_luong_nhap': 50, 'gia_nhap':32999000.00, 'ngay_nhap': '2024-01-02', 'nha_cung_cap': 'Lenoo', 'hinh_anh': '../uploads/lenovo.jpg', 'mo_ta': """VGA: NVIDIA® GeForce RTX™ 4050 6GB GDDR6
CPU: Intel® Core™ i7-13650HX, 14C (6P + 8E)
RAM: 1x 16GB SO-DIMM DDR5-4800 (Còn trống 1 khe, tối đa 32GB)
Ổ cứng: 512GB SSD M.2 PCIe 4.0x4 NVMe (Còn trống 1 khe)
Màn hình: 15.6" FHD (1920x1080) IPS 300nits Anti-glare, 100% sRGB, 144Hz, G-SYNC®
Màu: Xám
Chất liệu : PC-ABS
OS: Windows 11"""},
    # Thêm các sản phẩm khác nếu cần
]

for product in products:
    insert_or_update_product(
        ma_hang=product['ma_hang'],
        ten_san_pham=product['ten_san_pham'],
        loai_hang=product['loai_hang'],
        ten_danh_muc=product['ten_danh_muc'],
        noi_san_xuat=product['noi_san_xuat'],
        so_luong_nhap=product['so_luong_nhap'],
        gia_nhap=product['gia_nhap'],
        ngay_nhap=product['ngay_nhap'],
        nha_cung_cap=product['nha_cung_cap'],
        hinh_anh=product['hinh_anh'],
        mo_ta=product['mo_ta']
    )



    