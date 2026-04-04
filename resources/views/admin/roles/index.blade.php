@extends('admin.layout')
@section('title', 'Roles & Permissions')
@section('page_title', 'Roles & Permissions')

@section('content')
<div class="space-y-6">

    {{-- ═══ Header ═══ --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-[1.1rem] font-black text-[#031629] italic">Access Control</h2>
            <p class="text-[0.7rem] text-slate-400 font-medium mt-0.5">Manage roles, permissions & user assignments</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.roles.users') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-[0.72rem] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>User Assignments</span>
            </a>
            <a href="{{ route('admin.roles.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#031629] text-[0.72rem] font-black uppercase tracking-widest text-white hover:bg-[#1d293d] transition-all shadow-md">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>New Role</span>
            </a>
        </div>
    </div>

    {{-- ═══ Roles Grid ═══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($roles as $role)
        @php
            $colors = [
                'super-admin'     => ['bg' => 'bg-[#ff6900]',    'text' => 'text-white',       'badge' => 'bg-orange-100 text-orange-700',  'icon' => '👑'],
                'admin'           => ['bg' => 'bg-[#031629]',    'text' => 'text-white',       'badge' => 'bg-slate-100 text-slate-700',     'icon' => '🛡️'],
                'inspector'       => ['bg' => 'bg-blue-600',     'text' => 'text-white',       'badge' => 'bg-blue-50 text-blue-700',        'icon' => '🔍'],
                'dealer'          => ['bg' => 'bg-emerald-600',  'text' => 'text-white',       'badge' => 'bg-emerald-50 text-emerald-700',  'icon' => '🤝'],
                'finance-manager' => ['bg' => 'bg-violet-600',   'text' => 'text-white',       'badge' => 'bg-violet-50 text-violet-700',    'icon' => '💰'],
            ];
            $c = $colors[$role->name] ?? ['bg' => 'bg-slate-700', 'text' => 'text-white', 'badge' => 'bg-slate-100 text-slate-600', 'icon' => '⚙️'];
        @endphp
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-all">
            {{-- Card Header --}}
            <div class="{{ $c['bg'] }} px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ $c['icon'] }}</span>
                    <div>
                        <div class="{{ $c['text'] }} text-[0.9rem] font-black uppercase tracking-wide">
                            {{ str_replace('-', ' ', $role->name) }}
                        </div>
                        <div class="{{ $c['text'] }} opacity-70 text-[0.6rem] font-bold uppercase tracking-widest">
                            {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }} assigned
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="{{ $c['text'] }} text-2xl font-black opacity-90">{{ $role->permissions_count }}</div>
                    <div class="{{ $c['text'] }} text-[0.55rem] opacity-60 font-bold uppercase tracking-widest">permissions</div>
                </div>
            </div>

            {{-- Permissions Preview --}}
            <div class="px-6 py-4">
                <div class="flex flex-wrap gap-1.5 max-h-20 overflow-hidden">
                    @foreach($role->permissions->take(10) as $perm)
                        <span class="px-2 py-0.5 text-[0.55rem] font-black uppercase {{ $c['badge'] }} rounded-full tracking-wide">
                            {{ $perm->name }}
                        </span>
                    @endforeach
                    @if($role->permissions_count > 10)
                        <span class="px-2 py-0.5 text-[0.55rem] font-black text-slate-400 bg-slate-50 rounded-full">
                            +{{ $role->permissions_count - 10 }} more
                        </span>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-6 pb-5 flex items-center justify-between border-t border-slate-50 pt-4">
                <a href="{{ route('admin.roles.edit', $role) }}"
                   class="flex items-center gap-1.5 text-[0.65rem] font-black uppercase tracking-widest text-slate-500 hover:text-[#031629] transition-all">
                    <i data-lucide="settings-2" class="w-3.5 h-3.5"></i>
                    Edit Permissions
                </a>
                @if(!in_array($role->name, ['super-admin', 'admin']))
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                      onsubmit="return confirm('Delete role {{ $role->name }}? Users will lose this role.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex items-center gap-1.5 text-[0.65rem] font-black uppercase tracking-widest text-red-400 hover:text-red-600 transition-all">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        Delete
                    </button>
                </form>
                @else
                <span class="text-[0.6rem] font-black text-slate-300 uppercase tracking-widest italic">Protected</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
