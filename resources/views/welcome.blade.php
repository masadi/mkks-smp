@extends('layouts.app')
@section('content')
    <!-- Navbar -->
    @include('layouts.admin.nav')
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    @include('layouts.admin.content')
    <!-- /.content-wrapper -->

    @include('layouts.admin.footer')

@endsection