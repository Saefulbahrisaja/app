@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            

            @if(session('success'))
                <div class="mx-6 mt-6">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-6">
                <form action="{{ route('markup.update', $markup->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <!-- Input Field -->
                    <div>
                        <label for="percentage" class="block text-sm font-semibold text-gray-700 mb-2">
                            Persentase Markup
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                step="0.01" 
                                name="percentage" 
                                id="percentage" 
                                value="{{ $markup->percentage }}" 
                                class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg font-medium"
                                required
                                aria-describedby="percentage-help"
                                placeholder="0.00"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm font-medium">%</span>
                            </div>
                        </div>
                        <div class="mt-2 flex items-start">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p id="percentage-help" class="text-sm text-gray-600">
                                Nilai markup akan digunakan untuk menghitung harga jual otomatis dari HPP (Harga Pokok Produksi).
                            </p>
                        </div>
                    </div>

                    <!-- Current Value Display -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Nilai Saat Ini:</span>
                            <span class="text-lg font-bold text-blue-600">{{ number_format($markup->percentage, 2) }}%</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Simpan Perubahan
                        </span>
                    </button>
                </form>
            </div>

            <!-- Footer Note -->
            <div class="bg-gray-50 px-6 py-4">
                <p class="text-xs text-gray-500 text-center">
                    Pastikan nilai markup sesuai dengan strategi penetapan harga Anda
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
