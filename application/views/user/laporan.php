<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $titleTag.' Dicetak Oleh '.$this->session->userdata('username') ?></title>
    <style>
        *{
            font-family:sans-serif;
        }
        table{
            width:100%;
        }
        table,th,tr,td{
            border:1px solid black;
            border-collapse:collapse;
            border-spacing:0;
        }
        th,td{
            padding:18px;
        }
        .text-center{
            text-align:center;
        }
        .text-right{
            text-align:right;
        }
        .font-bold{
            font-weight:bold;
        }
        .m-fix{
            margin:15px;
        }
        .mt-fix{
            margin-top:15px;
        }
        .mb-fix{
            margin-bottom:15px;
        }
        hr{
            width:800px;
            margin-bottom:30px;
        }
        .d-flex{
            display:flex;
        }
        .w-100{
            width:100%;
        }
    </style>
</head>
<body>
   <?php 
   use PhpOffice\PhpSpreadsheet\Spreadsheet;
   use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

        
        ?>
</body>
</html>