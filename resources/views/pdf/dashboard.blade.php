@extends('layouts.pdf')

@section('content')

<header>
    TADD calculator v2 - overzicht voor @empty($school) alle personeelsleden @endempty @isset($school) {{$school->abbreviation}} @endisset
</header>
    <footer>Gegenereerd op {{$gendate}} door {{Auth::user()->name}}</footer>
<div class="container">
    @if(!(Auth::user()->readonly))
    <i>
    <ul><b>Verklaring kolomlijst:</b>
            <li>Arch-P : personeelslid is permanent gearchiveerd </li>
            <li>Arch-T : personeelslid is tijdelijk gearchiveerd omdat het personeelslid op dit moment elders is
            tewerkgesteld (andere scholengemeenschap, privé, …), wel TADD opbouwt in een bepaald ambt,
            maar niet van plan is om in dit ambt TADD aan te vragen, …</li>
            <li>Arch-A : het systeem heeft dit personeelslid automatisch als gearchiveerd gemarkeerd omdat het
            personeelslid gedurende minstens de laatste 5 (opeenvolgende) schooljaren geen aanstelling meer
            heeft gehad in de scholengemeenschap. Dit gebeurt per ambt!</li>
    </ul>
    </i>
    @endif
    @if($listType == 1)
    <div class="row">
        <h2>Volgend jaar recht op TADD</h2>
    </div>
    <div class="row">
        <table class="table">
        <tr>
            <th width="150px">Naam</th>
            <th width="100px">Voornaam</th>
            <th width="150px">Ambt</th>
            <th width="30px">TOT</th>
            <th width="30px">EFF</th>
            @if(!(Auth::user()->readonly))
            <th class="rotated" width="30px">Arch-P</th>
            <th class="rotated" width="30px">Arch-T</th>
            <th class="rotated" width="30px">Arch-A</th>
            
            @endif
        </tr>
        @foreach($listToShow as $functiondatarow)
        <tr>
            <td>{{$functiondatarow->lastname}}</td>
            <td>{{$functiondatarow->firstname}}</td>
            <td>{{$functiondatarow->ambt}}</td>
            <td class="centercol">{{$functiondatarow->total_seniority_days}}</td>
            <td class="centercol">{{$functiondatarow->seniority_days}}</td>
            @if(!(Auth::user()->readonly))
            <td class="centercol">@if($functiondatarow->archived_final === 1)<input type="checkbox" checked>@endif</td>
            <td class="centercol">@if($functiondatarow->archived_temporary === 1)<input type="checkbox" checked>@endif</td>
            <td class="centercol">@if($functiondatarow->archived_auto === 1)<input type="checkbox" checked>@endif</td>
            
            @endif
        </tr>
        @endforeach
        </table>
    </div>
    @elseif($listType == 2)
    <div class="row">
        <h2>Dit jaar recht op TADD</h2>
    </div>
    <div class="row">
        <table class="table">
        <tr>
            <th width="150px">Naam</th>
            <th width="100px">Voornaam</th>
            <th width="150px">Ambt</th>
            <th width="30px">TOT</th>
            <th width="30px">EFF</th>
            @if(!(Auth::user()->readonly))
            
            <th class="rotated" width="30px">Arch-P</th>
            <th class="rotated" width="30px">Arch-T</th>
            <th class="rotated" width="30px">Arch-A</th>
            
            @endif
        </tr>
        @foreach($listToShow as $functiondatarow2)
        <tr>
            <td>{{$functiondatarow2->lastname}}</td>
            <td>{{$functiondatarow2->firstname}}</td>
            <td>{{$functiondatarow2->ambt}}</td>
            <td>{{$functiondatarow2->total_seniority_days}}</td>
            <td>{{$functiondatarow2->seniority_days}}</td>
            @if(!(Auth::user()->readonly))
            
            <td class="centercol">@if($functiondatarow2->archived_final === 1)<input type="checkbox" checked>@endif</td>
            <td class="centercol">@if($functiondatarow2->archived_temporary === 1)<input type="checkbox" checked>@endif</td>
            <td class="centercol">@if($functiondatarow2->archived_auto === 1)<input type="checkbox" checked>@endif</td>
            
            
            @endif
        </tr>
        @endforeach
        </table>
    </div>
    @else
    <div class="row">
        <h2>Reeds TADD</h2>
    </div>
    <div class="row">
        <table class="table">
        <tr>
            <th>Naam</th>
            <th>Voornaam</th>
            <th>Ambt</th>
        </tr>
        @foreach($listToShow as $functiondatarow3)
        <tr>
            <td>{{$functiondatarow3->lastname}}</td>
            <td>{{$functiondatarow3->firstname}}</td>
            <td>{{$functiondatarow3->ambt}}</td>
        </tr>
        @endforeach
        </table>
    </div>
    @endif
</div>


@endsection