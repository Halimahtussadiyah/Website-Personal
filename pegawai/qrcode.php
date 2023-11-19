<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Exit Permit Dormitory Muka Kuning</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../vendor/fontawesome-free/css/fontawesome.css">
    <script src="../vendor/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="../vendor/sweetalert2/dist/sweetalert2.min.css">
    <?php
    include '../functions.php';
    ?>
    <style>
        * {
            color: black;
        }
    </style>
</head>

<body id="page-top">

    <?php
    // Cek apakah parameter 'id' ada dalam URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Query untuk mengambil data izin berdasarkan ID
        $query = "SELECT * FROM izin_pegawai WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
    ?>

            <center>
                <div class="container m-5">
                    <div class="card p-4" style="border: none;box-shadow:2px 10px 10px lightgrey;">
                        <div class="card-body">
                            <h3>Kartu Tanda Izin Pegawai</h3>
                            <hr>
                            <br>
                            <div class="row">
                                <div class="col-4">
                                    <i class="fas fa-user fa-8x"></i>
                                </div>
                                <div class="col-8">
                                    <p class="card-text">Tanggal Pengajuan: <?= $data['tanggal_pengajuan']; ?></p>
                                    <p class="card-text">Kategori: <?= $data['kategori']; ?></p>
                                    <p class="card-text">Jenis Izin: <?= $data['jenis_izin']; ?></p>
                                    <p class="card-text">Keterangan: <?= $data['keterangan']; ?></p>
                                    <div id="qrcode"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </center>

            <script src="../qrcodejs-master/qrcode.min.js"></script>
            <script>
                // Ambil URL tujuan untuk QR Code
                var url = 'https//' + window.location.hostname + 'pegawai/qrcode.php?id=<?= $data['id']; ?>';

                // Buat objek QRCode dengan pustaka qrcode.js
                var qrcode = new QRCode(document.getElementById("qrcode"), {
                    text: url,
                    width: 128,
                    height: 128
                });
            </script>
    <?php
        } else {
            echo "Data izin tidak ditemukan.";
        }
    } else {
        echo "Parameter 'id' tidak ditemukan dalam URL.";
    }
    ?>

    <?php include "plugin.php"; ?>
</body>

</html>