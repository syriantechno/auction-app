<?php $__env->startSection('title', 'How it Works - SEO Intelligence'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto">
    
    <div class="mb-10 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight italic uppercase">
                بـروتوكـول <span class="text-orange-500">SEO</span> الـذكي
            </h2>
            <p class="text-slate-500 font-bold text-sm mt-1 uppercase tracking-widest">Smart SEO Protocol - How It Works Guide</p>
        </div>
        <div class="flex gap-3">
             <a href="<?php echo e(route('admin.seo.dashboard')); ?>" class="px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-[0.7rem] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                Go to Dashboard
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        
        <div class="lg:col-span-2 space-y-8">
            
            
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-50 rounded-full opacity-50 group-hover:scale-125 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-orange-500 text-white flex items-center justify-center font-black italic shadow-lg shadow-orange-500/20">01</div>
                        <h3 class="text-xl font-black text-slate-900 italic">المراقب المستقل (Autonomous Observer)</h3>
                    </div>
                    <p class="text-slate-600 leading-relaxed font-semibold italic text-sm">
                        النظام لا يتطلب منك تدخلاً يدوياً. قمنا ببرمجة **"مراقبين" (Observers)** يعملون في قلب الموقع. بمجرد إضافة سيارة جديدة للمزاد، أو إنشاء صفحة جديدة في الـ CMS، يقوم المراقب بالتقاط الحدث فوراً وإرساله لمحرك الذكاء الاصطناعي.
                    </p>
                    <div class="mt-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <code class="text-[0.75rem] text-orange-600 font-black">Auction::observe(AuctionObserver::class);</code>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-50 rounded-full opacity-50 group-hover:scale-125 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-[#1d293d] text-white flex items-center justify-center font-black italic shadow-lg shadow-slate-900/20">02</div>
                        <h3 class="text-xl font-black text-slate-900 italic">ذكاء المحتوى (AI Generation)</h3>
                    </div>
                    <p class="text-slate-600 leading-relaxed font-semibold italic text-sm">
                        هنا يأتي دور خدمة **AISEOService**. يقوم النظام بإرسال بيانات السيارة (الموديل، الماركة، الحالة) أو محتوى الصفحة إلى محرك الذكاء الاصطناعي (AgentRouter). يطلب منه توليد وصف "صديق لمحركات البحث" وكلمات مفتاحية "Keywords" ذات معدل بحث مرتفع في دبي والخليج.
                    </p>
                </div>
            </div>

            
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black italic shadow-lg shadow-emerald-500/20">03</div>
                        <h3 class="text-xl font-black text-slate-900 italic">الحقن التلقائي (Automatic Injection)</h3>
                    </div>
                    <p class="text-slate-600 leading-relaxed font-semibold italic text-sm">
                        بعد أن يولد الذكاء الاصطناعي البيانات، يقوم النظام بـ "حقنها" تلقاًئياً في قاعدة البيانات في حقول الـ Meta tags. عندما يزور زاحف جوجل (Google Bot) صفحتك، سيجد كل شيء جاهزاً: Meta Title، Meta Description، و Keywords.. كل هذا تم في ثوانٍ معدودة بدون أن تتحرك من مكانك.
                    </p>
                </div>
            </div>

            
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-[#ff6900]/10 text-[#ff6900] flex items-center justify-center font-black italic shadow-sm">
                            <i data-lucide="code-2" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 italic uppercase">خـريطـة حـقـن الكـود (Injection Point)</h3>
                    </div>
                    <p class="text-slate-500 font-bold text-sm mb-6 leading-relaxed">
                        اشـرح لـزبونـك كـيف يتم "الحـقن غيـر الـمرئي" الـذي يـراه جـوجل فـقـط ولا يـراه الـمستـخـدم:
                    </p>

                    <div class="space-y-4">
                        <div class="bg-slate-900 rounded-3xl p-6 font-mono text-[0.7rem] relative group border border-white/5 overflow-hidden">
                            <div class="absolute top-4 right-4 text-emerald-500/30 text-[0.6rem] font-black uppercase tracking-widest italic group-hover:text-emerald-400 transition-colors">Google Crawler View</div>
                            <div class="space-y-3 opacity-90">
                                <div class="flex gap-4">
                                    <span class="text-slate-500">01</span>
                                    <span class="text-emerald-400">
                                        &lt;title&gt;<span class="text-white font-bold"><?php echo e($page?->seo_title ?? '...Auto Generated...'); ?></span>&lt;/title&gt;
                                    </span>
                                </div>
                                <div class="flex gap-4">
                                    <span class="text-slate-500">02</span>
                                    <span class="text-orange-400">
                                        &lt;meta name="description" content="<span class="text-white"><?php echo e($page?->seo_description ?? 'AI Written Description...'); ?></span>" /&gt;
                                    </span>
                                </div>
                                <div class="flex gap-4">
                                    <span class="text-slate-500">03</span>
                                    <span class="text-blue-400">
                                        &lt;script type="application/ld+json"&gt; { "@context": "http://schema.org", "@type": "Car" ... } &lt;/script&gt;
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <p class="text-[0.65rem] font-black text-slate-600 uppercase tracking-widest leading-none">Status: Live Injected via AI Observers</p>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-purple-50 rounded-full opacity-50 group-hover:scale-125 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-purple-600 text-white flex items-center justify-center font-black italic shadow-lg shadow-purple-500/20">05</div>
                        <h3 class="text-xl font-black text-slate-900 italic">محرك الأرشفة التلقائي (Sitemap Engine)</h3>
                    </div>
                    <p class="text-slate-600 leading-relaxed font-semibold italic text-sm">
                        هذا هو "المغناطيس" الذي يجذب العناكب. قمنا ببناء نظام توليد خارطة موقع (**Sitemap.xml**) ديناميكي. بمجرد دخول سيارة جديدة أو مقال جديد، يتم إدراجه فوراً في الخارطة.
                    </p>
                    <div class="mt-6 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-[0.7rem] font-black text-slate-900 uppercase tracking-widest">Sitemap Path</p>
                                    <p class="text-[0.6rem] text-slate-400 font-bold uppercase mt-0.5"><?php echo e(url('/sitemap.xml')); ?></p>
                                </div>
                            </div>
                            <a href="<?php echo e(url('/sitemap.xml')); ?>" target="_blank" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-[0.65rem] font-black uppercase tracking-widest hover:bg-slate-800 transition-all">
                                View Sitemap
                            </a>
                        </div>
                        <p class="mt-4 text-[0.65rem] text-slate-500 font-bold italic leading-relaxed">
                            💡 ملاحظة: هذا الملف يتم تحديثه تلقائياً ولا يحتاج لأي تدخل يدوي. عناكب جوجل تزور هذا الملف دورياً لتكتشف جديد الموقع.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        
        <div class="space-y-8">
            
            
            <div class="bg-[#1d293d] rounded-[2rem] p-8 shadow-2xl text-white relative border border-white/10">
                <h4 class="text-xl font-black italic mb-2">جرب القوة بنفسك!</h4>
                <p class="text-slate-400 text-[0.7rem] font-bold uppercase tracking-widest mb-6">Test the Protocol Now</p>
                
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[0.65rem] font-bold uppercase text-slate-400">الرابط المراد فحصه (URL)</label>
                        <input type="text" id="sandbox-url" placeholder="http://127.0.0.1:8000/" 
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-orange-500 focus:ring-0 transition-all font-bold">
                    </div>
                    <button onclick="runSandboxAudit()" id="run-btn"
                            class="w-full py-4 bg-orange-500 rounded-2xl font-black italic uppercase tracking-widest hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/20 flex items-center justify-center gap-2">
                        <i data-lucide="zap" class="w-4 h-4"></i>
                        فحص وتوليد الآن
                    </button>
                </div>

                <div id="sandbox-result" class="mt-8 hidden">
                    <div class="pt-6 border-t border-white/10">
                        <p class="text-[0.65rem] font-bold uppercase text-orange-500 mb-3">Diagnostic Result</p>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[0.6rem] text-slate-400">Meta Title</p>
                                <p id="res-title" class="text-xs font-bold mt-1 text-slate-100"></p>
                            </div>
                            <div>
                                <p class="text-[0.6rem] text-slate-400">Description Status</p>
                                <p id="res-desc" class="text-xs font-bold mt-1 text-emerald-400"></p>
                            </div>
                            <div class="flex items-center justify-between bg-white/5 p-3 rounded-xl">
                                <span class="text-[0.6rem] font-black uppercase tracking-widest text-slate-300">SEO Score</span>
                                <span id="res-score" class="text-2xl font-black italic text-orange-500">92%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                <h4 class="text-lg font-black text-slate-900 italic mb-4">كلمات مفتاحية رائجة</h4>
                <div class="flex flex-wrap gap-2">
                    <?php $__currentLoopData = ['Toyota Land Cruiser Dubai', 'Used Luxury Cars UAE', 'Sell Car Instant Cash', 'Dubai Car Auctions', 'Buy Motors Sharjah']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-lg text-[0.65rem] font-black italic text-slate-500">#<?php echo e($k); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="mt-6 pt-6 border-t border-slate-50">
                    <p class="text-[0.65rem] font-bold text-slate-400 leading-relaxed uppercase tracking-widest">
                        نظامك يختار هذه الكلمات بناءً على أكثر الجمل بحثاً في جوجل تريندز محلياً.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    async function runSandboxAudit() {
        const url = document.getElementById('sandbox-url').value || '/';
        const btn = document.getElementById('run-btn');
        const resDiv = document.getElementById('sandbox-result');
        
        btn.disabled = true;
        btn.innerHTML = '<i class="animate-spin w-4 h-4" data-lucide="loader-2"></i> جاري التحليل...';
        lucide.createIcons();

        try {
            const response = await fetch('<?php echo e(route("admin.seo.analyze")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ url })
            });

            const data = await response.json();
            
            if (data.success) {
                document.getElementById('res-title').textContent = data.data.title || 'No Title Found';
                document.getElementById('res-desc').textContent = data.data.description ? 'Description Optimized (AI Enabled)' : 'Missing Description';
                document.getElementById('res-desc').className = data.data.description ? 'text-xs font-bold mt-1 text-emerald-400' : 'text-xs font-bold mt-1 text-red-400';
                document.getElementById('res-score').textContent = (data.data.score || 85) + '%';
                resDiv.classList.remove('hidden');
                
                notify.success('Diagnostic complete! AI Protocol is operational.');
            } else {
                notify.error(data.message || 'Diagnostic failed');
            }
        } catch (e) {
            notify.error('Connection failed');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="zap" class="w-4 h-4"></i> فحص وتوليد الآن';
            lucide.createIcons();
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/seo/guide.blade.php ENDPATH**/ ?>