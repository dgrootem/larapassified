@extends('layouts.pdf')

@section('content')

<header>
    TADD calculator v2 - overzicht voor alle personeelsleden @isset($school) voor {{$school->abbreviation}} @endisset
</header>
    <footer>Gegenereerd op {{$gendate}} door {{Auth::user()->name}}</footer>
<div class="container">

    <i>
    Waneer de reële toestand voor een personeelslid niet overeenstemt met deze in de lijst kan u dit aankruisen in overeenkomstige extra kolom
    <ul>Verklaring extra kolommen: 
        <li>TADD : personeelslid is reeds TADD</li>
        <li>Benoemd : personeelslid is reeds vastbenoemd</li>
        <li>DuD : personeelslid is <u>D</u>efinitief <u>u</u>it <u>D</u>ienst (overleden, pensioen,...)</li>
        <li>Andere : ...</li>
    </ul>
    </i>
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
            <th class="spacercol"></th>
            <th class="rotated" width="20px">TADD</th>
            <th class="rotated" width="20px">Benoemd</th>
            <th class="rotated" width="20px">DuD</th>
            <th class="rotated" width="20px">Andere</th>
        </tr>
        @foreach($nextyear as $functiondatarow)
        <tr>
            <td>{{$functiondatarow->lastname}}</td>
            <td>{{$functiondatarow->firstname}}</td>
            <td>{{$functiondatarow->ambt}}</td>
            <td>{{$functiondatarow->total_seniority_days}}</td>
            <td>{{$functiondatarow->seniority_days}}</td>
            <td class="spacercol"></td>
            <td class="cbcol1"><input type="checkbox"></td>
            <td class="cbcol1"><input type="checkbox"></td>
            <td class="cbcol1"><input type="checkbox"></td>
            <td class="cbcol1"><input type="checkbox"></td>
        </tr>
        @endforeach
        </table>
    </div>
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
            <th class="spacercol"></th>
            <th class="rotated" width="20px">TADD</th>
            <th class="rotated" width="20px">Benoemd</th>
            <th class="rotated" width="20px">DuD</th>
            <th class="rotated" width="20px">Andere</th>
        </tr>
        @foreach($thisyear as $functiondatarow2)
        <tr>
            <td>{{$functiondatarow2->lastname}}</td>
            <td>{{$functiondatarow2->firstname}}</td>
            <td>{{$functiondatarow2->ambt}}</td>
            <td>{{$functiondatarow2->total_seniority_days}}</td>
            <td>{{$functiondatarow2->seniority_days}}</td>
            <td class="spacercol"></td>
            <td class="cbcol1"><input type="checkbox"></td>
            <td class="cbcol1"><input type="checkbox"></td>
            <td class="cbcol1"><input type="checkbox"></td>
            <td class="cbcol1"><input type="checkbox"></td>
        </tr>
        @endforeach
        </table>
    </div>
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
        @foreach($tadd as $functiondatarow3)
        <tr>
            <td>{{$functiondatarow3->lastname}}</td>
            <td>{{$functiondatarow3->firstname}}</td>
            <td>{{$functiondatarow3->ambt}}</td>
        </tr>
        @endforeach
        </table>
    </div>
</div>


@endsection