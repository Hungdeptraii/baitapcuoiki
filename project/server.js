const express = require('express');
const { MongoClient } = require('mongodb');

const app = express();
const port = 3000;

// Kết nối đến MongoDB
const uri = 'mongodb://localhost:27017'; // Thay đổi URI nếu cần
const client = new MongoClient(uri, { useNewUrlParser: true, useUnifiedTopology: true });

let database;

client.connect(err => {
  if (err) throw err;
  database = client.db('mydatabase'); // Thay thế 'mydatabase' bằng tên database của bạn
  console.log('Kết nối MongoDB thành công!');
});

// Thiết lập route để lấy dữ liệu từ MongoDB
app.get('/data', async (req, res) => {
  try {
    const collection = database.collection('mycollection'); // Thay thế 'mycollection' bằng tên collection của bạn
    const data = await collection.find({}).toArray();
    res.json(data);
  } catch (err) {
    res.status(500).send(err);
  }
});

// Thiết lập server để phục vụ các tệp HTML
app.use(express.static('public'));

app.listen(port, () => {
  console.log(`Server chạy tại http://localhost:${port}`);
});
