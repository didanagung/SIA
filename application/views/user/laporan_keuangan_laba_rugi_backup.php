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
            <div class="container">
            <div class="table-responsive">
                <h3>Pendapatan</h3>
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col"></th>
                    <th scope="col">Nominal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $i=1;
                    foreach($jurnalsP as $row):
                      if($row->jenis_saldo=='kredit'):
                  ?>
                  <tr>
                    <td>
                    <?= $row->nama_reff ?>
                    </td>
                    <td>
                    <?= 'Rp. '.number_format($row->saldo,0,',','.') ?>
                    </td>     
                  </tr>
                  <?php 
                    endif;
                  ?>
                  <?php endforeach ?>
                  <tr>
                    <td class="text"><b>Total Pendapatan</b></td>
                    <td class="text-danger pr-5"><b><?= 'Rp. '.number_format($totalKreditP->saldo,0,',','.') ?></b></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="table-responsive mt-3">
                <h3>Beban</h3>
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col"></th>
                    <th scope="col">Nominal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $i=1;
                    foreach($jurnalsB as $rows):
                      if($rows->jenis_saldo=='debit'):
                  ?>
                  <tr>
                  <td>
                    <?= $rows->nama_reff ?>
                    </td>
                    <td>
                    <?= 'Rp. '.number_format($rows->saldo,0,',','.') ?>
                    </td>    
                  </tr>
                  <?php 
                    endif;
                  ?>
                  <?php endforeach ?>
                  <tr>
                    <td class="text"><b>Total Beban</b></td>
                    <td class="text-danger"><b><?= 'Rp. '.number_format($totalDebitB->saldo,0,',','.') ?></b></td>
                  </tr>

                  <tr>
                    <?php $labaRugi = $totalKreditP->saldo - $totalDebitB->saldo ?>
                    <td class="text"><b><?= ($labaRugi < 0) ? "Rugi" :  "Laba"  ?></b></td>
                    <td class="text-primary"><b><?= 'Rp. '.number_format($labaRugi,0,',','.')  ?></b></td>
                  </tr>
                </tbody>
              </table>
              </div>
            </div>
          </div>
        </div>
      </div>