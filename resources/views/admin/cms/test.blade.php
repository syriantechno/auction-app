@extends('admin.layout')

@section('title', 'CMS Test')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-4">CMS Test Page</h1>
    
    <div x-data="{ activeTab: 'brands' }">
        <!-- Navigation -->
        <div class="flex gap-4 mb-8">
            <button @click="activeTab = 'hero'" :class="activeTab === 'hero' ? 'bg-blue-500 text-white' : 'bg-gray-200'" class="px-4 py-2 rounded">
                Hero
            </button>
            <button @click="activeTab = 'brands'" :class="activeTab === 'brands' ? 'bg-blue-500 text-white' : 'bg-gray-200'" class="px-4 py-2 rounded">
                Brands
            </button>
        </div>

        <!-- Content -->
        <div x-show="activeTab === 'hero'" class="bg-green-100 p-4 rounded">
            <h2>Hero Content</h2>
            <p>This is the hero section.</p>
        </div>

        <div x-show="activeTab === 'brands'" class="bg-blue-100 p-4 rounded">
            <h2>Brands Content</h2>
            <p>This is the brands section.</p>
            
            <div class="mt-4">
                <h3>Registration Lead Brand Hub</h3>
                <div class="grid grid-cols-4 gap-2 mt-2">
                    <div class="bg-white p-2 rounded border">Mercedes</div>
                    <div class="bg-white p-2 rounded border">BMW</div>
                    <div class="bg-white p-2 rounded border">Audi</div>
                </div>
            </div>
            
            <div class="mt-4">
                <h3>Elite Brand Hub</h3>
                <div class="grid grid-cols-4 gap-2 mt-2">
                    <div class="bg-white p-2 rounded border">Mercedes</div>
                    <div class="bg-white p-2 rounded border">BMW</div>
                    <div class="bg-white p-2 rounded border">Audi</div>
                    <div class="bg-white p-2 rounded border">Porsche</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

