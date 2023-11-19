<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php
if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $departemen = mysqli_real_escape_string($conn, $_POST['departemen']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkQuery = "SELECT * FROM pegawai WHERE email = '$email'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Email sudah digunakan!',
                text: 'Mohon gunakan email lain.',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $query = "INSERT INTO pegawai (nama, jabatan, departemen, email, password) VALUES ('$nama', '$jabatan', '$departemen', '$email', '$hashedPassword')";
        if (mysqli_query($conn, $query)) {
            $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Data Berhasil Ditambahkan!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Data Gagal Ditambahkan!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        }
    }
}

if (isset($_POST['edit'])) {
    $id_pegawai = mysqli_real_escape_string($conn, $_POST['id_pegawai']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE pegawai SET nama = '$nama', jabatan = '$jabatan', password = '$hashedPassword' WHERE id = '$id_pegawai'";
    } else {
        $query = "UPDATE pegawai SET nama = '$nama', jabatan = '$jabatan' WHERE id = '$id_pegawai'";
    }

    if (mysqli_query($conn, $query)) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Data Berhasil di Edit!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Data Gagal Di-Edit!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    }
}

if (isset($_POST['hapus'])) {
    $id_pegawai = mysqli_real_escape_string($conn, $_POST['id_pegawai']);

    $query = "DELETE FROM pegawai WHERE id = '$id_pegawai'";
    if (mysqli_query($conn, $query)) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Data Berhasil Dihapus!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Data Gagal Di-Hapus!',
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
                    <div class="">
                        <p>
                            <a class="btn btn-info" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <i class="fas fa-plus-square"></i> Tambah Data pegawai
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="nama">Nama:</label>
                                        <input type="text" class="form-control" id="nama" name="nama" required>
                                    </div>
                                    <div class="form-group">
                                        <select name="jabatan" id="" class="form-control">
                                            <option value="" hidden>Pilih Jabatan</option>
                                            <option value="Staff">Staff</option>
                                            <option value="Supervisor">Operator</option>
                                            <option value="Head Departement">Head Departement</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select name="departemen" id="" class="form-control">
                                            <option value="" hidden>Pilih Departemen</option>
                                            <option value="IT">IT</option>
                                            <option value="Finance">Finance</option>
                                            <option value="Operation">Operation</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password:</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-info w-100">Submit</button>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-info">Data Pegawai</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Departemen</th>
                                            <th>Email</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $conn->prepare("SELECT * FROM pegawai");
                                        $stmt->execute();
                                        $pegawai = $stmt->get_result();
                                        ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($pegawai as $data) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($data['nama']); ?></td>
                                                <td><?= htmlspecialchars($data['jabatan']); ?></td>
                                                <td><?= htmlspecialchars($data['departemen']); ?></td>
                                                <td><?= htmlspecialchars($data['email']); ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal<?= $data['id'] ?>">Edit</a>
                                                    <br><br>
                                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal<?= $data['id'] ?>">Hapus</a>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Pegawai -->
                                            <div class="modal fade" id="editModal<?= $data['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Edit Data Pegawai</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST">
                                                                <input type="hidden" name="id_pegawai" value="<?= $data['id']; ?>">
                                                                <div class="form-group">
                                                                    <label for="nama">Nama:</label>
                                                                    <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <select name="jabatan" id="" class="form-control">
                                                                        <option value="<?= $data['jabatan']; ?>"><?= $data['jabatan']; ?> (Jabatan Saat Ini)</option>
                                                                        <option value="Staff">Staff</option>
                                                                        <option value="Supervisor">Supervisor</option>
                                                                        <option value="Head Departement">Head Departement</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <select name="departemen" id="" class="form-control">
                                                                        <option value="<?= $data['departemen']; ?>"><?= $data['departemen']; ?> (Departemen Saat Ini)</option>
                                                                        <option value="IT">IT</option>
                                                                        <option value="Finance">Finance</option>
                                                                        <option value="Operation">Operation</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="email">Email:</label>
                                                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($data['email']); ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="password">Password Baru (Opsional):</label>
                                                                    <input type="password" class="form-control" id="password" name="password">
                                                                </div>
                                                                <button type="submit" name="edit" class="btn btn-info w-100">Simpan</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Hapus Pegawai -->
                                            <div class="modal fade" id="hapusModal<?= $data['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus Data Pegawai</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus data pegawai dengan Nama: <b><?= htmlspecialchars($data['nama']) ?></b>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="id_pegawai" value="<?= $data['id'] ?>">
                                                                <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <?php $i++; ?>
                                        <?php endforeach; ?>
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