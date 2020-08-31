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
            <li>TOT-0 : startwaarde voor totaal gewerkte dagen voor een personeelslid in een bepaalde functie</li>
            <li>OND-0 : startwaarde voor aantal dagen onderbreking voor een personeelslid in een bepaalde functie</li>
            <li>Arch-P : personeelslid is permanent gearchiveerd </li>
            <li>Arch-T : personeelslid is tijdelijk gearchiveerd</li>
            <li>Arch-A : het systeem heeft dit personeelslid automatisch als gearchiveerd gemarkeerd</li>
    </ul>
    Waneer de reële toestand voor een personeelslid niet overeenstemt met deze in de lijst kan u dit aankruisen in overeenkomstige extra kolom
    <ul><b>Verklaring extra kolommen: </b>
        <li>TADD : personeelslid is reeds TADD</li>
        <li>Benoemd : personeelslid is reeds vastbenoemd</li>
        <li>DuD : personeelslid is <u>D</u>efinitief <u>u</u>it <u>D</u>ienst (overleden, pensioen,...)</li>
        <li>Andere : ...</li>
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
            <th class="rotated" width="30px">TOT-0</th>
            <th class="rotated" width="30px">OND-0</th>
            <th class="rotated" width="30px">Arch-P</th>
            <th class="rotated" width="30px">Arch-T</th>
            <th class="rotated" width="30px">Arch-A</th>
            <th class="spacercol"></th>
            <th class="rotated" width="20px">TADD</th>
            <th class="rotated" width="20px">Benoemd</th>
            <th class="rotated" width="20px">DuD</th>
            <th class="rotated" width="20px">Andere</th>
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
            <td class="centercol">{{$functiondatarow->startwaarde_tot}}</td>
            <td class="centercol">{{$functiondatarow->startwaarde_int}}</td>
            <td class="centercol">@if($functiondatarow->archived_final === 1)<input type="checkbox" checked>@endif</td>
            <td class="centercol">@if($functiondatarow->archived_temporary === 1)<input type="checkbox" checked>@endif</td>
            <td class="centercol">@if($functiondatarow->archived_auto === 1)<input type="checkbox" checked>@endif</td>
            <td class="spacercol"></td>
            <td class="cbcol1"><input type="checkbox"></td>
            <td class="cbcol1"><input type="checkbox"></td>
            <td class="cbcol1"><input type="checkbox"></td>
            <td class="cbcol1"><input type="checkbox"></td>
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
            <th class="rotated" width="30px">TOT-0</th>
            <th class="rotated" width="30px">OND-0</th>
            <th class="rotated" width="30px">Arch-P</th>
            <th class="rotated" width="30px">Arch-T</th>
            <th class="rotated" width="30px">Arch-A</th>
            <th class="spacercol"></th>
            <th class="rotated" width="20px">TADD</th>
            <th class="rotated" width="20px">Benoemd</th>
            <th class="rotated" width="20px">DuD</th>
            <th class="rotated" width="20px">Andere</th>
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
            <td class="centercol">{{$functiondatarow2->startwaarde_tot}}</td>
            <td class="centercol">{{$functiondatarow2->startwaarde_int}}</td>
            <td class="centercol">@if($functiondatarow2->archived_final === 1)<input type="checkbox" checked>@endif</td>
            <td class="centercol">@if($functiondatarow2->archived_temporary === 1)<input type="checkbox" checked>@endif</td>
            <td class="centercol">@if($functiondatarow2->archived_auto === 1)<input type="checkbox" checked>@endif</td>
            <td class="spacercol"></td>
            <td class="cbcol1"><input type="checkbox"></td>
            <td class="cbcol1"><input type="checkbox"></td>
            <td class="cbcol1"><input type="checkbox"></td>
            <td class="cbcol1"><input type="checkbox"></td>
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