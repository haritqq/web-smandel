<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kartu Pelajar Digital</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <style>
    * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; }
    body { background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
    .container { background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); display: flex; flex-wrap: wrap; gap: 30px; max-width: 900px; padding: 30px; width: 100%; }
    .form-section, .card-section { flex: 1; min-width: 300px; }
    h2 { color: #1e3c72; margin-bottom: 20px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 14px; color: #333; }
    input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; }
    button { background: #1e3c72; color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 10px; }
    button:hover { background: #2a5298; }
    
    /* Desain Kartu Pelajar */
    .card-wrapper { display: flex; flex-direction: column; align-items: center; justify-content: center; }
    #student-card { width: 350px; height: 210px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-radius: 12px; color: white; padding: 15px; position: relative; box-shadow: 0 4px 10px rgba(0,0,0,0.2); overflow: hidden; }
    .card-header { text-align: center; border-bottom: 2px solid rgba(255,255,255,0.3); padding-bottom: 5px; margin-bottom: 10px; }
    .card-header h3 { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
    .card-header p { font-size: 10px; opacity: 0.8; }
    .card-body { display: flex; gap: 15px; align-items: center; }
    .photo-box { width: 80px; height: 100px; background: #fff; border-radius: 6px; overflow: hidden; border: 2px solid #fff; flex-shrink: 0; }
    .photo-box img { width: 100%; height: 100%; object-fit: cover; }
    .details { font-size: 12px; line-height: 1.6; }
    .details p span { font-weight: bold; }
    .card-footer { position: absolute; bottom: 8px; right: 15px; font-size: 9px; opacity: 0.6; }
  </style>
</head>
<body>

<div class="container">
  <div class="form-section">
    <h2>Data Siswa</h2>
    <form id="cardForm">
      <div class="form-group">
        <label for="inputNama">Nama Lengkap</label>
        <input type="text" id="inputNama" placeholder="Masukkan nama" value="Budi Santoso" oninput="updateCard()">
      </div>
      <div class="form-group">
        <label for="inputNISN">NISN</label>
        <input type="text" id="inputNISN" placeholder="Masukkan NISN" value="0012345678" oninput="updateCard()">
      </div>
      <div class="form-group">
        <label for="inputKelas">Kelas</label>
        <input type="text" id="inputKelas" placeholder="Contoh: X IPA 1" value="X IPA 1" oninput="updateCard()">
      </div>
      <div class="form-group">
        <label for="inputFoto">Foto Formal (Pasfoto)</label>
        <input type="file" id="inputFoto" accept="image/*" onchange="previewImage(event)">
      </div>
    </form>
  </div>

  <div class="card-section card-wrapper">
    <h2>Pratinjau Kartu</h2>
    <div id="student-card">
      <div class="card-header">
        <h3>SMA NEGERI 1 INDONESIA</h3>
        <p>KARTU TANDA PELAJAR DIGITAL</p>
      </div>
      <div class="card-body">
        <div class="photo-box">
          <img id="cardPhoto" src="https://via.placeholder.com/80x100?text=Foto" alt="Foto Siswa">
        </div>
        <div class="details">
          <p>Nama: <br><span id="cardNama">Budi Santoso</span></p>
          <p>NISN: <br><span id="cardNISN">0012345678</span></p>
          <p>Kelas: <br><span id="cardKelas">X IPA 1</span></p>
        </div>
      </div>
      <div class="card-footer">Berlaku Selama Menjadi Siswa</div>
    </div>
    <button onclick="downloadCard()">Unduh Kartu (PNG)</button>
  </div>
</div>

<script>
  // Fungsi untuk memperbarui teks di kartu secara instan
  function updateCard() {
    document.getElementById('cardNama').innerText = document.getElementById('inputNama').value || '-';
    document.getElementById('cardNISN').innerText = document.getElementById('inputNISN').value || '-';
    document.getElementById('cardKelas').innerText = document.getElementById('inputKelas').value || '-';
  }

  // Fungsi untuk memperbarui foto siswa
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
      const output = document.getElementById('cardPhoto');
      output.src = reader.result;
    };
    if (event.target.files[0]) {
      reader.readAsDataURL(event.target.files[0]);
    }
  }

  // Fungsi untuk mengunduh elemen kartu sebagai gambar PNG
  function downloadCard() {
    const cardElement = document.getElementById('student-card');
    html2canvas(cardElement, { scale: 2 }).then(canvas => {
      const link = document.createElement('a');
      link.download = 'Kartu_Pelajar_' + (document.getElementById('inputNama').value || 'Siswa') + '.png';
      link.href = canvas.toDataURL('image/png');
      link.click();
    });
  }
</script>

</body>
</html>