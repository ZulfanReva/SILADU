<?php
            $host = "localhost";
            $username = "root";
            $password = "";
            $database = "siladu2";
            $koneksi = mysqli_connect($host, $username, $password, $database);
            if (!$koneksi) {
                die("<script>alert('Gagal tersambung dengan database.')</script>");
            }
?>