@extends('admin.layout')

@section('title', 'Communication Settings')
@section('page_title', 'Communication Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-20">

    {{-- Header --}}
    <div class="flex items-center gap-6 pb-8 border-b border-slate-100">
        <div class="w-14 h-14 rounded-2xl bg-[#1d293d] flex items-center justify-center shadow-xl">
            <i data-lucide="mail" class="w-7 h-7 text-[#ff6900]"></i>
        </div>
        <div>
            <h1 class="text-3xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                Communication <span class="text-[#ff6900]">Settings</span>
            </h1>
            <p class="text-[0.65rem] text-slate-400 font-black uppercase tracking-widest italic mt-1">
                Email & WhatsApp — Lead Confirmation Messages
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold flex items-center gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i> {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.communication.update') }}" class="space-y-6">
        @csrf

        {{-- ══════════════════════════ EMAIL SMTP ══════════════════════════ --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-50 space-y-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 border border-blue-100 flex items-center justify-center">
                    <i data-lucide="server" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest">SMTP Configuration</h2>
                    <p class="text-[0.6rem] text-slate-400 font-bold mt-0.5">Outgoing email server settings</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                @php $fields = [
                    ['mail_host',       'SMTP Host',        'smtp.gmail.com', 'text'],
                    ['mail_port',       'SMTP Port',        '587',            'number'],
                    ['mail_username',   'Username / Email', 'you@gmail.com',  'email'],
                    ['mail_password',   'Password / App Key','••••••••••',    'password'],
                    ['mail_from_address','From Address',    'no-reply@motorbazar.com','email'],
                    ['mail_from_name',  'From Name',        'Motor Bazar',    'text'],
                ]; @endphp

                @foreach($fields as [$key, $label, $placeholder, $type])
                <div class="space-y-2 {{ $key === 'mail_from_name' ? 'col-span-2' : '' }}">
                    <label class="text-[0.65rem] font-black uppercase text-slate-400 tracking-widest">{{ $label }}</label>
                    <input type="{{ $type }}" name="{{ $key }}"
                           value="{{ $settings[$key] ?? '' }}"
                           placeholder="{{ $placeholder }}"
                           class="w-full h-12 bg-slate-50 border-2 border-slate-100 rounded-2xl px-4 font-bold text-sm text-slate-700 outline-none focus:border-[#ff6900] transition-all"
                    >
                </div>
                @endforeach

                {{-- Encryption --}}
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black uppercase text-slate-400 tracking-widest">Encryption</label>
                    <select name="mail_encryption" class="w-full h-12 bg-slate-50 border-2 border-slate-100 rounded-2xl px-4 font-bold text-sm text-slate-700 outline-none focus:border-[#ff6900] transition-all">
                        @foreach(['tls' => 'TLS (Recommended)', 'ssl' => 'SSL', 'none' => 'None'] as $val => $lab)
                            <option value="{{ $val }}" {{ ($settings['mail_encryption'] ?? 'tls') === $val ? 'selected' : '' }}>{{ $lab }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Test button area --}}
                <div class="flex items-end">
                    <button type="button" onclick="sendTestEmail()"
                            class="w-full h-12 bg-blue-50 border-2 border-blue-100 text-blue-600 rounded-2xl font-black text-[0.65rem] uppercase tracking-widest hover:bg-blue-100 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i> Send Test Email
                    </button>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════ EMAIL TEMPLATE ══════════════════════════ --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-50 space-y-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 border border-indigo-100 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest">Email Template — Lead Confirmation</h2>
                    <p class="text-[0.6rem] text-slate-400 font-bold mt-0.5">Sent to the client when they submit a sell request</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black uppercase text-slate-400 tracking-widest">Email Subject</label>
                    <input type="text" name="email_lead_subject"
                           value="{{ $settings['email_lead_subject'] ?? 'We received your request — Motor Bazar' }}"
                           class="w-full h-12 bg-slate-50 border-2 border-slate-100 rounded-2xl px-4 font-bold text-sm text-slate-700 outline-none focus:border-[#ff6900] transition-all">
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="text-[0.65rem] font-black uppercase text-slate-400 tracking-widest">Email Body (overwrites default template)</label>
                        <span class="text-[0.55rem] text-slate-400 font-bold">Leave empty to use the default branded template</span>
                    </div>
                    <textarea name="email_lead_body" rows="5"
                              placeholder="Optional: Enter custom body text. Leave empty to use the branded HTML template."
                              class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 font-medium text-sm text-slate-700 outline-none focus:border-[#ff6900] transition-all resize-none">{{ $settings['email_lead_body'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════ WHATSAPP API ══════════════════════════ --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-50 space-y-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-500 border border-green-100 flex items-center justify-center">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest">WhatsApp API Configuration</h2>
                    <p class="text-[0.6rem] text-slate-400 font-bold mt-0.5">Supports Twilio, Meta Cloud API, or any custom provider</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                {{-- Provider selector --}}
                <div class="space-y-2 col-span-2">
                    <label class="text-[0.65rem] font-black uppercase text-slate-400 tracking-widest">Provider</label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['twilio' => ['Twilio','text-blue-600','bg-blue-50','border-blue-200'], 'meta' => ['Meta / WhatsApp Business','text-green-600','bg-green-50','border-green-200'], 'generic' => ['Custom HTTP API','text-slate-600','bg-slate-50','border-slate-200']] as $pVal => $pData)
                        <label class="relative flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer {{ $pData[2] }} {{ $pData[3] }} hover:opacity-90 transition-all">
                            <input type="radio" name="whatsapp_provider" value="{{ $pVal }}" class="hidden peer"
                                   {{ ($settings['whatsapp_provider'] ?? 'twilio') === $pVal ? 'checked' : '' }}>
                            <div class="w-4 h-4 rounded-full border-2 border-current {{ $pData[1] }} flex items-center justify-center {{ ($settings['whatsapp_provider'] ?? 'twilio') === $pVal ? 'border-4' : '' }}"></div>
                            <span class="text-[0.65rem] font-black uppercase tracking-widest {{ $pData[1] }}">{{ $pData[0] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                @php $waFields = [
                    ['whatsapp_api_url',    'API URL',                'https://api.twilio.com/...'],
                    ['whatsapp_api_key',    'API Key / Account SID',  'ACxxxxxxxxxxxxxxx'],
                    ['whatsapp_api_secret', 'API Secret / Auth Token','••••••••••••••••••'],
                    ['whatsapp_from',       'From Number',            'whatsapp:+14155238886'],
                ]; @endphp

                @foreach($waFields as [$key, $label, $placeholder])
                <div class="space-y-2 {{ $key === 'whatsapp_api_url' ? 'col-span-2' : '' }}">
                    <label class="text-[0.65rem] font-black uppercase text-slate-400 tracking-widest">{{ $label }}</label>
                    <input type="text" name="{{ $key }}"
                           value="{{ $settings[$key] ?? '' }}"
                           placeholder="{{ $placeholder }}"
                           class="w-full h-12 bg-slate-50 border-2 border-slate-100 rounded-2xl px-4 font-bold text-sm text-slate-700 outline-none focus:border-[#ff6900] transition-all"
                    >
                </div>
                @endforeach

                {{-- Test button --}}
                <div class="col-span-2 flex items-center gap-4">
                    <input type="text" id="wa-test-number" placeholder="+9665xxxxxxxx — Test number"
                           class="flex-1 h-12 bg-slate-50 border-2 border-slate-100 rounded-2xl px-4 font-bold text-sm text-slate-700 outline-none focus:border-[#ff6900] transition-all">
                    <button type="button" onclick="sendTestWhatsApp()"
                            class="h-12 px-6 bg-green-50 border-2 border-green-100 text-green-600 rounded-2xl font-black text-[0.65rem] uppercase tracking-widest hover:bg-green-100 transition-all flex items-center gap-2">
                        <i data-lucide="message-circle" class="w-4 h-4"></i> Send Test
                    </button>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════ WHATSAPP TEMPLATE ══════════════════════════ --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-50 space-y-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 border border-emerald-100 flex items-center justify-center">
                    <i data-lucide="message-square-text" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest">WhatsApp Message Template</h2>
                    <p class="text-[0.6rem] text-slate-400 font-bold mt-0.5">Sent to the client after their sell request</p>
                </div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-4 text-[0.65rem] font-bold text-slate-500 border border-slate-100">
                <span class="font-black text-slate-700">Available variables:</span>
                <span class="ml-2 text-[#ff6900]">{name}</span> ·
                <span class="text-[#ff6900]">{make}</span> ·
                <span class="text-[#ff6900]">{model}</span> ·
                <span class="text-[#ff6900]">{year}</span> ·
                <span class="text-[#ff6900]">{date}</span> ·
                <span class="text-[#ff6900]">{time}</span> ·
                <span class="text-[#ff6900]">{ref}</span>
            </div>

            <textarea name="whatsapp_lead_template" rows="8"
                      class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 font-medium text-sm text-slate-700 outline-none focus:border-[#ff6900] transition-all resize-none font-mono">{{ $settings['whatsapp_lead_template'] ?? "Hello {name}! 👋\n\nYour Motor Bazar request has been received.\n\n🚗 Vehicle: {year} {make} {model}\n📅 Inspection: {date} at {time}\n🔖 Ref: #{ref}\n\nOur team will contact you shortly. Thank you!" }}</textarea>
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full h-16 bg-[#1d293d] text-white rounded-2xl font-black shadow-2xl hover:bg-black active:scale-95 transition-all flex items-center justify-center gap-4 text-[0.75rem] uppercase tracking-[0.2em]">
            <i data-lucide="save" class="w-5 h-5 text-[#ff6900]"></i>
            Save Communication Settings
        </button>
    </form>
</div>

@push('scripts')
<script>
async function sendTestEmail() {
    const email = prompt('Enter your email address to receive a test email:');
    if (!email) return;

    const btn = event.currentTarget;
    btn.disabled = true;
    btn.textContent = 'Sending...';

    const res = await fetch('{{ route("admin.settings.communication.test-email") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
    });
    const data = await res.json();

    alert(data.message ?? (res.ok ? 'Test email sent!' : 'Failed to send.'));
    btn.disabled = false;
    btn.innerHTML = '<i data-lucide="send" class="w-4 h-4"></i> Send Test Email';
    lucide.createIcons();
}

async function sendTestWhatsApp() {
    const phone = document.getElementById('wa-test-number').value.trim();
    if (!phone) { alert('Enter a phone number first.'); return; }

    const res = await fetch('{{ route("admin.settings.communication.test-whatsapp") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
        body: JSON.stringify({ phone })
    });
    const data = await res.json();
    alert(data.message ?? (res.ok ? 'WhatsApp test sent!' : 'Failed to send.'));
}
</script>
@endpush
@endsection
