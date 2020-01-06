<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Latest compiled and minified bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="@url('pdf.css')">
    <title>Overzicht voor {{$employee->fullname}}</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <img src="https://www.skbl.be/joomla/images/logo/logo.png" alt="skbl logo">
            </div>
            <div class="col-md-8">
                <span class="titel">Persoonlijk overzicht voor {{$employee->fullname}}</span>
                <span class="stamboeknummer">{{$employee->registrationNumber}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col col-md-12">
            </div>
            <div>
                @foreach($ambten as $ambt)
                {{$ambt->name}}
                <table>
                    
                </table>
            </div>

        </div>
    </div>
</body>

</html>