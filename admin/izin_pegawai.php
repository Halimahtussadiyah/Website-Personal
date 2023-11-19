<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php
if (isset($_POST['edit'])) {
    $id_izin_pegawai = mysqli_real_escape_string($conn, $_POST['id_izin_pegawai']);
    $status_hrd = mysqli_real_escape_string($conn, $_POST['status_hrd']);
    $tanggal_verifikasi_hrd = date('Y-m-d H:i:s');

    $query = "UPDATE izin_pegawai SET status_hrd = '$status_hrd', tanggal_verifikasi_hrd = '$tanggal_verifikasi_hrd' WHERE id = '$id_izin_pegawai'";

    if (mysqli_query($conn, $query)) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Izin Berhasil Di Konfirmasi!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Data Gagal Di Konfirmasi!',
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

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-info">Riwayat Perizinan Pegawai</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
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
                                        $izinQuery = "SELECT izin_pegawai.*, pegawai.nama AS nama_pegawai, pegawai.jabatan AS jabatan_pegawai FROM izin_pegawai JOIN pegawai ON izin_pegawai.pegawai_id = pegawai.id ORDER BY ISNULL(tanggal_verifikasi_hrd) DESC, tanggal_verifikasi_hrd, id";
                                        $result = mysqli_query($conn, $izinQuery);

                                        if ($result && mysqli_num_rows($result) > 0) {
                                            $i = 1;
                                            while ($data = mysqli_fetch_assoc($result)) {
                                                $showKonfirmasiButton = is_null($data['tanggal_verifikasi_hrd']) ? true : false;
                                        ?>
                                                <tr>
                                                    <td><?= $i; ?></td>
                                                    <td><?= htmlspecialchars($data['nama_pegawai']); ?></td>
                                                    <td><?= htmlspecialchars($data['jabatan_pegawai']); ?></td>
                                                    <td><?= $data['tanggal_pengajuan']; ?></td>
                                                    <td><?= $data['kategori']; ?></td>
                                                    <td><?= $data['jenis_izin']; ?></td>
                                                    <td><?= $data['keterangan']; ?></td>
                                                    <td><?= $data['status_hrd']; ?></td>
                                                    <td><?= $data['tanggal_verifikasi_hrd']; ?></td>
                                                    <td>
                                                        <?php if ($showKonfirmasiButton) : ?>
                                                            <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal<?= $data['id'] ?>">Konfirmasi</a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <div class="modal fade" id="editModal<?= $data['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel">Konfirmasi Perizinan</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5>Detail Pegawai</h5>
                                                                <hr>
                                                                <p>Nama: <?= htmlspecialchars($data['nama_pegawai']); ?></p>
                                                                <p>Jabatan: <?= htmlspecialchars($data['jabatan_pegawai']); ?></p>
                                                                <p>Tanggal Pengajuan: <?= $data['tanggal_pengajuan']; ?></p>
                                                                <p>Kategori : <?= $data['kategori']; ?></p>
                                                                <p>Jenis Izin: <?= $data['jenis_izin']; ?></p>
                                                                <p>Keterangan: <?= $data['keterangan']; ?></p>
                                                                <hr>
                                                                <form action="" method="POST">
                                                                    <input type="hidden" name="id_izin_pegawai" value="<?= $data['id']; ?>">
                                                                    <div class="form-group">
                                                                        <select name="status_hrd" id="" class="form-control">
                                                                            <option value="" selected disabled>Pilih Status Konfirmasi</option>
                                                                            <option value="Diizinkan">Diizinkan</option>
                                                                            <option value="Ditolak">Ditolak</option>
                                                                        </select>
                                                                    </div>
                                                                    <button type="submit" name="edit" class="btn btn-info w-100">Simpan</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                                $i++;
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="9">Belum ada riwayat perizinan.</td>
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