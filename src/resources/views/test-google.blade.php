@extends('layouts.app')
@section('title', 'Google Button Test')
@section('content')
<div style="padding:40px;text-align:center;">
    <h2 style="margin-bottom:20px;">Google Button Test</h2>
    <a href="{{ route('auth.google.redirect') }}" style="display:inline-flex;align-items:center;gap:12px;padding:10px 24px;background:#fff;border:1px solid #dadce0;border-radius:4px;font-family:Roboto,sans-serif;font-size:14px;font-weight:500;color:#3c4043;text-decoration:none;">
        <img src="/icons/google.svg" alt="Google" width="18" height="18">
        Lanjutkan dengan Google
    </a>
</div>
@endsection
