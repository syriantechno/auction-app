<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login - <?php echo e(\App\Models\SystemSetting::get('site_name', 'Motor Bazar')); ?></title>
    
    <!-- Design Foundation -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
        .form-card {
            background: white;
            border-radius: 48px;
            box-shadow: 0 50px 100px -30px rgba(0, 0, 0, 0.08), 0 30px 60px -30px rgba(255, 70, 5, 0.05);
            border: 1px solid rgba(255, 70, 5, 0.05);
        }
        .input-premium {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .input-premium:focus {
            background: white;
            border-color: #ff4605;
            box-shadow: 0 0 0 4px rgba(255, 70, 5, 0.1);
        }
        .orange-blob {
            background: #ff4605;
            border-radius: 50%;
            opacity: 0.1;
            filter: blur(2px);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 lg:p-0">
    
    <!-- Decorative Blobs -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -right-[10%] top-[10%] w-[60vw] h-[60vw] orange-blob"></div>
        <div class="absolute left-[5%] bottom-[10%] w-[30vw] h-[30vw] orange-blob opacity-5"></div>
    </div>

    <div class="relative z-10 w-full max-w-[1240px] mx-auto flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-0">
        
        <!-- Left: Login Context -->
        <div class="w-full lg:w-[45%] p-4 lg:p-8 order-2 lg:order-1">
            <div class="form-card w-full max-w-[500px] p-10 lg:p-14 text-left">
                
                <div class="mb-14">
                    <h2 class="text-4xl font-black text-[#031629] mb-3 tracking-tight uppercase italic underline decoration-bazar-500 decoration-[6px] underline-offset-[16px]">SIGN IN</h2>
                    <p class="text-[#031629]/60 font-bold text-[0.8rem] tracking-wide mt-8 uppercase italic">Access your automotive workspace</p>
                </div>

                <?php if($errors->any()): ?>
                    <div class="mb-8 p-5 rounded-lg bg-red-50 border border-red-100 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0"><i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i></div>
                        <div class="text-xs font-bold text-red-900 pt-2"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <div><?php echo e($error); ?></div> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('login')); ?>" method="POST" class="space-y-8">
                    <?php echo csrf_field(); ?>
                    
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <label for="email" class="text-[0.65rem] font-black text-[#031629]/40 uppercase tracking-widest block pl-1 italic">Email Identity</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-bazar-500 transition-transform group-focus-within:scale-110">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                </div>
                                <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required autofocus
                                    placeholder="name@domain.com"
                                    class="w-full pl-12 pr-4 py-5 input-premium rounded-3xl outline-none font-bold text-sm text-[#031629] placeholder:text-slate-200">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center px-1">
                                <label for="password" class="text-[0.65rem] font-black text-[#031629]/40 uppercase tracking-widest block pl-1 italic">Access Key</label>
                                <a href="#" class="text-[0.6rem] font-bold text-bazar-500 hover:text-[#031629] transition-colors uppercase tracking-widest italic">Forgot Password?</a>
                            </div>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-bazar-500 transition-transform group-focus-within:scale-110">
                                    <i data-lucide="lock" class="w-5 h-5"></i>
                                </div>
                                <input type="password" name="password" id="password" required 
                                    placeholder="••••••••••••"
                                    class="w-full pl-12 pr-4 py-5 input-premium rounded-3xl outline-none font-bold text-sm text-[#031629] placeholder:text-slate-200">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 px-1">
                        <input type="checkbox" name="remember" id="remember" class="w-5 h-5 rounded-lg border-slate-200 text-bazar-500 focus:ring-bazar-500 cursor-pointer transition-all">
                        <label for="remember" class="text-xs font-bold text-[#031629]/60 cursor-pointer select-none">Keep me logged in</label>
                    </div>

                    <div class="pt-6">
                        <button type="submit" 
                            class="w-full h-[74px] bg-bazar-500 hover:bg-bazar-600 rounded-full flex items-center justify-center gap-4 text-white font-black uppercase tracking-[0.2em] text-[1.1rem] shadow-2xl shadow-bazar-500/30 transform active:scale-[0.98] transition-all group">
                            <span>LOGIN NOW</span>
                            <i data-lucide="arrow-right" class="w-6 h-6 group-hover:translate-x-2 transition-transform"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-16 text-center text-xs font-bold text-slate-300 uppercase tracking-widest space-y-6">
                    <p class="flex items-center justify-center gap-2 italic">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-slate-200"></i>
                         Encrypted security layer Active
                    </p>
                    <div class="flex justify-center gap-8">
                        <a href="<?php echo e(route('register')); ?>" class="text-[#031629] hover:text-bazar-500 transition-colors">Create Account</a>
                        <span class="opacity-10 text-[#031629]">|</span>
                        <a href="#" class="text-[#031629] hover:text-bazar-500 transition-colors">Help Center</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Car & Branding -->
        <div class="w-full lg:w-[55%] flex flex-col items-center justify-center relative translate-x-12 z-20 order-1 lg:order-2">
            <!-- Branding Header -->
            <?php
                $siteName = \App\Models\SystemSetting::get('site_name', 'Motor Bazar');
                $siteLogo = \App\Models\SystemSetting::get('site_logo');
                $primaryWord = explode(' ', $siteName)[0] ?? 'Motor';
                $secondaryWord = explode(' ', $siteName)[1] ?? 'Bazar';
            ?>
            <div class="flex items-center gap-8 mb-16 lg:-ml-24">
                <div class="w-40 h-40 flex items-center justify-center text-[#031629]">
                    <?php if($siteLogo): ?>
                        <img src="<?php echo e(asset('storage/' . $siteLogo)); ?>" class="w-40 h-40 object-contain drop-shadow-2xl">
                    <?php else: ?>
                        <div class="w-32 h-32 rounded-[40px] bg-[#031629] shadow-2xl flex items-center justify-center overflow-hidden">
                            <span class="font-black italic text-6xl text-white"><?php echo e(strtoupper(substr($siteName, 0, 1))); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <h1 class="text-6xl font-black text-[#031629] uppercase italic leading-none tracking-tighter"><?php echo e($primaryWord); ?><span class="text-bazar-500"><?php echo e($secondaryWord); ?></span></h1>
                    <p class="text-[0.8rem] text-[#031629]/60 font-black uppercase tracking-[0.3em] mt-3 italic pl-1">Elite Auction Platform</p>
                </div>
            </div>

            <!-- Car Illustration -->
            <img src="<?php echo e(asset('images/cars/car-silver.png')); ?>" 
                class="w-full h-auto drop-shadow-[0_80px_100px_rgba(0,0,0,0.15)] animate-in fade-in zoom-in slide-in-from-right-20 duration-1000" 
                alt="Motor Bazar Elite Selection">
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    </script>
</body>
</html>
<?php /**PATH F:\auction_app\resources\views/auth/login.blade.php ENDPATH**/ ?>