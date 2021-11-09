  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?= base_url('laporan_keuangan/labaRugi') ?>">&laquo Laporan Keuangan Laba / Rugi</a>
        <!-- Form -->
        <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
          <div class="form-group mb-0">
            
          </div>
        </form>
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                  <img alt="Image placeholder" src="<?= base_url('assets/img/theme/team-4-800x800.jpg') ?>">
                </span>
                <div class="media-body ml-2 d-none d-lg-block">
                  <span class="mb-0 text-sm  font-weight-bold"><?= ucwords($this->session->userdata('username')) ?></span>
                </div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
              <a href="<?= base_url('logout') ?>" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Logout</span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- Header -->
    <div class="header bg-gradient-warning pb-8 pt-5 pt-md-8">
      <div class="container-fluid">
        <div class="header-body">    
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--7">
      <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0">
               
        </div>
      </div>
      <div class="row mt-5">
        <div class="col mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Laporan Keuangan Laba / Rugi</h3>
                </div>
              </div>
            </div>
            <div class="table-responsive">
            <?php 
                $a=0;
                $debit = 0;
                $kredit = 0;
            ?>
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Nama Akun</th>
                    <th scope="col">Nominal</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                        $totalP=0;
                        for($i=0;$i<$jumlahP;$i++) :                          
                            $a++;
                            $s=0;
                            $deb = $saldoP[$i];
                    ?>
                    <tr>
                        <td>
                            <?= $dataP[$i][$s]->nama_reff ?>
                        </td>
                        <?php 
                            for($j=0;$j<count($dataP[$i]);$j++):
                                    $kredit = $kredit + $deb[$j]->saldo;
                                $hasil = $debit-$kredit;
                            endfor 
                        ?>
                                <td><?= 'Rp. '.number_format(abs($hasil),0,',','.') ?></td>
                                <?php $totalP += $hasil; ?>
                        <?php
                            $debit = 0;
                            $kredit = 0;
                        ?>
                    </tr>
                    <?php endfor ?>
                      <tr>
                        <td class="text-center"><b>Total</b></td>
                        <td class="text-primary"><?= 'Rp. '.number_format(abs($totalP),0,',','.') ?></td>
                    </tr>
                </tbody>
              </table>

              <!-- tabel beban -->
              <table class="table align-items-center table-flush mt-3">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Nama Akun</th>
                    <th scope="col">Nominal</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                        $totalB=0;
                        for($i=0;$i<$jumlahB;$i++) :                          
                            $a++;
                            $s=0;
                            $deb = $saldoB[$i];
                    ?>
                    <tr>
                        <td>
                            <?= $dataB[$i][$s]->nama_reff ?>
                        </td>
                        <?php 
                            for($j=0;$j<count($dataB[$i]);$j++):
                                    $kredit = $kredit + $deb[$j]->saldo;
                                $hasil = $debit-$kredit;
                            endfor 
                        ?>
                                <td><?= 'Rp. '.number_format(abs($hasil),0,',','.') ?></td>
                                <?php $totalB += $hasil; ?>
                        <?php
                            $debit = 0;
                            $kredit = 0;
                        ?>
                    </tr>
                    <?php endfor ?>
                      <tr>
                        <td class="text-center"><b>Total</b></td>
                        <td class="text-primary"><?= 'Rp. '.number_format(abs($totalB),0,',','.') ?></td>
                    </tr>
                      <tr>
                        <?php $nilaiTotal = $totalP - $totalB; ?>
                        <td class="text-center"><b><?= $nilaiTotal > 0 ? 'Rugi' : 'Laba'; ?></b></td>
                        <td class="<?= $nilaiTotal > 0 ? 'text-danger' : 'text-primary'; ?>"><?= 'Rp. '.number_format(abs($nilaiTotal),0,',','.') ?></td>
                    </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>