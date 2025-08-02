@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-6 max-w-4xl px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">AI Blog Generator</h1>
        
        <form method="POST" action="{{ route('ai.generate') }}" class="mb-6">
            @csrf
            <div class="mb-4">
                <label for="prompt" class="block text-sm font-medium text-gray-700 mb-2">
                    Enter your blog title or topic:
                </label>
                <input 
                    type="text" 
                    name="prompt" 
                    id="prompt" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="e.g., The Future of Artificial Intelligence"
                    value="{{ old('prompt') }}"
                    required
                    minlength="5"
                    maxlength="255"
                >
                @error('prompt')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <button 
                type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md transition duration-200 ease-in-out"
            >
                Generate Blog Post
            </button>
        </form>

        @if($errors->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <strong>Error:</strong> {{ $errors->first('error') }}
            </div>
        @endif

        @isset($output)
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Generated Blog Post:</h2>
                <div class="prose max-w-none">
                    <div class="whitespace-pre-wrap text-gray-700 leading-relaxed">{{ $output }}</div>
                </div>
            </div>
        @endisset
    </div>
</div>
@endsection
