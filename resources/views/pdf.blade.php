<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Latest compiled and minified bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="@url('pdf.css')"> -->
    <title>Overzicht voor {{$employee->fullname}}</title>
    <style>
        @page {
            margin: 100px 25px;
        }

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
    </style>
</head>

<body>
    <header>TADD calculator v2 - overzicht voor {{$employee->fullname}}</header>
    <footer>Gegenereerd op {{$gendate}} door {{Auth::user()->name}}</footer>
    <div class="container">
        <div class="row">
            <table>
                <tr>
                    <td width="120px"><img height="100px" src="https://www.skbl.be/joomla/images/logo/logo.png" alt="skbl logo"></td>
                    <td width="30px"></td>
                    <td>
                        <h1 class="bd-title">{{$employee->fullname}}</h1>
                        <h4 class="bd-lead">{{$employee->registrationNumber}}</h4>
                    </td>
                </tr>
            </table>

        </div>
        <div class="row">
            <h2>Gekende aanstellingen</h2>
        </div>
        <div class="row">
            <div class="col col-sm-12">
                @if(count($ambten) > 0)
                @foreach($ambten as $ambt)
                <h3>{{$ambt->name}} : {{$aanstellingen[$ambt->id][0]->total_seniority_days}} dagen ({{$aanstellingen[$ambt->id][0]->seniority_days}} eff)</h3>
                <table class="table">
                    <tr>
                        <th>School</th>
                        <th class="datecol">Van</th>
                        <th class="datecol">Tot</th>
                        
                    </tr>
                    @foreach($aanstellingen[$ambt->id][0]->employments as $aanstelling)
                    
                    <tr class="{{ ($aanstelling->school->useForCalculations == 0) ? 'teltnietmee' : ''}}">
                        
                        <td>{{$aanstelling->school->name}}</td>
                        <td class="datecol">{{$aanstelling->beginDateAsDate}}</td>
                        <td class="datecol">{{$aanstelling->endDateAsDate}}</td>
                        
                    </tr>
                    @endforeach
                </table>
                @endforeach
                @else
                <h3 style="text-align: center; background-color:lightyellow">Geen gegevens gevonden</h3>
                @endif
            </div>
        </div>

        <div class="row">
            <h2>Gekende onderbrekingen</h2>
        </div>
        <div class="row">
            <div class="col col-sm-12">
                @if(count($interruptions) > 0)
                <table class="table">
                    <tr>
                        <th class="datecol">Van</th>
                        <th class="datecol">Tot</th>
                        <th></th>
                    </tr>
                    @foreach($interruptions as $interruption)
                    <tr>
                        <td class="datecol">{{$interruption->beginDateAsDate}}</td>
                        <td class="datecol">{{$interruption->endDateAsDate}}</td>
                        <td>Deze periode telt {{($interruption->interruption_type_id == 1)? 'WEL' : 'NIET'}} mee voor effectief</td>
                    </tr>
                    @endforeach
                </table>
                @else
                <h3 style="text-align: center; background-color:lightyellow">Geen gegevens gevonden</h3>
                @endif
            </div>
        </div>

    </div>
</body>

</html>