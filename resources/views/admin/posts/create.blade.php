@extends('admin.layout')

@section('title', 'Compose New Article')

@section('content')
<div class="px-1 max-w-6xl mx-auto">
    <form id="post-form" action="{{ route('admin.posts.store') }}" method="POST">
        @csrf
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-black text-[#111827] mb-1 tracking-tight">Compose Editorial</h1>
                <p class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest leading-none underline decoration-zinc-800 decoration-2 italic italic">Content production pipeline</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 bg-white text-[#5a6a85] rounded-md font-bold border border-[#f1f5f9] shadow-sm hover:bg-gray-50 flex items-center gap-2 text-xs transition-all">
                    <i data-lucide="arrow-left" class="w-3.5"></i> Back to Vault
                </a>
                <button type="submit" class="px-6 py-3 bg-black text-white rounded-md font-black shadow-lg hover:bg-zinc-800 transition-all flex items-center gap-2 text-xs uppercase tracking-widest">
                    <i data-lucide="send" class="w-4"></i> Publish Official Post
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Editorial Core --}}
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 text-black flex items-center justify-center border border-zinc-100 shadow-sm">
                            <i data-lucide="type" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Draft Profile & Identity</h2>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Article Title Header</label>
                            <input type="text" name="title" placeholder="Providing an elite perspective..." class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-3 rounded-md font-black text-[#111827] text-sm focus:border-zinc-300 outline-none transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Technical Body Content</label>
                            <textarea name="content_raw" rows="18" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-4 rounded-md font-bold text-[#111827] text-sm outline-none placeholder:text-gray-400 focus:border-black/10 resize-none font-mono" placeholder="Begin the strategic narrative here..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Metadata & Logistics --}}
            <div class="space-y-6">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center border border-blue-100">
                            <i data-lucide="layers" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Categories</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Editorial Category</label>
                            <select name="category_id" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-3 rounded-md font-black text-[#111827] text-sm outline-none">
                                <option value="" disabled selected>— Choose Segment —</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-400 flex items-center justify-center border border-indigo-100 shadow-sm">
                            <i data-lucide="image" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Branding Assets</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="aspect-video bg-gray-50 rounded-md border border-dashed border-gray-200 flex flex-col items-center justify-center text-center p-4">
                            <i data-lucide="upload-cloud" class="w-8 text-gray-300 mb-2"></i>
                            <span class="text-[0.6rem] text-gray-400 font-black uppercase">Cover Identification</span>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Public Asset URL</label>
                            <input type="url" name="featured_image" placeholder="https://external-resource.img/..." class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-3 rounded-md font-bold text-[#111827] text-sm outline-none border-0 shadow-inner">
                        </div>
                    </div>
                </div>

                {{-- Status Deployment --}}
                <div class="bg-[#111827] rounded-lg p-6 shadow-xl border border-[#111827] ring-4 ring-zinc-900">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-zinc-800 text-[#d9e685] flex items-center justify-center border border-white/10 shadow-sm">
                            <i data-lucide="activity" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-white uppercase tracking-wider">Deployment Life</h2>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-zinc-800 rounded-md mb-4 border border-white/5">
                        <span class="text-[0.7rem] text-gray-400 font-black uppercase tracking-widest leading-none">Internal Live Release</span>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_published" value="1" class="w-5 h-5 rounded border-zinc-700 bg-zinc-900 text-[#d9e685] focus:ring-0 focus:ring-offset-0">
                        </div>
                    </div>
                    <p class="text-[0.55rem] text-gray-600 font-extrabold uppercase italic">Activating public visibility will expose this content to global search crawlers & users.</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

