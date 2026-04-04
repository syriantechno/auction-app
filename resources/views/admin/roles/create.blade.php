@extends('admin.layout')
@section('title', 'Create Role')
@section('page_title', 'Create New Role')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.roles.index') }}" class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 transition-all">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h2 class="text-[1rem] font-black text-[#031629] italic">New Role</h2>
            <p class="text-[0.65rem] text-slate-400 font-medium">Define a new access role with custom permissions</p>
        </div>
    </div>

    <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Role Name --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <label class="block text-[0.7rem] font-black uppercase tracking-widest text-slate-500 mb-2">Role Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   placeholder="e.g. content-editor, warehouse-manager"
                   class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all"
                   required>
            <p class="text-[0.6rem] text-slate-400 mt-2 font-medium">Use lowercase with hyphens. Example: <code class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-600">finance-manager</code></p>
            @error('name')<p class="text-red-500 text-[0.65rem] mt-1 font-bold">{{ $message }}</p>@enderror
        </div>

        {{-- Permissions --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <div class="text-[0.7rem] font-black uppercase tracking-widest text-slate-500">Permissions</div>
                    <div class="text-[0.6rem] text-slate-400 font-medium mt-0.5">Select which actions this role can perform</div>
                </div>
                <button type="button" onclick="toggleAll()" id="toggle-all-btn"
                        class="px-3 py-1.5 text-[0.6rem] font-black uppercase tracking-widest bg-slate-50 rounded-lg text-slate-500 hover:bg-[#031629] hover:text-white transition-all">
                    Select All
                </button>
            </div>

            <div class="space-y-6">
                @foreach($permissions as $group => $groupPerms)
                @php
                    $groupIcons = [
                        'dashboard'     => '📊', 'leads'     => '📋', 'inspections' => '🔍',
                        'cars'          => '🚗', 'auctions'  => '🔨', 'stock'       => '📦',
                        'dealers'       => '🤝', 'finance'   => '💰', 'cms'         => '📝',
                        'posts'         => '📰', 'pages'     => '📄', 'menus'       => '☰',
                        'seo'           => '🎯', 'settings'  => '⚙️', 'notifications'=> '🔔',
                        'roles'         => '🛡️', 'users'     => '👥',
                    ];
                    $icon = $groupIcons[$group] ?? '⚡';
                @endphp
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-base">{{ $icon }}</span>
                        <span class="text-[0.7rem] font-black uppercase tracking-widest text-[#031629]">{{ ucfirst($group) }}</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($groupPerms as $permission)
                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-100 hover:border-[#ff6900]/30 hover:bg-orange-50/30 cursor-pointer transition-all group">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                   class="perm-checkbox w-4 h-4 rounded accent-[#ff6900]"
                                   {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                            <span class="text-[0.65rem] font-bold text-slate-600 group-hover:text-[#031629]">
                                {{ str_replace($group . '.', '', $permission->name) }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.roles.index') }}"
               class="px-6 py-2.5 text-[0.72rem] font-black uppercase tracking-widest text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">
                Cancel
            </a>
            <button type="submit"
                    class="px-8 py-2.5 text-[0.72rem] font-black uppercase tracking-widest text-white bg-[#031629] rounded-xl hover:bg-[#1d293d] transition-all shadow-md">
                Create Role
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let allSelected = false;
function toggleAll() {
    allSelected = !allSelected;
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = allSelected);
    document.getElementById('toggle-all-btn').textContent = allSelected ? 'Deselect All' : 'Select All';
}
</script>
@endpush
@endsection
