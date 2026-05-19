<?php
include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

// Proses Generate KIR
if(isset($_POST['generate'])) {
    $id_ruangan = mysqli_real_escape_string($koneksi, $_POST['id_ruangan']);
    $tanggal_cetak = date('Y-m-d');
    
    // Insert ke tabel kir
    $query = mysqli_query($koneksi, "INSERT INTO kir (id_ruangan, tanggal_cetak) VALUES ('$id_ruangan', '$tanggal_cetak')");
    
    if($query) {
        $id_kir = mysqli_insert_id($koneksi);
        
        // Insert detail aset ke detail_kir
        $aset_query = mysqli_query($koneksi, "SELECT id_aset, kondisi FROM aset WHERE id_ruangan = '$id_ruangan'");
        while($aset = mysqli_fetch_array($aset_query)) {
            mysqli_query($koneksi, "INSERT INTO detail_kir (id_kir, id_aset, kondisi_saat_cetak) 
                                    VALUES ('$id_kir', '{$aset['id_aset']}', '{$aset['kondisi']}')");
        }
        
        echo "<script>
                alert('Generate KIR sukses!');
                document.location='kir.php';
            </script>";
    } else {
        echo "<script>
                alert('Generate KIR gagal!');
                document.location='kir.php';
            </script>";
    }
}
?>