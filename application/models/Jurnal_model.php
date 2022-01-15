<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_model extends CI_Model{
    private $table = 'transaksi';

    public function getJurnal(){
        return $this->db->get($this->table)->result();
    }

    public function getJurnalById($id){
        return $this->db->where('id_transaksi',$id)->get($this->table)->row();
    }

    public function countJurnalNoReff($noReff){
        return $this->db->where('no_reff',$noReff)->get($this->table)->num_rows();
    }

    public function getJurnalByYear(){
        return $this->db->select('tgl_transaksi')
                        ->from($this->table)
                        ->group_by('year(tgl_transaksi)')
                        ->get()
                        ->result();
    }

    public function getJurnalByYearAndMonth(){
        return $this->db->select('tgl_transaksi')
                        ->from($this->table)
                        ->group_by('month(tgl_transaksi)')
                        ->group_by('year(tgl_transaksi)')
                        ->get()
                        ->result();
    }

    public function getAkunInJurnal(){
        return $this->db->select('transaksi.no_reff,akun.no_reff,akun.nama_reff')
                    ->from($this->table)            
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->order_by('akun.no_reff','ASC')
                    ->group_by('akun.nama_reff')
                    ->get()
                    ->result();
    }

    public function countAkunInJurnal(){
        return $this->db->select('transaksi.no_reff,akun.no_reff,akun.nama_reff')
                    ->from($this->table)            
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->order_by('akun.no_reff','ASC')
                    ->group_by('akun.nama_reff')
                    ->get()
                    ->num_rows();
    }

    public function getJurnalByNoReff($noReff){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
                    ->from($this->table)            
                    ->where('transaksi.no_reff',$noReff)
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalByNoReffMonthYear($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
                    ->from($this->table)            
                    ->where('transaksi.no_reff',$noReff)
                    ->where('month(transaksi.tgl_transaksi)',$bulan)
                    ->where('year(transaksi.tgl_transaksi)',$tahun)
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalByNoReffMonthYearP($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
                    ->from($this->table)            
                    ->where('transaksi.no_reff',$noReff)
                    ->where('month(transaksi.tgl_transaksi)',$bulan)
                    ->where('year(transaksi.tgl_transaksi)',$tahun)
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->like('akun.nama_reff', 'pendapatan')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
                }

    public function getJurnalByNoReffMonthYearPr($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
                    ->from($this->table)            
                    ->where('transaksi.no_reff',$noReff)
                    ->where('month(transaksi.tgl_transaksi)',$bulan)
                    ->where('year(transaksi.tgl_transaksi)',$tahun)
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->like('akun.nama_reff', 'prive')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
                }

    public function getJurnalByNoReffMonthYearB($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
                    ->from($this->table)            
                    ->where('transaksi.no_reff',$noReff)
                    ->where('month(transaksi.tgl_transaksi)',$bulan)
                    ->where('year(transaksi.tgl_transaksi)',$tahun)
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->like('akun.nama_reff', 'beban')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
                }

    public function getJurnalByNoReffMonthYearM($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
                    ->from($this->table)            
                    ->where('transaksi.no_reff',$noReff)
                    ->where('month(transaksi.tgl_transaksi)',$bulan)
                    ->where('year(transaksi.tgl_transaksi)',$tahun)
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->like('akun.nama_reff', 'modal')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
                }

    public function getJurnalByNoReffMonthYearA($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
                    ->from($this->table)            
                    ->where('transaksi.no_reff',$noReff)
                    ->where('month(transaksi.tgl_transaksi)',$bulan)
                    ->where('year(transaksi.tgl_transaksi)',$tahun)
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->like('akun.no_reff', '11', 'after')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
                }

    public function getJurnalByNoReffMonthYearAt($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
                    ->from($this->table)            
                    ->where('transaksi.no_reff',$noReff)
                    ->where('month(transaksi.tgl_transaksi)',$bulan)
                    ->where('year(transaksi.tgl_transaksi)',$tahun)
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->like('akun.no_reff', '12', 'after')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
                }

    public function getJurnalByNoReffMonthYearU($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
                    ->from($this->table)            
                    ->where('transaksi.no_reff',$noReff)
                    ->where('month(transaksi.tgl_transaksi)',$bulan)
                    ->where('year(transaksi.tgl_transaksi)',$tahun)
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->like('akun.nama_reff', 'utang', 'after')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
                }

    public function getJurnalByNoReffMonthYearMp($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
                    ->from($this->table)            
                    ->where('transaksi.no_reff',$noReff)
                    ->where('month(transaksi.tgl_transaksi)',$bulan)
                    ->where('year(transaksi.tgl_transaksi)',$tahun)
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->like('akun.no_reff', '311')
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
                }

    public function getJurnalByNoReffSaldo($noReff){
        return $this->db->select('transaksi.jenis_saldo,transaksi.saldo')
        ->from($this->table)            
        ->where('transaksi.no_reff',$noReff)
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->order_by('tgl_transaksi','ASC')
        ->get()
        ->result();
    }
    
    public function getJurnalByNoReffSaldoMonthYear($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.jenis_saldo,transaksi.saldo')
        ->from($this->table)            
        ->where('transaksi.no_reff',$noReff)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->order_by('tgl_transaksi','ASC')
        ->get()
        ->result();
    }
    
    public function getJurnalByNoReffSaldoMonthYearP($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.jenis_saldo,transaksi.saldo')
        ->from($this->table)            
        ->where('transaksi.no_reff',$noReff)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->like('akun.nama_reff', 'pendapatan')
        ->order_by('tgl_transaksi','ASC')
        ->get()
        ->result();
    }

    public function getJurnalByNoReffSaldoMonthYearPr($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.jenis_saldo,transaksi.saldo')
        ->from($this->table)            
        ->where('transaksi.no_reff',$noReff)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->like('akun.nama_reff', 'prive')
        ->order_by('tgl_transaksi','ASC')
        ->get()
        ->result();
    }

    public function getJurnalByNoReffSaldoMonthYearB($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.jenis_saldo,transaksi.saldo')
        ->from($this->table)            
        ->where('transaksi.no_reff',$noReff)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->like('akun.nama_reff', 'beban')
        ->order_by('tgl_transaksi','ASC')
        ->get()
        ->result();
    }

    public function getJurnalByNoReffSaldoMonthYearM($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.jenis_saldo,transaksi.saldo')
        ->from($this->table)            
        ->where('transaksi.no_reff',$noReff)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->like('akun.nama_reff', 'modal')
        ->order_by('tgl_transaksi','ASC')
        ->get()
        ->result();
    }

    public function getJurnalByNoReffSaldoMonthYearA($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.jenis_saldo,transaksi.saldo')
        ->from($this->table)            
        ->where('transaksi.no_reff',$noReff)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->like('akun.no_reff', '11', 'after')
        ->order_by('tgl_transaksi','ASC')
        ->get()
        ->result();
    }

    public function getJurnalByNoReffSaldoMonthYearAt($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.jenis_saldo,transaksi.saldo')
        ->from($this->table)            
        ->where('transaksi.no_reff',$noReff)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->like('akun.no_reff', '12', 'after')
        ->order_by('tgl_transaksi','ASC')
        ->get()
        ->result();
    }

    public function getJurnalByNoReffSaldoMonthYearU($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.jenis_saldo,transaksi.saldo')
        ->from($this->table)            
        ->where('transaksi.no_reff',$noReff)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->like('akun.nama_reff', 'utang', 'after')
        ->order_by('tgl_transaksi','ASC')
        ->get()
        ->result();
    }

    public function getJurnalByNoReffSaldoMonthYearMp($noReff,$bulan,$tahun){
        return $this->db->select('transaksi.jenis_saldo,transaksi.saldo')
        ->from($this->table)            
        ->where('transaksi.no_reff',$noReff)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->like('akun.no_reff', '311')
        ->order_by('tgl_transaksi','ASC')
        ->get()
        ->result();
    }

    public function getJurnalJoinAkun(){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
                        ->from($this->table)
                        ->join('akun','transaksi.no_reff = akun.no_reff')
                        ->order_by('tgl_transaksi','ASC')
                        ->order_by('tgl_input','ASC')
                        ->order_by('jenis_saldo','ASC')
                        ->get()
                        ->result();
    }

    public function getJurnalJoinAkunDetail($bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
        ->from($this->table)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->order_by('tgl_transaksi','ASC')
        ->order_by('tgl_input','ASC')
        ->order_by('jenis_saldo','ASC')
        ->get()
        ->result();
    }
    
    public function getJurnalJoinAkunDetailFilter($bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
        ->from($this->table)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->like('transaksi.no_reff', '111')
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->order_by('tgl_transaksi','ASC')
        ->order_by('tgl_input','ASC')
        ->order_by('jenis_saldo','ASC')
        ->get()
        ->result();
    }

    public function getJurnalJoinAkunDetailFilterP($bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
        ->from($this->table)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->like('transaksi.no_reff', '4')
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->order_by('tgl_transaksi','ASC')
        ->order_by('tgl_input','ASC')
        ->order_by('jenis_saldo','ASC')
        ->get()
        ->result();
    }
    
    public function getJurnalJoinAkunDetailFilterB($bulan,$tahun){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
        ->from($this->table)
        ->where('month(transaksi.tgl_transaksi)',$bulan)
        ->where('year(transaksi.tgl_transaksi)',$tahun)
        ->like('transaksi.no_reff','5')
        ->join('akun','transaksi.no_reff = akun.no_reff')
        ->order_by('tgl_transaksi','ASC')
        ->order_by('tgl_input','ASC')
        ->order_by('jenis_saldo','ASC')
        ->get()
        ->result();
    }

    public function getTotalSaldoDetail($jenis_saldo,$bulan,$tahun){
        return $this->db->select_sum('saldo')
                        ->from($this->table)
                        ->where('month(transaksi.tgl_transaksi)',$bulan)
                        ->where('year(transaksi.tgl_transaksi)',$tahun)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->get()
                        ->row();
                    }
                    
public function getTotalSaldoDetailFilter($jenis_saldo,$bulan,$tahun){
        return $this->db->select_sum('saldo')
                        ->from($this->table)
                        ->where('month(transaksi.tgl_transaksi)',$bulan)
                        ->where('year(transaksi.tgl_transaksi)',$tahun)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->like('transaksi.no_reff', '111')
                        ->get()
                        ->row();
    }

public function getTotalSaldoDetailFilterP($jenis_saldo,$bulan,$tahun){
        return $this->db->select_sum('saldo')
                        ->from($this->table)
                        ->where('month(transaksi.tgl_transaksi)',$bulan)
                        ->where('year(transaksi.tgl_transaksi)',$tahun)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->like('transaksi.no_reff', '4')
                        ->get()
                        ->row();
    }

    public function getTotalSaldoDetailFilterB($jenis_saldo,$bulan,$tahun){
        return $this->db->select_sum('saldo')
                        ->from($this->table)
                        ->where('month(transaksi.tgl_transaksi)',$bulan)
                        ->where('year(transaksi.tgl_transaksi)',$tahun)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->like('transaksi.no_reff', '5')
                        ->get()
                        ->row();
    }

    public function getTotalSaldoDetailFilterA($jenis_saldo,$bulan,$tahun){
        return $this->db->select_sum('saldo')
                        ->from($this->table)
                        ->where('month(transaksi.tgl_transaksi)',$bulan)
                        ->where('year(transaksi.tgl_transaksi)',$tahun)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->like('transaksi.no_reff', '11', 'after')
                        ->get()
                        ->row();
    }

    public function getTotalSaldo($jenis_saldo){
        return $this->db->select_sum('saldo')
                        ->from($this->table)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->get()
                        ->row();
    }

    public function insertJurnal($data){
        return $this->db->insert($this->table,$data);
    }

    public function updateJurnal($id,$data){
        return $this->db->where('id_transaksi',$id)->update($this->table,$data);
    }

    public function deleteJurnal($id){
        return $this->db->where('id_transaksi',$id)->delete($this->table);
    }

    public function getDefaultValues(){
        return [
            'tgl_transaksi'=>date('Y-m-d'),
            'no_reff'=>'',
            'jenis_saldo'=>'',
            'saldo'=>'',
        ];
    }

    public function getValidationRules(){
        return [
            [
                'field'=>'tgl_transaksi',
                'label'=>'Tanggal Transaksi',
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