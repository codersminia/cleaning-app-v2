@extends('layouts.app')

@section('title', 'Location')

@section('content')
    <location-component :prospect='@json($prospect)'></location-component>
@endsection
