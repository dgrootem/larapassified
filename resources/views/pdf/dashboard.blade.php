@extends('layouts.pdf')

@section('content')

<header>
    TADD calculator v2 - overzicht voor alle personeelsleden @isset($school) voor {{$school->abbreviation}} @endisset
</header>
    <footer>Gegenereerd op {{$gendate}} door {{Auth::user()->name}}</footer>
<div class="container">
    <div class="row">
        <h2>Volgend jaar recht op TADD</h2>
    </div>
    <div class="row">
        <table class="table">
        <tr>
            <th>Naam</th>
            <th>Voornaam</th>
            <th>Ambt</th>
            <th>TOT</th>
            <th>EFF</th>
        </tr>
        @foreach($nextyear as $functiondatarow)
        <tr>
            <td>{{$functiondatarow->lastname}}</td>
            <td>{{$functiondatarow->firstname}}</td>
            <td>{{$functiondatarow->ambt}}</td>
            <td>{{$functiondatarow->total_seniority_days}}</td>
            <td>{{$functiondatarow->seniority_days}}</td>
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
            <th>Naam</th>
            <th>Voornaam</th>
            <th>Ambt</th>
            <th>TOT</th>
            <th>EFF</th>
        </tr>
        @foreach($thisyear as $functiondatarow2)
        <tr>
            <td>{{$functiondatarow2->lastname}}</td>
            <td>{{$functiondatarow2->firstname}}</td>
            <td>{{$functiondatarow2->ambt}}</td>
            <td>{{$functiondatarow2->total_seniority_days}}</td>
            <td>{{$functiondatarow2->seniority_days}}</td>
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