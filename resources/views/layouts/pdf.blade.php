<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Latest compiled and minified bootstrap CSS -->
    @if($usebootstrap == 1)
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    @endif
    <!-- <link rel="stylesheet" href="@url('pdf.css')"> -->
    <title>Overzicht</title>
    @if($orientation==1)
    <style>
        @page {
            margin: 100px 25px;
            size: A4 landscape;
        }
    @else
    <style>
        @page {
            margin: 100px 25px;
        }
    @endif

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            /*background-color: lightblue;*/
            height: 50px;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            /*background-color: lightblue;*/
            height: 50px;
            text-align: center;
        }

        p {
            page-break-after: always;
        }

        p:last-child {
            page-break-after: never;
        }
        .teltnietmee {
            text-decoration: line-through;
        }

        .datecol {
            width: 100px;
        }

        .centercol {
            text-align: center;
        }

        .rotated {
            transform: rotate(-90deg);
            margin-left: 50px;
            text-align: left;
            height: 100px;
        }
        .cbcol1{
            text-align: center;
            
        }
        .spacercol{
            padding-left: 40px;
        }
    </style>
</head>

<body>
@yield('content')
    
</body>

</html>