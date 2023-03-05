<?php
    class PeduliDiri {

        // public
        public $filename = "./output/user.txt";
        // private
        public function daftar($nik, $nama_lengkap) {

            //check dulu apakah
            $file_handle = fopen($this->filename , 'r');
            $status = "";
            while (!feof($file_handle)) {
                $pisah = explode(" | ", fgets($file_handle));
                if ($pisah[0] == $nik) {
                    //nik yang sama tidak boleh daftar lagi
                    header('Content-Type: application/json');
                    $status = "NIK Sudah Terdaftar";
                    $data = [
                        'status' => $status
                    ];
                    echo json_encode($data);
                    return;
                }
            }

            $text = $nik . " | " . $nama_lengkap . "\n";
            $file_handle = fopen($this->filename , 'a+');
            fwrite($file_handle, $text);
            $status = "Berhasil Mendaftar";
            header('Content-Type: application/json');
            $data = [
                'status' => $status,
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap
            ];
            echo json_encode($data);
        }

        public function login($nik, $nama_lengkap) {
            $credential = $nik . " | " . $nama_lengkap;
            header('Contein-Type: application/json');
            

            //cara 2
            $file = file($this->filename, FILE_IGNORE_NEW_LINES);
            if(stripos(json_encode($file), $credential) !== false) {
                $data = [
                    'status' => 'success',
                    'nik' => $nik,
                    'nama_lengkap' => $nama_lengkap
                ];
                echo json_encode($data);
            } else {
                echo json_encode('error');
            }
        }
        public function IsiCatatan($nik, $tanggal, $jam, $lokasi, $suhu) {

            $checkFile = file_exists('./output/data-' . $nik . '.txt');

            //fungsi untuk simpan data
            function saveData($filename, $tanggal, $jam, $lokasi, $suhu) {
                //menggabungkan variable ke teks
                $text = $tanggal . " | " . $jam . " | " . $lokasi . " | " . $suhu . "\n";

                //simpan data teks ke file perjalanan
                $file_handle = fopen($filename, 'a+');
                fwrite($file_handle, $text);
                header('Content-Type: application/json');
                $data = [
                    'status' => 'success'
                ];
                echo json_encode($data);
            }
            if($checkFile) {
                $filename = './output/data-' . $nik . '.txt';
                saveData($filename, $tanggal, $jam, $lokasi, $suhu);
            } else {
                //file belum ada, kita buat dulu baru tulis
                $creatFile = fopen('./output/data-' . $nik . '.txt', 'w');
                $filename = './output/data-' . $nik . '.txt';
                saveData($filename, $tanggal, $jam, $lokasi, $suhu);
            }
        }
        public function getData($nik) {
            $file_handle = fopen('./output/data-' . $nik . '.txt', 'r');
            $datas = array();
            while (!feof($file_handle)) {
                $pisah = explode(" | ", fgets($file_handle));
                if ($pisah[0] != '') {
                    $clearDataEnd = str_replace("\r", "", $pisah[3]);
                    $clearDataFinal = str_replace("\n", "", $clearDataEnd);
                    $datas[] = [
                        'tanggal' => $pisah[0],
                        'waktu' => $pisah[1],
                        'lokasi' => $pisah[2],
                        'suhu' => $clearDataFinal
                    ];
                }
            }
            fclose($file_handle);
            echo json_encode($datas);
            return;
        }

        public function getDataSort($nik, $sort) {
            $file_handle = fopen('./output/data-' . $nik . '.txt', 'r');
            $datas = array();
            while (!feof($file_handle)) {
                $pisah = explode(" | ", fgets($file_handle));
                if ($pisah[0] != '') {
                    $clearDataEnd = str_replace("\r", "", $pisah[3]);
                    $clearDataFinal = str_replace("\n", "", $clearDataEnd);
                    $datas[] = [
                        'tanggal' => $pisah[0],
                        'waktu' => $pisah[1],
                        'lokasi' => $pisah[2],
                        'suhu' => $clearDataFinal
                    ];
                }
            }
            fclose($file_handle);

            usort($datas, function($a, $b) use($sort) {
                return $a[$sort] <=> $b[$sort];
            });

            echo json_encode($datas);
            return;
        }
    }
?>