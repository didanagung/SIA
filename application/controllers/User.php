<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class User extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(['url','form','sia','tgl_indo']);
        $this->load->library(['session','form_validation']);
        $this->load->model('Akun_model','akun',true);
        $this->load->model('Jurnal_model','jurnal',true);
        $this->load->model('JurnalPenyesuaian_model','jurnalPenyesuaian',true);
        $this->load->model('User_model','user',true);
        $login = $this->session->userdata('login');
        if(!$login){
            redirect('login');
        }
    }

    public function index(){
        $titleTag = 'Dashboard';
        $content = 'user/dashboard';
        $dataAkun = $this->akun->getAkun();
        $dataAkunTransaksi = $this->jurnal->getAkunInJurnal();
        
        foreach($dataAkunTransaksi as $row){
            $data[] = (array) $this->jurnal->getJurnalByNoReff($row->no_reff);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldo($row->no_reff);
        }
        $jumlah = count($data);

        $jurnals = $this->jurnal->getJurnalJoinAkun();
        $totalDebit = $this->jurnal->getTotalSaldo('debit');
        $totalKredit = $this->jurnal->getTotalSaldo('kredit');
        $this->load->view('template',compact('content','dataAkun','titleTag','jurnals','totalDebit','totalKredit','jumlah','data','saldo','dataAkunTransaksi'));
    }

    public function dataAkun(){
        if($this->session->userdata('role') != 'direktur') {
            show_404();
        }else {
            $content = 'user/data_akun';
            $titleTag = 'Data Akun';
            $dataAkun = $this->akun->getAkun();
            $this->load->view('template',compact('content','dataAkun','titleTag'));
        }
    }

    public function isNamaAkunThere($str){
        $namaAkun = $this->akun->countAkunByNama($str);
        if($namaAkun >= 1){
            $this->form_validation->set_message('isNamaAkunThere', 'Nama Akun Sudah Ada');
            return false;
        }
        return true;
    }

    public function isNoAkunThere($str){
        $noAkun = $this->akun->countAkunByNoReff($str);
        if($noAkun >= 1){
            $this->form_validation->set_message('isNoAkunThere', 'No.Reff Sudah Ada');
            return false;
        }
        return true;
    }

    public function createAkun(){
        if($this->session->userdata('role') != 'direktur') {
            show_404();
        }else {
            $title = 'Tambah';
            $titleTag = 'Data Akun';
            $action = 'data_akun/tambah';
            $content = 'user/form_akun';

            if(!$_POST){
                $data = (object) $this->akun->getDefaultValues();
            }else{
                $data = (object) $this->input->post(null,true);
                $data->id_user = $this->session->userdata('id');
            }

            if(!$this->akun->validate()){
                $this->load->view('template',compact('content','title','action','data','titleTag'));
                return;
            }
            
            $this->akun->insertAkun($data);
            $this->session->set_flashdata('berhasil','Data Akun Berhasil Di Tambahkan');
            redirect('data_akun');
        }
    }

    public function editAkun($no_reff = null){
        if($this->session->userdata('role') != 'direktur') {
            show_404();
        }else {
            $title = 'Edit';
            $titleTag = 'Data Akun';
            $action = 'data_akun/edit/'.$no_reff;
            $content = 'user/form_akun';

            if(!$_POST){
                $data = (object) $this->akun->getAkunByNo($no_reff);
            }else{
                $data = (object) $this->input->post(null,true);
                $data->id_user = $this->session->userdata('id');
            }

            if(!$this->akun->validate()){
                $this->load->view('template',compact('content','title','action','data','titleTag'));
                return;
            }
            
            $this->akun->updateAkun($no_reff,$data);
            $this->session->set_flashdata('berhasil','Data Akun Berhasil Di Ubah');
            redirect('data_akun');
        }
    }

    public function deleteAkun(){
        if($this->session->userdata('role') != 'direktur') {
            show_404();
        }else {
            $id = $this->input->post('id',true);
            $noReffTransaksi = $this->jurnal->countJurnalNoReff($id);
            if($noReffTransaksi >= 0 ){
                $this->session->set_flashdata('dataNull','No.Reff '.$id.' Tidak Bisa Di Hapus Karena Data Akun Ada Di Jurnal Umum');
                redirect('data_akun');
            }
            $this->akun->deleteAkun($id);
            $this->session->set_flashdata('berhasilHapus','Data akun dengan No.Reff '.$id.' berhasil di hapus');
            redirect('data_akun');
        }
    }

    public function jurnalUmum(){
        $titleTag = 'Jurnal Umum';
        $content = 'user/jurnal_umum_main';
        $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
        $tahun = $this->jurnal->getJurnalByYear();
        $this->load->view('template',compact('content','listJurnal','titleTag','tahun'));
    }

    public function jurnalUmumDetail(){
        $content = 'user/jurnal_umum';
        $titleTag = 'Jurnal Umum';

        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);
        $jurnals = null;

        if(empty($bulan) || empty($tahun)){
            redirect('jurnal_umum');
        }

        $jurnals = $this->jurnal->getJurnalJoinAkunDetail($bulan,$tahun);
        $totalDebit = $this->jurnal->getTotalSaldoDetail('debit',$bulan,$tahun);
        $totalKredit = $this->jurnal->getTotalSaldoDetail('kredit',$bulan,$tahun);

        if($jurnals==null){
            $this->session->set_flashdata('dataNull','Data Jurnal Dengan Bulan '.bulan($bulan).' Pada Tahun '.date('Y',strtotime($tahun)).' Tidak Di Temukan');
            redirect('jurnal_umum');
        }

        $this->load->view('template',compact('content','jurnals','totalDebit','totalKredit','titleTag'));
    }

    public function createJurnal(){
        $title = 'Tambah'; 
        $content = 'user/form_jurnal'; 
        $action = 'jurnal_umum/tambah'; 
        $tgl_input = date('Y-m-d H:i:s'); 
        $id_user = $this->session->userdata('id'); 
        $titleTag = 'Jurnal Umum';

        if(!$_POST){
            $data = (object) $this->jurnal->getDefaultValues();
        }else{
            $data = (object) [
                'id_user'=>$id_user,
                'no_reff'=>$this->input->post('no_reff',true),
                'tgl_input'=>$tgl_input,
                'tgl_transaksi'=>$this->input->post('tgl_transaksi',true),
                'jenis_saldo'=>$this->input->post('jenis_saldo',true),
                'saldo'=>$this->input->post('saldo',true)
            ];
        }

        if(!$this->jurnal->validate()){
            $this->load->view('template',compact('content','title','action','data','titleTag'));
            return;
        }
        
        $this->jurnal->insertJurnal($data);
        $this->session->set_flashdata('berhasil','Data Jurnal Berhasil Di Tambahkan');
        redirect('jurnal_umum');    
    }

    public function editForm(){
        if($_POST){
            $id = $this->input->post('id',true);
            $title = 'Edit'; $content = 'user/form_jurnal'; $action = 'jurnal_umum/edit'; $titleTag = 'Edit Jurnal Umum';

            $data = (object) $this->jurnal->getJurnalById($id);

            $this->load->view('template',compact('content','title','action','data','id','titleTag'));
        }else{
            redirect('jurnal_umum');
        }
    }

    public function editJurnal(){
        $title = 'Edit'; 
        $content = 'user/form_jurnal'; 
        $action = 'jurnal_umum/edit'; 
        $tgl_input = date('Y-m-d H:i:s'); 
        $id_user = $this->session->userdata('id'); 
        $titleTag = 'Jurnal Umum';

        if($_POST){
            $data = (object) [
                'id_user'=>$id_user,
                'no_reff'=>$this->input->post('no_reff',true),
                'tgl_input'=>$tgl_input,
                'tgl_transaksi'=>$this->input->post('tgl_transaksi',true),
                'jenis_saldo'=>$this->input->post('jenis_saldo',true),
                'saldo'=>$this->input->post('saldo',true)
            ];
            $id = $this->input->post('id',true);
        }

        if(!$this->jurnal->validate()){
            $this->load->view('template',compact('content','title','action','data','id','titleTag'));
            return;
        }
        
        $this->jurnal->updateJurnal($id,$data);
        $this->session->set_flashdata('berhasil','Data Jurnal Berhasil Di Ubah');
        redirect('jurnal_umum');    
    }

    public function deleteJurnal(){
        $id = $this->input->post('id',true);
        $this->jurnal->deleteJurnal($id);
        $this->session->set_flashdata('berhasilHapus','Data Jurnal berhasil di hapus');
        redirect('jurnal_umum');
    }

    public function bukuBesar(){
        $titleTag = 'Buku Besar';
        $content = 'user/buku_besar_main';
        $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
        $tahun = $this->jurnal->getJurnalByYear();
        $this->load->view('template',compact('content','listJurnal','titleTag','tahun'));
    }

    public function bukuBesarDetail(){
        $content = 'user/buku_besar';
        $titleTag = 'Buku Besar';
        
        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);

        if(empty($bulan) ||empty($tahun)){
            redirect('buku_besar');
        }
        
        $dataAkun = $this->akun->getAkunByMonthYear($bulan,$tahun);
        $data = null;
        $saldo = null;

        foreach($dataAkun as $row){
            $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYear($row->no_reff,$bulan,$tahun);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYear($row->no_reff,$bulan,$tahun);
        }

        if($data == null || $saldo == null){
            $this->session->set_flashdata('dataNull','Data Buku Besar Dengan Bulan '.bulan($bulan).' Pada Tahun '.date('Y',strtotime($tahun)).' Tidak Di Temukan');
            redirect('buku_besar');
        }

        $jumlah = count($data);

        $this->load->view('template',compact('content','titleTag','dataAkun','data','jumlah','saldo'));
    }

    public function neracaSaldo(){
            $titleTag = 'Neraca Saldo';
            $content = 'user/neraca_saldo_main';
            $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
            $tahun = $this->jurnal->getJurnalByYear();
            $this->load->view('template',compact('content','listJurnal','titleTag','tahun'));
        }

    public function neracaSaldoDetail(){
        $content = 'user/neraca_saldo';
        $titleTag = 'Neraca Saldo';

        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);

        if(empty($bulan) || empty($tahun)){
            redirect('neraca_saldo');
        }

        $dataAkun = $this->akun->getAkunByMonthYear($bulan,$tahun);
        $data = null;
        $saldo = null;
        
        foreach($dataAkun as $row){
            $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYear($row->no_reff,$bulan,$tahun);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYear($row->no_reff,$bulan,$tahun);
        }

        if($data == null || $saldo == null){
            $this->session->set_flashdata('dataNull','Neraca Saldo Dengan Bulan '.bulan($bulan).' Pada Tahun '.date('Y',strtotime($tahun)).' Tidak Di Temukan');
            redirect('neraca_saldo');
        }

        $jumlah = count($data);

        $this->load->view('template',compact('content','titleTag','dataAkun','data','jumlah','saldo'));
    }

    public function jurnalPenyesuaian(){
        $titleTag = 'Jurnal Penyesuaian';
        $content = 'user/jurnal_penyesuaian_main';
        $listJurnal = $this->jurnalPenyesuaian->getJurnalByYearAndMonth();
        $tahun = $this->jurnalPenyesuaian->getJurnalByYear();
        $this->load->view('template',compact('content','listJurnal','titleTag','tahun'));
    }

    public function jurnalPenyesuaianDetail(){
        $content = 'user/jurnal_penyesuaian';
        $titleTag = 'Jurnal Penyesuaian';
        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);
        $jurnals = null;

        if(empty($bulan) || empty($tahun)){
            redirect('jurnal_penyesuaian');
        }

        $jurnals = $this->jurnalPenyesuaian->getJurnalJoinAkunDetail($bulan,$tahun);
        $totalDebit = $this->jurnalPenyesuaian->getTotalSaldoDetail('debit',$bulan,$tahun);
        $totalKredit = $this->jurnalPenyesuaian->getTotalSaldoDetail('kredit',$bulan,$tahun);

        if($jurnals==null){
            $this->session->set_flashdata('dataNull','Data Jurnal Dengan Bulan '.bulan($bulan).' Pada Tahun '.date('Y',strtotime($tahun)).' Tidak Di Temukan');
            redirect('jurnal_penyesuaian');
        }

        $this->load->view('template',compact('content','jurnals','totalDebit','totalKredit','titleTag'));
    }

    public function createJurnalPenyesuaian(){
        $title = 'Tambah Jurnal Penyesuaian'; 
        $content = 'user/form_jurnal_penyesuaian'; 
        $action = 'jurnal_penyesuaian/tambah'; 
        $tgl_input = date('Y-m-d H:i:s'); 
        $id_user = $this->session->userdata('id'); 
        $titleTag = 'Jurnal Penyesuaian';
        $jurnals = $this->jurnal->getJurnalJoinAkun();

        if(!$_POST){
            $data = (object) $this->jurnalPenyesuaian->getDefaultValues();
        }else{
            $data = (object) [
                'id_user'=>$id_user,
                'no_reff'=>$this->input->post('no_reff',true),
                'tgl_input'=>$tgl_input,
                'tgl_penyesuaian'=>$this->input->post('tgl_penyesuaian',true),
                'id_transaksi'=>$this->input->post('akun',true),
                'jenis_saldo'=>$this->input->post('jenis_saldo',true),
                'saldo'=>$this->input->post('saldo',true)
            ];

        }

        if(!$this->jurnalPenyesuaian->validate()){
            $this->load->view('template',compact('content','title','action','data','titleTag', 'jurnals'));
            return;
        }
        
        $this->jurnalPenyesuaian->insertJurnal($data);
        $this->session->set_flashdata('berhasil','Data Jurnal Penyesuaian Berhasil Di Tambahkan');
        redirect('jurnal_penyesuaian');    
    }

    public function editFormJPenyesuaian(){
        if($_POST){
            $id = $this->input->post('id',true);
            $title = 'Edit'; 
            $content = 'user/form_jurnal_penyesuaian'; 
            $action = 'jurnal_penyesuaian/edit'; 
            $titleTag = 'Jurnal Penyesuaian';
            $jurnals = $this->jurnal->getJurnalJoinAkun();

            $data = (object) $this->jurnalPenyesuaian->getJurnalById($id);

            $this->load->view('template',compact('content','title','action','data','id','titleTag', 'jurnals'));
        }else{
            redirect('jurnal_penyesuaian');
        }
    }

    public function editJurnalPenyesuaian(){
        $title = 'Edit';
        $content = 'user/form_jurnal_penyesuaian'; 
        $action = 'jurnal_penyesuaian/edit'; 
        $tgl_input = date('Y-m-d H:i:s'); 
        $id_user = $this->session->userdata('id'); 
        $titleTag = 'Edit Jurnal Penyesuaian';

        if($_POST){
            $data = (object) [
                'id_user'=>$id_user,
                'no_reff'=>$this->input->post('no_reff',true),
                'tgl_input'=>$tgl_input,
                'tgl_penyesuaian'=>$this->input->post('tgl_penyesuaian',true),
                'id_transaksi'=>$this->input->post('akun',true),
                'jenis_saldo'=>$this->input->post('jenis_saldo',true),
                'saldo'=>$this->input->post('saldo',true)
            ];
            $id = $this->input->post('id',true);
        }

        if(!$this->jurnalPenyesuaian->validate()){
            $this->load->view('template',compact('content','title','action','data','id','titleTag'));
            return;
        }
        
        $this->jurnalPenyesuaian->updateJurnal($id,$data);
        $this->session->set_flashdata('berhasil','Data Jurnal Penyesuaian Berhasil Di Ubah');
        redirect('jurnal_penyesuaian');    
    }

    public function deleteJurnalPenyesuaian(){
        $id = $this->input->post('id',true);
        $this->jurnalPenyesuaian->deleteJurnalPenyesuaian($id);
        $this->session->set_flashdata('berhasilHapus','Data Jurnal Penyesuaian berhasil di hapus');
        redirect('jurnal_penyesuaian');
    }

    public function laporanKeuangan(){
        if($this->session->userdata('role') != 'direktur') {
            show_404();
        }else {
            $titleTag = 'Laporan Keuangan';
            $content = 'user/laporan_keuangan';
            $this->load->view('template',compact('content','titleTag'));
        }
    }

    public function laporanKeuanganLabaRugi() {
        if($this->session->userdata('role') != 'direktur') {
            show_404();
        }else {
            $titleTag = 'Laporan Keuangan';
            $content = 'user/laporan_keuangan_laba_rugi_main';
            $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
            $tahun = $this->jurnal->getJurnalByYear();
            $this->load->view('template',compact('content','listJurnal','titleTag','tahun'));
        }
    }

    public function laporanKeuanganLabaRugiDetail(){
        if($this->session->userdata('role') != 'direktur') {
            show_404();
        }else {
            $content = 'user/laporan_keuangan_laba_rugi';
            $titleTag = 'Laporan Keuangan';

            $bulan = $this->input->post('bulan',true);
            $tahun = $this->input->post('tahun',true);

            if(empty($bulan) || empty($tahun)){
                redirect('laporan_keuangan/labaRugi');
            }

            $dataAkunP = $this->akun->getAkunByMonthYearP($bulan,$tahun);
            $dataAkunB = $this->akun->getAkunByMonthYearB($bulan,$tahun);
            $dataP = null;
            $dataB = null;
            $saldoP = null;
            $hasil = null;
            $totalP = null;
            $totalB = null;
            $s = null;
            
            foreach($dataAkunP as $row){
                $dataP[] = (array) $this->jurnal->getJurnalByNoReffMonthYearP($row->no_reff,$bulan,$tahun);
                $saldoP[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearP($row->no_reff,$bulan,$tahun);
            }

            foreach($dataAkunB as $row){
                $dataB[] = (array) $this->jurnal->getJurnalByNoReffMonthYearB($row->no_reff,$bulan,$tahun);
                $saldoB[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearB($row->no_reff,$bulan,$tahun);
            }

            if($dataP == null || $saldoP == null || $saldoB == null || $dataB == null){
                $this->session->set_flashdata('dataNull','Laporan Keuangan Laba / Rugi Dengan Bulan '.bulan($bulan).' Pada Tahun '.$tahun.' Tidak Di Temukan');
                redirect('laporan_keuangan/labaRugi');
            }

            $jumlahP = count($dataP);
            $jumlahB = count($dataB);

            $this->load->view('template',compact('content','titleTag','dataAkunP','dataAkunB','dataP','dataB','jumlahP','jumlahB','saldoP','saldoB','hasil', 'totalP', 'totalB', 's', 'bulan', 'tahun'));
        }
    }

    public function excelLaporanLabaRugi()
    {
        if($this->session->userdata('role') != 'direktur') {
            show_404();
        }else {
            $bulan = $this->input->post('bulan',true);
            $tahun = $this->input->post('tahun',true);

            $dataAkunP = $this->akun->getAkunByMonthYearP($bulan,$tahun);
            $dataAkunB = $this->akun->getAkunByMonthYearB($bulan,$tahun);

            foreach($dataAkunP as $row){
                $dataP[] = (array) $this->jurnal->getJurnalByNoReffMonthYearP($row->no_reff,$bulan,$tahun);
                $saldoP[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearP($row->no_reff,$bulan,$tahun);
            }

            foreach($dataAkunB as $row){
                $dataB[] = (array) $this->jurnal->getJurnalByNoReffMonthYearB($row->no_reff,$bulan,$tahun);
                $saldoB[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearB($row->no_reff,$bulan,$tahun);
            }

            $jumlahP = count($dataP);
            $jumlahB = count($dataB);
            
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(12);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getColumnDimension('A')->setWidth(20);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(12);
            $sheet->getColumnDimension('D')->setWidth(12);
            // $sheet->getStyle('C:D')->getNumberFormat('Accounting');

            $sheet->setCellValue('A1', 'PT. Mitra Sejati Konsultan');
            $sheet->setCellValue('A2', 'Laporan Laba/Rugi');
            $sheet->setCellValue('A3', 'Per '. bulan($bulan). " Tahun $tahun");
            $sheet->mergeCells('A1:D1');
            $sheet->mergeCells('A2:D2');
            $sheet->mergeCells('A3:D3');
            $sheet->getStyle('A1:D3')->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A1:D3')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14
                ]
            ]);

            $sheet->setCellValue('A5', 'Pendapatan');
            $sheet->getStyle('A5')->applyFromArray([
                'font' => ['bold' => true]
            ]);
            $x = 6;
            $totalP = 0;
            $totalB = 0;
            $debitP = 0;
            $debitB = 0;
            $kreditP = 0;
            $kreditB = 0;
            
            for($i=0;$i<$jumlahP;$i++) { 
                $s=0;
                $deb = $saldoP[$i];
                $sheet->setCellValue('A'.$x, $dataP[$i][$s]->nama_reff);
                for($j=0;$j<count($dataP[$i]);$j++) {
                    $kreditP = $kreditP + $deb[$j]->saldo;
                    $hasilP = $kreditP-$debitP;
                }
                $sheet->setCellValue('C'.$x, $hasilP);
                $totalP += $hasilP;
                $debitP = 0;
                $kreditP = 0;
                $x++;
            }
            $x--;

            $sheet->getStyle('C'. $x)->applyFromArray([
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ]);

            $x++;

            $sheet->getStyle("A$x:D$x")->applyFromArray([
                'font' => ['bold' => true]
            ]);
            $sheet->setCellValue('B'.$x, 'Total Pendapatan');
            $sheet->setCellValue('D'.$x, $totalP);
            $sheet->getStyle('D'. $x)->applyFromArray([
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ]);
            $x++;
            $x++;
            $sheet->setCellValue("A$x", 'Beban');
            $sheet->getStyle("A$x")->applyFromArray([
                'font' => ['bold' => true]
            ]);        
            $x++;

            for($i=0;$i<$jumlahB;$i++) { 
                $s=0;
                $deb = $saldoB[$i];
                $sheet->setCellValue('A'.$x, $dataB[$i][$s]->nama_reff);
                for($j=0;$j<count($dataB[$i]);$j++) {
                    $kreditB = $kreditB + $deb[$j]->saldo;
                    $hasilB = $kreditB-$debitB;
                }
                $sheet->setCellValue('C'.$x, $hasilB);
                $totalB += $hasilB;
                $debitB = 0;
                $kreditB = 0;
                $x++;
            }

            $x--;

            $sheet->getStyle('C'. $x)->applyFromArray([
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ]);

            $x++;

            $sheet->getStyle("A$x:D$x")->applyFromArray([
                'font' => ['bold' => true]
            ]);
            $sheet->setCellValue('B'.$x, 'Total Beban');
            $sheet->setCellValue('D'.$x, $totalB);
            $sheet->getStyle('D'. $x)->applyFromArray([
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ]);
            $x++;
            $sheet->getStyle("A$x:D$x")->applyFromArray([
                'font' => ['bold' => true]
            ]);
            $sheet->setCellValue('B'.$x, ($totalP - $totalB < 0) ? 'Beban' : 'Laba Bersih');
            $sheet->setCellValue('D'.$x, $totalP - $totalB);
            $sheet->getStyle("A5:D$x")->applyFromArray([
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ]);

            $writer = new Xlsx($spreadsheet);
            $filename = 'Laporan Laba / Rugi '.bulan($bulan).' '.$tahun;
            
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
            header('Cache-Control: max-age=0');
        
            $writer->save('php://output');
        }
    }

    public function laporanKeuanganPerubahanModal() {
        if($this->session->userdata('role') != 'direktur') {
            show_404();
        }else {
            $titleTag = 'Laporan Keuangan';
            $content = 'user/laporan_keuangan_perubahan_modal_main';
            $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
            $tahun = $this->jurnal->getJurnalByYear();
            $this->load->view('template',compact('content','listJurnal','titleTag','tahun'));
        }
    }

    public function laporanKeuanganPerubahanModalDetail() {
        if($this->session->userdata('role') != 'direktur') {
            show_404();
        }else {
            $content = 'user/laporan_keuangan_perubahan_modal';
            $titleTag = 'Laporan Keuangan';

            $bulan = $this->input->post('bulan',true);
            $tahun = $this->input->post('tahun',true);

            if(empty($bulan) || empty($tahun)){
                redirect('laporan_keuangan/perubahanModal');
            }

            $dataAkunM = $this->akun->getAkunByMonthYearM($bulan,$tahun);
            $dataAkunP = $this->akun->getAkunByMonthYearP($bulan,$tahun);
            $dataAkunPr = $this->akun->getAkunByMonthYearPr($bulan,$tahun);
            $dataAkunB = $this->akun->getAkunByMonthYearB($bulan,$tahun);
            $dataM = null;
            $dataP = null;
            $dataPr = null;
            $dataB = null;
            $saldoM = null;
            $saldoP = null;
            $saldoPr = null;
            $saldoB = null;
            $hasil = null;
            $totalM = null;
            $totalP = null;
            $totalPr = null;
            $totalB = null;
            $s = null;
            
            foreach($dataAkunM as $row){
                $dataM[] = (array) $this->jurnal->getJurnalByNoReffMonthYearM($row->no_reff,$bulan,$tahun);
                $saldoM[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearM($row->no_reff,$bulan,$tahun);
            }

            foreach($dataAkunP as $row){
                $dataP[] = (array) $this->jurnal->getJurnalByNoReffMonthYearP($row->no_reff,$bulan,$tahun);
                $saldoP[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearP($row->no_reff,$bulan,$tahun);
            }

            foreach($dataAkunPr as $row){
                $dataPr[] = (array) $this->jurnal->getJurnalByNoReffMonthYearPr($row->no_reff,$bulan,$tahun);
                $saldoPr[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearPr($row->no_reff,$bulan,$tahun);
            }

            foreach($dataAkunB as $row){
                $dataB[] = (array) $this->jurnal->getJurnalByNoReffMonthYearB($row->no_reff,$bulan,$tahun);
                $saldoB[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearB($row->no_reff,$bulan,$tahun);
            }

            if($dataP == null || $saldoP == null ||$dataPr == null || $saldoPr == null || $saldoB == null || $dataB == null || $saldoM == null || $dataM == null){
                $this->session->set_flashdata('dataNull','Laporan Perubahan Modal Dengan Bulan '.bulan($bulan).' Pada Tahun '.$tahun.' Tidak Di Temukan');
                redirect('laporan_keuangan/perubahanModal');
            }

            $jumlahM = count($dataM);
            $jumlahP = count($dataP);
            $jumlahPr = count($dataPr);
            $jumlahB = count($dataB);

            $this->load->view('template',compact('content','titleTag', 'dataAkunM' ,'dataAkunP', 'dataAkunPr' ,'dataAkunB', 'dataM' ,'dataP', 'dataPr' ,'dataB', 'jumlahM' ,'jumlahP', 'jumlahPr' ,'jumlahB', 'saldoM' ,'saldoP', 'saldoPr' ,'saldoB','hasil', 'totalM' , 'totalP', 'totalPr' , 'totalB', 's', 'bulan', 'tahun'));
        }
    }

        
    public function excelLaporanPerubahanModal()
    {
        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);

        if(empty($bulan) || empty($tahun)){
            redirect('laporan_keuangan/perubahanModal');
        }

        $dataAkunM = $this->akun->getAkunByMonthYearM($bulan,$tahun);
        $dataAkunP = $this->akun->getAkunByMonthYearP($bulan,$tahun);
        $dataAkunPr = $this->akun->getAkunByMonthYearPr($bulan,$tahun);
        $dataAkunB = $this->akun->getAkunByMonthYearB($bulan,$tahun);
        $dataM = null;
        $dataP = null;
        $dataPr = null;
        $dataB = null;
        $saldoM = null;
        $saldoP = null;
        $saldoPr = null;
        $saldoB = null;
        $hasil = null;
        $totalM = null;
        $totalP = null;
        $totalPr = null;
        $totalB = null;
        $x = 4;
        
        foreach($dataAkunM as $row){
            $dataM[] = (array) $this->jurnal->getJurnalByNoReffMonthYearM($row->no_reff,$bulan,$tahun);
            $saldoM[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearM($row->no_reff,$bulan,$tahun);
        }

        foreach($dataAkunP as $row){
            $dataP[] = (array) $this->jurnal->getJurnalByNoReffMonthYearP($row->no_reff,$bulan,$tahun);
            $saldoP[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearP($row->no_reff,$bulan,$tahun);
        }

        foreach($dataAkunPr as $row){
            $dataPr[] = (array) $this->jurnal->getJurnalByNoReffMonthYearPr($row->no_reff,$bulan,$tahun);
            $saldoPr[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearPr($row->no_reff,$bulan,$tahun);
        }

        foreach($dataAkunB as $row){
            $dataB[] = (array) $this->jurnal->getJurnalByNoReffMonthYearB($row->no_reff,$bulan,$tahun);
            $saldoB[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearB($row->no_reff,$bulan,$tahun);
        }

        if($dataP == null || $saldoP == null ||$dataPr == null || $saldoPr == null || $saldoB == null || $dataB == null || $saldoM == null || $dataM == null){
            $this->session->set_flashdata('dataNull','Laporan Perubahan Modal Dengan Bulan '.bulan($bulan).' Pada Tahun '.$tahun.' Tidak Di Temukan');
            redirect('laporan_keuangan/perubahanModal');
        }

        $jumlahM = count($dataM);
        $jumlahP = count($dataP);
        $jumlahPr = count($dataPr);
        $jumlahB = count($dataB);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(12);

        $sheet->setCellValue('A1', 'Laporan Perubahan Modal Bulan '. bulan($bulan). ' Tahun '. $tahun);
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16
            ]
        ]);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan Perubahan Modal '.bulan($bulan).' '.$tahun;
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
    }

    public function laporanKeuanganNeraca() {
        if($this->session->userdata('role') != 'direktur') {
            show_404();
        }else {
            $titleTag = 'Laporan Keuangan';
            $content = 'user/laporan_keuangan_neraca_main';
            $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
            $tahun = $this->jurnal->getJurnalByYear();
            $this->load->view('template',compact('content','listJurnal','titleTag','tahun'));
        }
    }

    public function laporanKeuanganNeracaDetail() {
        $content = 'user/laporan_keuangan_neraca';
        $titleTag = 'Laporan Keuangan';

        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);

        if(empty($bulan) || empty($tahun)){
            redirect('laporan_keuangan/neraca');
        }

        $dataAkunA = $this->akun->getAkunByMonthYearA($bulan,$tahun);
        $dataAkunAt = $this->akun->getAkunByMonthYearAt($bulan,$tahun);
        $dataAkunU = $this->akun->getAkunByMonthYearU($bulan,$tahun);
        $dataAkunMp = $this->akun->getAkunByMonthYearMp($bulan,$tahun);
        $dataA = null;
        $dataAt = null;
        $dataU = null;
        $dataMp = null;
        $saldoA = null;
        $saldoAt = null;
        $saldoU = null;
        $saldoMp = null;
        $hasil = null;
        $totalA = null;
        $totalAt = null;
        $totalU = null;
        $totalMp = null;
        $s = null;
        
        foreach($dataAkunA as $row){
            $dataA[] = (array) $this->jurnal->getJurnalByNoReffMonthYearA($row->no_reff,$bulan,$tahun);
            $saldoA[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearA($row->no_reff,$bulan,$tahun);
        }

        foreach($dataAkunAt as $row){
            $dataAt[] = (array) $this->jurnal->getJurnalByNoReffMonthYearAt($row->no_reff,$bulan,$tahun);
            $saldoAt[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearAt($row->no_reff,$bulan,$tahun);
        }

        foreach($dataAkunU as $row){
            $dataU[] = (array) $this->jurnal->getJurnalByNoReffMonthYearU($row->no_reff,$bulan,$tahun);
            $saldoU[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearU($row->no_reff,$bulan,$tahun);
        }

        foreach($dataAkunMp as $row){
            $dataMp[] = (array) $this->jurnal->getJurnalByNoReffMonthYearMp($row->no_reff,$bulan,$tahun);
            $saldoMp[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearMp($row->no_reff,$bulan,$tahun);
        }

        if($dataA == null || $saldoA == null || $dataAt == null || $saldoAt == null || $dataU == null || $saldoU == null || $dataMp == null || $saldoMp == null){
            $this->session->set_flashdata('dataNull','Laporan Neraca Dengan Bulan '.bulan($bulan).' Pada Tahun '.$tahun.' Tidak Di Temukan');
            redirect('laporan_keuangan/neraca');
        }

        $jumlahA = count($dataA);
        $jumlahAt = count($dataAt);
        $jumlahU = count($dataU);
        $jumlahMp = count($dataMp);

        $this->load->view('template',compact('content','titleTag','dataAkunA', 'dataAkunAt', 'dataAkunU', 'dataAkunMp' ,'dataA', 'dataAt', 'dataU', 'dataMp' ,'jumlahA', 'jumlahAt', 'jumlahU', 'jumlahMp' ,'saldoA', 'saldoAt', 'saldoU', 'saldoMp' ,'hasil', 'totalA', 'totalAt', 'totalU', 'totalMp' , 's', 'bulan', 'tahun'));
    }

    public function excelLaporanNeraca()
    {
        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);

        if(empty($bulan) || empty($tahun)){
            redirect('laporan_keuangan/neraca');
        }

        $dataAkunA = $this->akun->getAkunByMonthYearA($bulan,$tahun);
        $dataAkunAt = $this->akun->getAkunByMonthYearAt($bulan,$tahun);
        $dataAkunU = $this->akun->getAkunByMonthYearU($bulan,$tahun);
        $dataAkunMp = $this->akun->getAkunByMonthYearMp($bulan,$tahun);
        $dataA = null;
        $dataAt = null;
        $dataU = null;
        $dataMp = null;
        $saldoA = null;
        $saldoAt = null;
        $saldoU = null;
        $saldoMp = null;
        $hasil = null;
        $totalA = null;
        $totalAt = null;
        $totalU = null;
        $totalMp = null;
        $s = null;
        
        foreach($dataAkunA as $row){
            $dataA[] = (array) $this->jurnal->getJurnalByNoReffMonthYearA($row->no_reff,$bulan,$tahun);
            $saldoA[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearA($row->no_reff,$bulan,$tahun);
        }

        foreach($dataAkunAt as $row){
            $dataAt[] = (array) $this->jurnal->getJurnalByNoReffMonthYearAt($row->no_reff,$bulan,$tahun);
            $saldoAt[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearAt($row->no_reff,$bulan,$tahun);
        }

        foreach($dataAkunU as $row){
            $dataU[] = (array) $this->jurnal->getJurnalByNoReffMonthYearU($row->no_reff,$bulan,$tahun);
            $saldoU[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearU($row->no_reff,$bulan,$tahun);
        }

        foreach($dataAkunMp as $row){
            $dataMp[] = (array) $this->jurnal->getJurnalByNoReffMonthYearMp($row->no_reff,$bulan,$tahun);
            $saldoMp[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearMp($row->no_reff,$bulan,$tahun);
        }

        $jumlahA = count($dataA);
        $jumlahAt = count($dataAt);
        $jumlahU = count($dataU);
        $jumlahMp = count($dataMp);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(12);

        $sheet->setCellValue('A1', 'PT. Mitra Sejati Konsultan');
        $sheet->setCellValue('A2', 'Laporan Neraca');
        $sheet->setCellValue('A3', 'Per '. bulan($bulan). " Tahun $tahun");
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        $sheet->getStyle('A1:D3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:D3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ]);

        $sheet->setCellValue('A5', 'Pendapatan');
        $sheet->getStyle('A5')->applyFromArray([
            'font' => ['bold' => true]
        ]);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan Neraca '.bulan($bulan).' '.$tahun;
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
    }

    public function laporanKeuanganArusKas() {
        if($this->session->userdata('role') != 'direktur') {
            show_404();
        }else {
            $titleTag = 'Laporan Keuangan';
            $content = 'user/laporan_keuangan_arus_kas_main';
            $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
            $tahun = $this->jurnal->getJurnalByYear();
            $this->load->view('template',compact('content','listJurnal','titleTag','tahun'));
        }
    }

    public function laporanKeuanganArusKasDetail() {
        $content = 'user/laporan_keuangan_arus_kas';
        $titleTag = 'Laporan Keuangan';
        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);
        $jurnals = null;

        if(empty($bulan) || empty($tahun)){
            redirect('laporan_keuangan/arusKas');
        }

        $jurnals = $this->jurnal->getJurnalJoinAkunDetailFilter($bulan,$tahun);
        $totalKredit = $this->jurnal->getTotalSaldoDetailFilter('kredit',$bulan,$tahun);
        $totalDebit = $this->jurnal->getTotalSaldoDetailFilter('debit',$bulan,$tahun);
        // $labaRugi = null;

        if($jurnals==null){
            $this->session->set_flashdata('dataNull','Data Laporan Keuangan Dengan Bulan '.bulan($bulan).' Pada Tahun '.$tahun.' Tidak Di Temukan');
            redirect('laporan_keuangan/arusKas');
        }

        $this->load->view('template',compact('content','jurnals','totalDebit','totalKredit','titleTag', 'bulan', 'tahun'));
    }

    public function excelLaporanArusKas()
    {
        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);
        $jurnals = null;

        if(empty($bulan) || empty($tahun)){
            redirect('laporan_keuangan/arusKas');
        }

        $jurnals = $this->jurnal->getJurnalJoinAkunDetailFilter($bulan,$tahun);
        $totalKredit = $this->jurnal->getTotalSaldoDetailFilter('kredit',$bulan,$tahun);
        $totalDebit = $this->jurnal->getTotalSaldoDetailFilter('debit',$bulan,$tahun);
        $jumlah = count($jurnals);

        if($jurnals==null){
            $this->session->set_flashdata('dataNull','Data Laporan Keuangan Dengan Bulan '.bulan($bulan).' Pada Tahun '.$tahun.' Tidak Di Temukan');
            redirect('laporan_keuangan/arusKas');
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(8);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(12);
        // $sheet->getStyle('C:D')->getNumberFormat('Accounting');

        $sheet->setCellValue('A1', 'Laporan Arus Kas Bulan '. bulan($bulan). ' Tahun '. $tahun);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16
            ]
        ]);

        $sheet->setCellValue('A3', 'PENDAPATAN');
        $sheet->setCellValue('B3', 'NAMA AKUN');
        $sheet->setCellValue('C3', 'REF');
        $sheet->setCellValue('D3', 'DEBET');
        $sheet->setCellValue('E3', 'KREDIT');
        $sheet->getStyle('A3:E3')->applyFromArray([
            'font' => ['bold' => true]
        ]);

        // $jumlahP = count($dataP);
        
        for($i=0;$i<$jumlah;$i++) {
            // $s=0;
            // $deb = $saldoB[$i];
            // $sheet->setCellValue('A'.$x, $dataB[$i][$s]->nama_reff);
            // for($j=0;$j<count($dataB[$i]);$j++) {
            //     $kreditB = $kreditB + $deb[$j]->saldo;
            //     $hasilB = $kreditB-$debitB;
            // }
            // $sheet->setCellValue('C'.$x, $hasilB);
            // $totalB += $hasilB;
            // $debitB = 0;
            // $kreditB = 0;
            // $x++;
        }


        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan Arus Kas '.bulan($bulan).' '.$tahun;
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
    }

    public function laporan(){
        $titleTag = 'Laporan';
        $content = 'user/laporan_main';
        $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
        $tahun = $this->jurnal->getJurnalByYear();
        $this->load->view('template',compact('content','listJurnal','titleTag','tahun'));
    }

    public function laporanCetak(){
        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);
        $titleTag = 'Laporan '.bulan($bulan).' '.$tahun;

        $dataAkun = $this->akun->getAkunByMonthYear($bulan,$tahun);
        var_dump($dataAkun);
        die;

        $jurnals = $this->jurnal->getJurnalJoinAkunDetail($bulan,$tahun);
        $totalDebit = $this->jurnal->getTotalSaldoDetail('debit',$bulan,$tahun);
        $totalKredit = $this->jurnal->getTotalSaldoDetail('kredit',$bulan,$tahun);

        $data = null;
        $saldo = null;
        foreach($dataAkun as $row){
            $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYear($row->no_reff,$bulan,$tahun);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYear($row->no_reff,$bulan,$tahun);
        }

        if($data == null || $saldo == null){
            $this->session->set_flashdata('dataNull','Laporan Dengan Bulan '.bulan($bulan).' Pada Tahun '.$tahun.' Tidak Di Temukan');
            redirect('laporan');
        }

        $jumlah = count($data);

        // $this->load->library('pdf');
        // $this->pdf->setPaper('A4', 'landscape');
        // $this->pdf->filename = "laporan_".bulan($bulan).'_'.$tahun;
        // $this->pdf->load_view('user/laporan', $data);
    }

    public function logout(){
        $this->user->logout();
        redirect('');
    }
}
