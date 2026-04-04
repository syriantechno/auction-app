@extends('admin.layout')
@section('title', 'Edit Role: ' . $role->name)
@section('page_title', 'Edit Role')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.roles.index') }}" class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 transition-all">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h2 class="text-[1rem] font-black text-[#031629] italic">Edit: {{ str_replace('-', ' ', strtoupper($role->name)) }}</h2>
            <p class="text-[0.65rem] text-slate-400 font-medium">{{ count($rolePermissions) }} of {{ $permissions->flatten()->count() }} permissions assigned</p>
        </div>
    </div>

    @if($role->name === 'super-admin')
    <div class="bg-orange-50 border border-orange-200 rounded-2xl px-6 py-4 flex items-center gap-3">
        <span class="text-xl">👑</span>
        <div>
            <div class="text-[0.7rem] font-black text-orange-700 uppercase tracking-widest">Super Admin — All Permissions</div>
            <div class="text-[0.6rem] text-orange-600 font-medium mt-0.5">This role always has all permissions. Permissions cannot be individually revoked.</div>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        {{-- Role Name --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <label class="block text-[0.7rem] font-black uppercase tracking-widest text-slate-500 mb-2">Role Name</label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}"
                   class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all
                   {{ in_array($role->name, ['super-admin','admin']) ? 'opacity-50 cursor-not-allowed bg-slate-50' : '' }}"
                   {{ in_array($role->name, ['super-admin','admin']) ? 'readonly' : '' }}>
            @error('name')<p class="text-red-500 text-[0.65rem] mt-1 font-bold">{{ $message }}</p>@enderror
        </div>

        {{-- Permissions --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <div class="text-[0.7rem] font-black uppercase tracking-widest text-slate-500">Permissions</div>
                    <div class="text-[0.6rem] text-slate-400 font-medium mt-0.5">
                        <span id="checked-count">{{ count($rolePermissions) }}</span> selected
                    </div>
                </div>
                @if($role->name !== 'super-admin')
                <button type="button" onclick="toggleAll()" id="toggle-all-btn"
                        class="px-3 py-1.5 text-[0.6rem] font-black uppercase tracking-widest bg-slate-50 rounded-lg text-slate-500 hover:bg-[#031629] hover:text-white transition-all">
                    Toggle All
                </button>
                @endif
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
                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border cursor-pointer transition-all group
                               {{ in_array($permission->name, $rolePermissions) ? 'border-[#ff6900]/40 bg-orange-50/50' : 'border-slate-100 hover:border-[#ff6900]/30 hover:bg-orange-50/30' }}">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                   class="perm-checkbox w-4 h-4 rounded accent-[#ff6900]"
                                   {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                   {{ $role->name === 'super-admin' ? 'disabled' : '' }}
                                   onchange="updateCount()">
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
                Save Changes
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let allSelected = {{ count($rolePermissions) === $permissions->flatten()->count() ? 'true' : 'false' }};
function toggleAll() {
    allSelected = !allSelected;
    document.querySelectorAll('.perm-checkbox:not([disabled])').forEach(cb => cb.checked = allSelected);
    updateCount();
}
function updateCount() {
    const count = document.querySelectorAll('.perm-checkbox:checked').length;
    document.getElementById('checked-count').textContent = count;
    // Visual update for labels
    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        const label = cb.closest('label');
        if (cb.checked) {
            label.classList.add('border-[#ff6900]/40', 'bg-orange-50/50');
            label.classList.remove('border-slate-100');
        } else {
            label.classList.remove('border-[#ff6900]/40', 'bg-orange-50/50');
            label.classList.add('border-slate-100');
        }
    });
}
</script>
@endpush
@endsection
