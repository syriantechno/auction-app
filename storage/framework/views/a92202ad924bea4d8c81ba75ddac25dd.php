<?php $__env->startSection('title', 'Roles & Permissions'); ?>
<?php $__env->startSection('page_title', 'Roles & Permissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-[1.1rem] font-black text-[#031629] italic">Access Control</h2>
            <p class="text-[0.7rem] text-slate-400 font-medium mt-0.5">Manage roles, permissions & user assignments</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('admin.roles.users')); ?>"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-[0.72rem] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>User Assignments</span>
            </a>
            <a href="<?php echo e(route('admin.roles.create')); ?>"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#031629] text-[0.72rem] font-black uppercase tracking-widest text-white hover:bg-[#1d293d] transition-all shadow-md">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>New Role</span>
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $colors = [
                'super-admin'     => ['bg' => 'bg-[#ff6900]',    'text' => 'text-white',       'badge' => 'bg-orange-100 text-orange-700',  'icon' => '👑'],
                'admin'           => ['bg' => 'bg-[#031629]',    'text' => 'text-white',       'badge' => 'bg-slate-100 text-slate-700',     'icon' => '🛡️'],
                'inspector'       => ['bg' => 'bg-blue-600',     'text' => 'text-white',       'badge' => 'bg-blue-50 text-blue-700',        'icon' => '🔍'],
                'dealer'          => ['bg' => 'bg-emerald-600',  'text' => 'text-white',       'badge' => 'bg-emerald-50 text-emerald-700',  'icon' => '🤝'],
                'finance-manager' => ['bg' => 'bg-violet-600',   'text' => 'text-white',       'badge' => 'bg-violet-50 text-violet-700',    'icon' => '💰'],
            ];
            $c = $colors[$role->name] ?? ['bg' => 'bg-slate-700', 'text' => 'text-white', 'badge' => 'bg-slate-100 text-slate-600', 'icon' => '⚙️'];
        ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-all">
            
            <div class="<?php echo e($c['bg']); ?> px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl"><?php echo e($c['icon']); ?></span>
                    <div>
                        <div class="<?php echo e($c['text']); ?> text-[0.9rem] font-black uppercase tracking-wide">
                            <?php echo e(str_replace('-', ' ', $role->name)); ?>

                        </div>
                        <div class="<?php echo e($c['text']); ?> opacity-70 text-[0.6rem] font-bold uppercase tracking-widest">
                            <?php echo e($role->users_count); ?> <?php echo e(Str::plural('user', $role->users_count)); ?> assigned
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="<?php echo e($c['text']); ?> text-2xl font-black opacity-90"><?php echo e($role->permissions_count); ?></div>
                    <div class="<?php echo e($c['text']); ?> text-[0.55rem] opacity-60 font-bold uppercase tracking-widest">permissions</div>
                </div>
            </div>

            
            <div class="px-6 py-4">
                <div class="flex flex-wrap gap-1.5 max-h-20 overflow-hidden">
                    <?php $__currentLoopData = $role->permissions->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="px-2 py-0.5 text-[0.55rem] font-black uppercase <?php echo e($c['badge']); ?> rounded-full tracking-wide">
                            <?php echo e($perm->name); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($role->permissions_count > 10): ?>
                        <span class="px-2 py-0.5 text-[0.55rem] font-black text-slate-400 bg-slate-50 rounded-full">
                            +<?php echo e($role->permissions_count - 10); ?> more
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="px-6 pb-5 flex items-center justify-between border-t border-slate-50 pt-4">
                <a href="<?php echo e(route('admin.roles.edit', $role)); ?>"
                   class="flex items-center gap-1.5 text-[0.65rem] font-black uppercase tracking-widest text-slate-500 hover:text-[#031629] transition-all">
                    <i data-lucide="settings-2" class="w-3.5 h-3.5"></i>
                    Edit Permissions
                </a>
                <?php if(!in_array($role->name, ['super-admin', 'admin'])): ?>
                <form action="<?php echo e(route('admin.roles.destroy', $role)); ?>" method="POST"
                      onsubmit="return confirm('Delete role <?php echo e($role->name); ?>? Users will lose this role.')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="flex items-center gap-1.5 text-[0.65rem] font-black uppercase tracking-widest text-red-400 hover:text-red-600 transition-all">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        Delete
                    </button>
                </form>
                <?php else: ?>
                <span class="text-[0.6rem] font-black text-slate-300 uppercase tracking-widest italic">Protected</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>