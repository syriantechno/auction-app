@extends('admin.layout')

@section('title', 'Global Branding Settings')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 bg-[#ff4605]/10 rounded-lg flex items-center justify-center text-[#ff4605]">
            <i data-lucide="image" class="w-6 h-6"></i>
        </div>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Branding Identity</h1>
            <p class="text-sm text-slate-500 font-medium">Manage your platform's global logo and visual assets.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center gap-3 text-emerald-700 font-bold text-sm animate-in fade-in slide-in-from-top-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Sidebar explanation -->
        <div class="md:col-span-1">
            <h3 class="text-sm font-bold text-slate-900 mb-2">Universal Logo</h3>
            <p class="text-xs text-slate-500 leading-relaxed">This image will appear in the main navigation of the public website and the top-left corner of the admin dashboard.</p>
            
            <div class="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-md">
                <div class="flex gap-2 text-amber-800 mb-1">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <span class="text-[0.65rem] font-bold uppercase tracking-wider">Specifications</span>
                </div>
                <ul class="text-[0.65rem] text-amber-700 space-y-1 font-medium">
                    <li>• SVG highly recommended</li>
                    <li>• Transparent background</li>
                    <li>• Max size: 2MB</li>
                </ul>
            </div>
        </div>

        <!-- Settings Form -->
        <div class="md:col-span-2">
            <form action="{{ route('admin.settings.logo.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                @csrf
                <div class="p-8">
                    <div class="mb-8">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Site Name</label>
                        <input type="text" name="site_name" value="{{ old('site_name', $siteName) }}" class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-slate-50 text-slate-900 font-bold outline-none focus:border-[#ff4605] focus:ring-4 focus:ring-[#ff4605]/10 transition-all" placeholder="Enter site name">
                        @error('site_name')
                            <p class="mt-2 text-[0.65rem] text-red-500 font-bold ml-1 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Current Branding</label>
                        <div class="w-full h-40 bg-slate-50 rounded-lg border-2 border-dashed border-slate-200 flex items-center justify-center relative overflow-hidden group">
                            @if($logo)
                                <img src="{{ asset('storage/' . $logo) }}" class="max-w-[70%] max-h-[70%] object-contain transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-slate-200 rounded-md flex items-center justify-center mx-auto mb-3">
                                        <i data-lucide="upload-cloud" class="w-6 h-6 text-slate-400"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-400 italic">No custom logo uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Upload New Asset</label>
                        <input type="file" name="site_logo" id="site_logo" class="hidden">
                        <label for="site_logo" class="flex items-center gap-4 p-4 bg-slate-50 border border-slate-200 rounded-lg cursor-pointer hover:border-[#ff4605]/30 hover:bg-[#ff4605]/5 transition-all group">
                            <div class="w-10 h-10 bg-white rounded-md border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-[#ff4605] group-hover:border-[#ff4605]/20 group-hover:shadow-sm transition-all">
                                <i data-lucide="plus" class="w-5 h-5"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-900">Select Image File</p>
                                <p class="text-[0.65rem] text-slate-400 font-medium mt-0.5">Click here or drag & drop</p>
                            </div>
                        </label>
                        @error('site_logo')
                            <p class="mt-2 text-[0.65rem] text-red-500 font-bold ml-1 italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="bg-[#1d293d] text-white px-8 py-3 rounded-md text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-xl shadow-black/10 flex items-center gap-2">
                        Save Branding <i data-lucide="save" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('site_logo').onchange = function() {
        if(this.files && this.files[0]) {
            const fileName = this.files[0].name;
            const labelP = this.parentElement.querySelector('p.text-xs');
            const subLabelP = this.parentElement.querySelector('p.text-[0.65rem]');
            labelP.textContent = 'File selected:';
            subLabelP.textContent = fileName;
            subLabelP.classList.remove('text-slate-400');
            subLabelP.classList.add('text-[#ff4605]');
        }
    };
</script>
@endsection

