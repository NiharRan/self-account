@extends('layouts.master')

@section('title')
    <title>{{ config('app.name') }} || Transaction Type List</title>
@endsection

@section('page-header')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
            <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('transactiontypes.index') }}">Transaction Types</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>        
            </div>
        </div>
    </div>
@endsection


@section('content')
<div class="row">
    <div class="col">
        <transaction-type-list></transaction-type-list>
    </div>
</div>
@endsection