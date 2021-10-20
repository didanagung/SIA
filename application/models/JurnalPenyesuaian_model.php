<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JurnalPenyesuaian_model extends CI_Model{
    private $table1 = 'transaksi';
    private $table2 = 'penyesuaian';

    public function getJurnal(){
        return $this->db->get($this->table2)->result();
    }

    public function getJurnalById($id){
        return $this->db->where('id_penyesuaian',$id)->get($this->table2)->row();
    }

    public function countJurnalNoReff($noReff){
        return $this->db->where('no_reff',$noReff)->get($this->table2)->num_rows();
    }

    public function getJurnalByYear(){
        return $this->db->select('tgl_penyesuaian')
                        ->from($this->table2)
                        ->group_by('year(tgl_penyesuaian)')
                        ->get()
                        ->result();
    }

    public function getJurnalByYearAndMonth(){
        return $this->db->select('tgl_penyesuaian')
                        ->from($this->table2)
                        ->group_by('month(tgl_penyesuaian)')
                        ->group_by('year(tgl_penyesuaian)')
                        ->get()
                        ->result();
    }

    public function getAkunInJurnal(){
        return $this->db->select('penyesuaian.no_reff,akun.no_reff,akun.nama_reff')
                    ->from($this->table2)            
                    ->join('akun','penyesuaian.no_reff = akun.no_reff')
                    ->order_by('akun.no_reff','ASC')
                    ->group_by('akun.nama_reff')
                    ->get()
                    ->result();
    }

    public function countAkunInJurnal(){
        return $this->db->select('penyesuaian.no_reff,akun.no_reff,akun.nama_reff')
                    ->from($this->table2)            
                    ->join('akun','penyesuaian.no_reff = akun.no_reff')
                    ->order_by('akun.no_reff','ASC')
                    ->group_by('akun.nama_reff')
                    ->get()
                    ->num_rows();
    }

    public function getJurnalByNoReff($noReff){
        return $this->db->select('penyesuaian.id_penyesuaian,penyesuaian.tgl_penyesuaian,akun.nama_reff,penyesuaian.no_reff,penyesuaian.jenis_saldo,penyesuaian.saldo,penyesuaian.tgl_input')
                    ->from($this->table2)            
                    ->where('penyesuaian.no_reff',$noReff)
                    ->join('akun','penyesuaian.no_reff = akun.no_reff')
                    ->order_by('tgl_penyesuaian','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalByNoReffMonthYear($noReff,$bulan,$tahun){
        return $this->db->select('penyesuaian.id_penyesuaian,penyesuaian.tgl_penyesuaian,akun.nama_reff,penyesuaian.no_reff,penyesuaian.jenis_saldo,penyesuaian.saldo,penyesuaian.tgl_input')
                    ->from($this->table2)            
                    ->where('penyesuaian.no_reff',$noReff)
                    ->where('month(penyesuaian.tgl_penyesuaian)',$bulan)
                    ->where('year(penyesuaian.tgl_penyesuaian)',$tahun)
                    ->join('akun','penyesuaian.no_reff = akun.no_reff')
                    ->order_by('tgl_penyesuaian','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalByNoReffSaldo($noReff){
        return $this->db->select('penyesuaian.jenis_saldo,penyesuaian.saldo')
                    ->from($this->table2)            
                    ->where('penyesuaian.no_reff',$noReff)
                    ->join('akun','penyesuaian.no_reff = akun.no_reff')
                    ->order_by('tgl_penyesuaian','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalByNoReffSaldoMonthYear($noReff,$bulan,$tahun){
        return $this->db->select('penyesuaian.jenis_saldo,penyesuaian.saldo')
                    ->from($this->table2)            
                    ->where('penyesuaian.no_reff',$noReff)
                    ->where('month(penyesuaian.tgl_penyesuaian)',$bulan)
                    ->where('year(penyesuaian.tgl_penyesuaian)',$tahun)
                    ->join('akun','penyesuaian.no_reff = akun.no_reff')
                    ->order_by('tgl_penyesuaian','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalJoinAkun(){
        return $this->db->select('penyesuaian.id_penyesuaian,penyesuaian.tgl_penyesuaian,akun.nama_reff,penyesuaian.no_reff,penyesuaian.jenis_saldo,penyesuaian.saldo,penyesuaian.tgl_input')
                        ->from($this->table2)
                        ->join('akun','penyesuaian.no_reff = akun.no_reff')
                        ->order_by('tgl_penyesuaian','ASC')
                        ->order_by('tgl_input','ASC')
                        ->order_by('jenis_saldo','ASC')
                        ->get()
                        ->result();
    }

    public function getJurnalJoinAkunDetail($bulan,$tahun){
        return $this->db->select('penyesuaian.id_penyesuaian,penyesuaian.tgl_penyesuaian,akun.nama_reff,penyesuaian.no_reff,penyesuaian.jenis_saldo,penyesuaian.saldo,penyesuaian.tgl_input')
                        ->from($this->table2)
                        ->where('month(penyesuaian.tgl_penyesuaian)',$bulan)
                        ->where('year(penyesuaian.tgl_penyesuaian)',$tahun)
                        ->join('akun','penyesuaian.no_reff = akun.no_reff')
                        ->order_by('tgl_penyesuaian','ASC')
                        ->order_by('tgl_input','ASC')
                        ->order_by('jenis_saldo','ASC')
                        ->get()
                        ->result();
    }

    public function getJurnalJoinAkunDetailFilterP($bulan,$tahun){
        return $this->db->select('penyesuaian.id_penyesuaian,penyesuaian.tgl_penyesuaian,akun.nama_reff,penyesuaian.no_reff,penyesuaian.jenis_saldo,penyesuaian.saldo,penyesuaian.tgl_input')
                        ->from($this->table2)
                        ->where('month(penyesuaian.tgl_penyesuaian)',$bulan)
                        ->where('year(penyesuaian.tgl_penyesuaian)',$tahun)
                        ->like('penyesuaian.no_reff','4')
                        ->join('akun','penyesuaian.no_reff = akun.no_reff')
                        ->order_by('tgl_penyesuaian','ASC')
                        ->order_by('tgl_input','ASC')
                        ->order_by('jenis_saldo','ASC')
                        ->get()
                        ->result();
    }

    public function getJurnalJoinAkunDetailFilterB($bulan,$tahun){
        return $this->db->select('penyesuaian.id_penyesuaian,penyesuaian.tgl_penyesuaian,akun.nama_reff,penyesuaian.no_reff,penyesuaian.jenis_saldo,penyesuaian.saldo,penyesuaian.tgl_input')
                        ->from($this->table2)
                        ->where('month(penyesuaian.tgl_penyesuaian)',$bulan)
                        ->where('year(penyesuaian.tgl_penyesuaian)',$tahun)
                        ->like('penyesuaian.no_reff','5')
                        ->join('akun','penyesuaian.no_reff = akun.no_reff')
                        ->order_by('tgl_penyesuaian','ASC')
                        ->order_by('tgl_input','ASC')
                        ->order_by('jenis_saldo','ASC')
                        ->get()
                        ->result();
    }

    public function getTotalSaldoDetail($jenis_saldo,$bulan,$tahun){
        return $this->db->select_sum('saldo')
                        ->from($this->table2)
                        ->where('month(penyesuaian.tgl_penyesuaian)',$bulan)
                        ->where('year(penyesuaian.tgl_penyesuaian)',$tahun)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->get()
                        ->row();
    }

    public function getTotalSaldoDetailFilterP($jenis_saldo,$bulan,$tahun){
        return $this->db->select_sum('saldo')
                        ->from($this->table2)
                        ->where('month(penyesuaian.tgl_penyesuaian)',$bulan)
                        ->where('year(penyesuaian.tgl_penyesuaian)',$tahun)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->like('penyesuaian.no_reff', '4')
                        ->get()
                        ->row();
    }

    public function getTotalSaldoDetailFilterB($jenis_saldo,$bulan,$tahun){
        return $this->db->select_sum('saldo')
                        ->from($this->table2)
                        ->where('month(penyesuaian.tgl_penyesuaian)',$bulan)
                        ->where('year(penyesuaian.tgl_penyesuaian)',$tahun)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->like('penyesuaian.no_reff', '5')
                        ->get()
                        ->row();
    }

    public function getTotalSaldo($jenis_saldo){
        return $this->db->select_sum('saldo')
                        ->from($this->table2)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->get()
                        ->row();
    }

    public function insertJurnal($data){
        return $this->db->insert($this->table2,$data);
    }

    public function updateJurnal($id,$data){
        return $this->db->where('id_penyesuaian',$id)->update($this->table2,$data);
    }

    public function deleteJurnalPenyesuaian($id){
        return $this->db->where('id_penyesuaian',$id)->delete($this->table2);
    }

    public function getDefaultValues(){
        return [
            'tgl_penyesuaian'=>date('Y-m-d'),
            'no_reff'=>'',
            'jenis_saldo'=>'',
            'saldo'=>'',
        ];
    }

    public function getValidationRules(){
        return [
            [
                'field'=>'tgl_penyesuaian',
                'label'=>'Tanggal Penyesuaian',
                'rules'=>'trim|required'
            ],
            [
                'field'=>'no_reff',
                'label'=>'Nama Akun',
                'rules'=>'trim|required'
            ],
            [
                'field'=>'jenis_saldo',
                'label'=>'Jenis Saldo',
                'rules'=>'trim|required'
            ],
            [
                'field'=>'saldo',
                'label'=>'Saldo',
                'rules'=>'trim|required|numeric'
            ],
        ];
    }

    public function validate(){
        $rules = $this->getValidationRules();
        $this->form_validation->set_rules($rules);
        $this->form_validation->set_error_delimiters('<span class="text-danger" style="font-size:14px">','</span>');
        return $this->form_validation->run();
    }
}