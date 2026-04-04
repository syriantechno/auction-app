@extends('admin.layout')
@section('title', 'User Assignments')
@section('page_title', 'User Role Assignments')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-[1rem] font-black text-[#031629] italic">User Assignments</h2>
            <p class="text-[0.65rem] text-slate-400 font-medium mt-0.5">Assign or change roles for each user</p>
        </div>
        <a href="{{ route('admin.roles.index') }}"
           class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-[0.72rem] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Roles</span>
        </a>
    </div>

    {{-- Role Legend --}}
    <div class="flex flex-wrap gap-2">
        @php
            $roleBadges = [
                'super-admin'     => 'bg-[#ff6900] text-white',
                'admin'           => 'bg-[#031629] text-white',
                'inspector'       => 'bg-blue-600 text-white',
                'dealer'          => 'bg-emerald-600 text-white',
                'finance-manager' => 'bg-violet-600 text-white',
            ];
        @endphp
        @foreach($roles as $role)
        <span class="px-3 py-1 text-[0.6rem] font-black uppercase tracking-widest rounded-full {{ $roleBadges[$role->name] ?? 'bg-slate-600 text-white' }}">
            {{ str_replace('-', ' ', $role->name) }}
        </span>
        @endforeach
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-6 py-4 text-left text-[0.6rem] font-black uppercase tracking-widest text-slate-400">User</th>
                        <th class="px-6 py-4 text-left text-[0.6rem] font-black uppercase tracking-widest text-slate-400">Email</th>
                        <th class="px-6 py-4 text-left text-[0.6rem] font-black uppercase tracking-widest text-slate-400">Current Role</th>
                        <th class="px-6 py-4 text-left text-[0.6rem] font-black uppercase tracking-widest text-slate-400">Assign Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-all">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#031629] flex items-center justify-center text-white text-xs font-black shadow-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-[0.78rem] font-black text-[#031629]">{{ $user->name }}</div>
                                    <div class="text-[0.6rem] text-slate-400 font-medium">ID #{{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[0.72rem] text-slate-500 font-medium">{{ $user->email }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($user->roles as $r)
                                <span class="px-2.5 py-1 text-[0.6rem] font-black uppercase tracking-widest rounded-full {{ $roleBadges[$r->name] ?? 'bg-slate-600 text-white' }}">
                                    {{ str_replace('-', ' ', $r->name) }}
                                </span>
                                @empty
                                <span class="px-2.5 py-1 text-[0.6rem] font-bold text-slate-400 bg-slate-100 rounded-full uppercase tracking-widest">
                                    No Role
                                </span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.roles.assign', $user) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <select name="role" class="text-[0.72rem] font-semibold border border-slate-200 rounded-lg px-3 py-1.5 focus:border-[#ff6900] focus:ring-2 focus:ring-orange-500/10 outline-none">
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ str_replace('-', ' ', ucwords($role->name)) }}
                                    </option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                        class="px-3 py-1.5 text-[0.6rem] font-black uppercase tracking-widest bg-[#031629] text-white rounded-lg hover:bg-[#1d293d] transition-all">
                                    Assign
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-50">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
