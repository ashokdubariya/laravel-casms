@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-lightbg flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8 text-center">
            <div class="mb-6">
                <div class="mx-auto w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                    <span class="text-4xl">⏰</span>
                </div>
            </div>

            <h1 class="text-2xl font-medium text-dark mb-4">Link Expired</h1>
            
            <p class="text-gray-700 mb-6">
                This approval link has expired and is no longer valid.
            </p>

            <div class="pt-6 border-t border-gray-200">
                <p class=" text-gray-600">
                    Please contact the sender to request a new approval link.
                </p>
            </div>
        </div>

        <div class="mt-6 text-center  text-gray-500">
            <p>Powered by {{ config('app.name') }}</p>
        </div>
    </div>
</div>
@endsection
