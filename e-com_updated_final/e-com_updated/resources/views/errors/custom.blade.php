@extends('layouts.app')

@section('title', 'Error')

@section('content')
<div style="min-height: 60vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <div style="font-size: 4rem;">😢</div>
    <h1 style="margin-top: 1rem; color: #e3342f;">{{ $message ?? 'Oops! Something went wrong.' }}</h1>
    <p style="margin-top: 1rem; color: #555;">We apologize for the inconvenience. Please try again later or contact support if the issue persists.</p>
    <a href="/" style="margin-top: 2rem; padding: 0.75rem 2rem; background: #38c172; color: #fff; border-radius: 8px; text-decoration: none; font-weight: bold;">Go to Home</a>
</div>
@endsection
