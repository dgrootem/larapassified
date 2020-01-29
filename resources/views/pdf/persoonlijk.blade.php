@extends('layouts.pdf')

@section('content')
    <header>TADD calculator v2 - overzicht voor {{$employee->fullname}}</header>
    <footer>Gegenereerd op {{$gendate}} door {{Auth::user()->name}}</footer>
    <div class="container">
        <div class="row">
            <table>
                <tr>
                    <td width="120px"><img height="100px" src="{{$mainlogo->value}}" alt="skbl logo"></td>
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
                        <th>Uren</th>
                        <th class="datecol">Van</th>
                        <th class="datecol">Tot</th>
                        
                    </tr>
                    @foreach($aanstellingen[$ambt->id][0]->employments as $aanstelling)
                    
                    <tr class="{{ ($aanstelling->school->useForCalculations == 0) ? 'teltnietmee' : ''}}">
                        
                        <td>{{$aanstelling->school->name}}</td>
                        <td>{{$aanstelling->hours}}</td>
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
                        <td>Deze periode telt {{($interruption->interruption_type->telt_mee == 1) ? 'WEL' : 'NIET'}} mee voor effectief</td>
                    </tr>
                    @endforeach
                </table>
                @else
                <h3 style="text-align: center; background-color:lightyellow">Geen gegevens gevonden</h3>
                @endif
            </div>
        </div>

    </div>
@endsection