<?php
    require_once "method.php";
    require_once "header.php";


    $datas = json_decode(file_get_contents('php://input'), true);

    if(isset($datas['nik'])) {
        $nik = $datas['nik'];
    }
    if(isset($datas['nama_lengkap'])) {
        $nama_lengkap = $datas['nama_lengkap'];
    }
    if(isset($datas['action'])) {
        $aksi = $datas['action'];
    }

    if($aksi) {
        $peduliDiri = new PeduliDiri();
        if($aksi == 'daftar') {
            $peduliDiri->daftar($nik, $nama_lengkap); 
        } elseif ($aksi == 'login') {
            $peduliDiri->login($nik, $nama_lengkap);
        } elseif ($aksi == 'getCatatan') {
            $peduliDiri->getData($nik);
        } elseif ($aksi == 'isiCatatan') {
            $peduliDiri->isiCatatan($nik, $datas['tanggal'], $datas['jam'], $datas['lokasi'], $datas['suhu']);
        } else {
            $peduliDiri->getDataSort($nik, $datas['sortBy']);
        }
    }

?>