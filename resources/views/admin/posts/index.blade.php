@extends('admin.layout')

@section('title', 'Content Management')

@section('content')
<div class="px-1">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-[#111827] mb-1 tracking-tight">Editorial Hub</h1>
            <p class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest leading-none">Global SEO & Content Strategy</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="px-6 py-2.5 bg-black text-white rounded-md font-black shadow-lg hover:bg-zinc-800 transition-all flex items-center gap-2 text-xs">
            <i data-lucide="pen-tool" class="w-3.5"></i> Draft New Article
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-md mb-6 font-bold border border-emerald-100 flex items-center gap-2 text-xs">
            <i data-lucide="check-circle" class="w-4"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-[#f1f5f9] overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#f1f5f9] bg-[#f8fafc]">
                    <th class="text-left text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Asset Preview</th>
                    <th class="text-left text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Editorial Listing</th>
                    <th class="text-left text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest text-center">Lifecycle Status</th>
                    <th class="text-left text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Editorial Timeline</th>
                    <th class="text-right text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f1f5f9]">
                @forelse($posts as $post)
                <tr class="hover:bg-[#fbfcfe] transition-all">
                    <td class="py-4 px-6">
                        <div class="w-12 h-12 rounded-md overflow-hidden bg-gray-50 border border-gray-100 shadow-sm flex items-center justify-center">
                            @if($post->featured_image)
                                <img src="{{ $post->featured_image }}" class="w-full h-full object-cover">
                            @else
                                <i data-lucide="image" class="w-4 text-gray-200"></i>
                            @endif
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-black text-[0.85rem] text-[#111827] mb-1 line-clamp-1">{{ $post->title }}</div>
                        <div class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">{{ optional($post->category)->name ?? 'Uncategorized' }}</div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        @if($post->is_published)
                            <span class="px-2.5 py-1 rounded-full text-[0.55rem] font-black uppercase tracking-widest border border-emerald-100 bg-emerald-50 text-emerald-600">Published Live</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[0.55rem] font-black uppercase tracking-widest border border-gray-100 bg-gray-50 text-gray-400">Internal Draft</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-[0.7rem] text-[#adb5bd] font-bold tabular-nums">
                        {{ optional($post->published_at)->format('M d, Y') ?? $post->updated_at->format('M d, Y') }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                             <a href="#" class="w-8 h-8 rounded-lg bg-gray-50 text-[#5a6a85] flex items-center justify-center hover:bg-zinc-100 transition-all border border-gray-100 shadow-sm"><i data-lucide="eye" class="w-3.5"></i></a>
                            <a href="{{ route('admin.posts.edit', $post) }}" class="w-8 h-8 rounded-lg bg-gray-50 text-[#5a6a85] flex items-center justify-center hover:bg-[#111827] hover:text-white transition-all shadow-sm"><i data-lucide="edit" class="w-3.5"></i></a>
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Erase this content entry?')" class="w-8 h-8 rounded-lg bg-red-50 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm"><i data-lucide="trash-2" class="w-3.5"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-20 text-center text-[#adb5bd] font-black uppercase tracking-widest text-[0.7rem]">Editorial vault is empty. No articles produced.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($posts->hasPages())
        <div class="mt-6 px-1">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection

