<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php
if (isset($_POST['submit'])) {
    $email = $_SESSION['email'];
    $tanggal_pengajuan = mysqli_real_escape_string($conn, $_POST['tanggal_pengajuan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $jenis_izin = mysqli_real_escape_string($conn, $_POST['jenis_izin']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $status_hrd = "Menunggu Konfirmasi";

    $pegawaiQuery = "SELECT id FROM pegawai WHERE email = '$email'";
    $result = mysqli_query($conn, $pegawaiQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $pegawai_id = $row['id'];

        $query = "INSERT INTO izin_pegawai (pegawai_id, tanggal_pengajuan, jenis_izin, keterangan, status_hrd)
              VALUES ('$pegawai_id', '$tanggal_pengajuan', '$jenis_izin', '$keterangan', '$status_hrd')";

        if (mysqli_query($conn, $query)) {
            $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Izin Berhasil Diajukan!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengajukan Izin!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        }
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Pegawai dengan Email Tidak Ditemukan!',
                text: 'Pastikan email Anda terdaftar dalam sistem.',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    }
}

?>



<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include "topbar.php"; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="mb-3">
                        <p>
                            <a class="btn btn-info" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <i class="fas fa-plus-square"></i> Ajukan Perizinan
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="tanggal_pengajuan">Tanggal Pengajuan:</label>
                                        <input type="date" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kategori">Kategori:</label>
                                        <select name="kategori" class="form-control" required>
                                            <option value="" disabled selected>Pilih Kategori Izin</option>
                                            <option value="Izin Masuk">Izin Masuk</option>
                                            <option value="Izin Keluar">Izin Keluar</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="jenis_izin">Jenis Izin:</label>
                                        <select name="jenis_izin" class="form-control" required>
                                            <option value="" disabled selected>Pilih Jenis Izin</option>
                                            <option value="Cuti">Cuti</option>
                                            <option value="Izin Sakit">Izin Sakit</option>
                                            <option value="Izin Terlambat">Izin Terlambat</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan:</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4" required></textarea>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-info w-100">Ajukan Izin</button>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-info">Riwayat Perizinan Saya</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Kategori</th>
                                            <th>Jenis Izin</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th>Waktu Konfirmasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $email = $_SESSION['email'];
                                        $izinQuery = "SELECT * FROM izin_pegawai WHERE pegawai_id = (SELECT id FROM pegawai WHERE email = '$email') ORDER BY id DESC";
                                        $result = mysqli_query($conn, $izinQuery);

                                        if ($result && mysqli_num_rows($result) > 0) {
                                            $i = 1;
                                            while ($data = mysqli_fetch_assoc($result)) {
                                        ?>
                                                <tr>
                                                    <td><?= $i; ?></td>
                                                    <td><?= $data['tanggal_pengajuan']; ?></td>
                                                    <td><?= $data['kategori']; ?></td>
                                                    <td><?= $data['jenis_izin']; ?></td>
                                                    <td><?= $data['keterangan']; ?></td>
                                                    <td><?= $data['status_hrd']; ?></td>
                                                    <td><?= $data['tanggal_verifikasi_hrd']; ?></td>
                                                    <td>
                                                        <a class="btn btn-info" href="qrcode.php?id=<?= $data['id']; ?>"><i class="fas fa-qrcode"></i> QR Code</a>
                                                    </td>
                                                </tr>
                                            <?php
                                                $i++;
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7">Belum ada riwayat perizinan.</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include "footer.php"; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "plugin.php"; ?>

    <script>
        $(document).ready(function() {
            $('#dataX').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sLast": "Terakhir",
                        "sNext": "Selanjutnya",
                        "sPrevious": "Sebelumnya"
                    },
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "sSearch": "Cari:",
                    "sEmptyTable": "Tidak ada data yang tersedia dalam tabel",
                    "sLengthMenu": "Tampilkan _MENU_ data",
                    "sZeroRecords": "Tidak ada data yang cocok dengan pencarian Anda"
                }
            });
        });
    </script>

    <script>
        <?php if (isset($script)) {
            echo $script;
        } ?>
    </script>
</body>

</html>