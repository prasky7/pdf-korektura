@extends('layouts.app')

@section('title', 'Přihlášení - PDF Korektura')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-800">PDF Korektura</h1>
            <p class="text-gray-500 mt-2">Přihlaste se pomocí AD účtu nebo lokálního účtu</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Uživatelské jméno / Email</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500"
                       placeholder="AD: jnovak  |  Lokální: admin">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Heslo</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-slate-600 border-gray-300 rounded">
                <label for="remember" class="ml-2 text-sm text-gray-600">Zapamatovat si mě</label>
            </div>

            <button type="submit" class="w-full bg-slate-800 text-white py-2 px-4 rounded-md hover:bg-slate-700 transition font-medium">
                Přihlásit se
            </button>
        </form>
    </div>
</div>
@endsection
