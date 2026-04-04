<?php $__env->startSection('title', isset($post) ? 'Edit — ' . $post->title : 'New Article'); ?>

<?php $__env->startPush('head'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@3.24.5/build/jodit.min.css">
<script src="https://cdn.jsdelivr.net/npm/jodit@3.24.5/build/jodit.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="pb-20 space-y-5">

    
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-100">
        <div class="flex items-center gap-5">
            <div class="relative">
                <div class="w-14 h-14 rounded-lg bg-[#1d293d] flex items-center justify-center shadow-xl transform rotate-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-lg bg-rose-500 border-[3px] border-[#f8fafc]"></div>
            </div>
            <div>
                <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                    <?php echo e(isset($post) ? 'Edit' : 'New'); ?> <span class="text-[#ff6900]">Article</span>
                </h1>
                <p class="text-slate-400 font-bold text-[0.65rem] uppercase tracking-[0.2em] italic opacity-80 mt-2.5">
                    <?php echo e(isset($post) ? 'Editing: ' . $post->title : 'Write and publish a new blog post'); ?>

                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <?php if(isset($post) && $post->is_published): ?>
            <a href="<?php echo e(route('blog.show', $post->slug)); ?>" target="_blank"
               class="px-4 py-2.5 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-600 flex items-center gap-2 text-[0.6rem] font-black uppercase tracking-widest transition-all hover:bg-emerald-500 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                View Live
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('admin.posts.index')); ?>"
               class="px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-[#1d293d] flex items-center gap-2 text-[0.6rem] font-black uppercase tracking-widest transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back
            </a>
        </div>
    </div>

    <?php if($errors->any()): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-lg text-[0.72rem] font-bold">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div>• <?php echo e($error); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    
    <form id="postForm"
          action="<?php echo e(isset($post) ? route('admin.posts.update', $post) : route('admin.posts.store')); ?>"
          method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($post)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-5 items-start">

            
            <div class="xl:col-span-3 space-y-5">

                
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <div class="text-[0.58rem] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Article Identity</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Title <span class="text-red-400">*</span></label>
                            <input type="text" name="title" id="postTitle"
                                   value="<?php echo e(old('title', $post->title ?? '')); ?>" required
                                   class="w-full h-10 bg-[#f0f2f5] border border-slate-200 rounded-lg px-3 text-[0.82rem] font-bold text-[#1d293d] outline-none focus:border-[#ff6900] focus:bg-white transition-all"
                                   placeholder="e.g. Best Cars to Bid on in 2025">
                        </div>
                        <div>
                            <label class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">URL Slug</label>
                            <div class="flex items-center gap-2">
                                <span class="text-[0.7rem] text-slate-400 font-mono">/blog/</span>
                                <input type="text" id="postSlug" readonly
                                       value="<?php echo e(old('slug', $post->slug ?? '')); ?>"
                                       class="flex-1 h-10 bg-[#f0f2f5] border border-slate-200 rounded-lg px-3 text-[0.75rem] font-mono text-slate-500 outline-none cursor-not-allowed">
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="flex items-center gap-3 px-5 py-3 bg-slate-50 border-b border-slate-200">
                        <div class="w-6 h-6 rounded-md bg-white border border-slate-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#1d293d" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                        </div>
                        <div class="text-[0.6rem] font-black text-[#1d293d] uppercase tracking-widest">Article Content</div>
                        <span class="ml-auto text-[0.48rem] font-black bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded-full uppercase tracking-widest">Rich Editor</span>
                    </div>
                    <textarea name="content_raw" id="postContent"><?php echo e(old('content_raw', $post->content['body'] ?? '')); ?></textarea>
                </div>

                
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <div class="text-[0.58rem] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">SEO Settings</div>
                    <label class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Meta Description</label>
                    <textarea name="meta_description" rows="3" maxlength="320"
                              class="w-full bg-[#f0f2f5] border border-slate-200 rounded-lg px-4 py-3 text-[0.78rem] text-slate-700 outline-none focus:border-[#ff6900] focus:bg-white transition-all resize-none"
                              placeholder="120–160 chars for best SEO results"><?php echo e(old('meta_description', $post->meta_description ?? '')); ?></textarea>
                </div>

            </div>

            
            <div class="space-y-4 sticky top-6">

                
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
                    <div class="text-[0.58rem] font-black uppercase tracking-[0.2em] text-slate-400">Visibility</div>
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <div class="text-[0.78rem] font-black text-[#1d293d]">Publish</div>
                            <div class="text-[0.55rem] text-slate-400">Make publicly visible</div>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="is_published" value="1" class="sr-only peer"
                                   <?php echo e(old('is_published', $post->is_published ?? false) ? 'checked' : ''); ?>>
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:bg-emerald-500 transition-colors"></div>
                            <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                    <button type="submit"
                            class="w-full py-3 bg-[#ff6900] text-white rounded-lg text-[0.62rem] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-[#e55e00] transition-all shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        <?php echo e(isset($post) ? 'Save Changes' : 'Publish Article'); ?>

                    </button>
                </div>

                
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <div class="text-[0.58rem] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">Category</div>
                    <select name="category_id"
                            class="w-full h-10 bg-[#f0f2f5] border border-slate-200 rounded-lg px-3 text-[0.78rem] font-medium text-slate-700 appearance-none outline-none focus:border-[#ff6900] focus:bg-white transition-all">
                        <option value="">— Uncategorized —</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id', $post->category_id ?? '') == $cat->id ? 'selected' : ''); ?>>
                            <?php echo e($cat->name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <a href="<?php echo e(route('admin.categories.index')); ?>"
                       class="block text-center mt-2 text-[0.55rem] font-black text-slate-400 hover:text-[#ff6900] uppercase tracking-widest transition-colors">
                        + Manage Categories
                    </a>
                </div>

                
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-2">
                        <div class="text-[0.55rem] font-black uppercase tracking-[0.28em] text-slate-400 flex items-center gap-2">
                            <div class="w-3 h-px bg-slate-300"></div> Featured Image
                        </div>
                    </div>

                    
                    <div id="featuredDropZone"
                         class="relative cursor-pointer group"
                         onclick="document.getElementById('featuredFileInput').click()"
                         ondragover="event.preventDefault();this.classList.add('border-[#ff4605]','bg-orange-50')"
                         ondragleave="this.classList.remove('border-[#ff4605]','bg-orange-50')"
                         ondrop="handleFeaturedDrop(event)">

                        
                        <div id="featuredPreview" class="<?php echo e((isset($post) && $post->featured_image) ? '' : 'hidden'); ?> relative">
                            <img id="featuredPreviewImg"
                                 src="<?php echo e($post->featured_image ?? ''); ?>"
                                 class="w-full h-36 object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-[0.65rem] font-black uppercase tracking-wider">Change Image</span>
                            </div>
                            <button type="button" id="featuredClearBtn"
                                    onclick="event.stopPropagation();clearFeaturedImage()"
                                    class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 transition-all shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>

                        
                        <div id="featuredEmpty" class="<?php echo e((isset($post) && $post->featured_image) ? 'hidden' : ''); ?> p-6 flex flex-col items-center justify-center gap-2 border-2 border-dashed border-slate-200 m-3 rounded-xl hover:border-[#ff4605] transition-all">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-slate-400"><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"/><polyline points="15 3 21 3 21 9"/><path d="m10 13 3.086-3.086a2 2 0 0 1 2.828 0L20 14"/></svg>
                            </div>
                            <div class="text-center">
                                <div class="text-[0.65rem] font-black text-slate-700 uppercase tracking-wide">Drop article image or click</div>
                                <div class="text-[0.55rem] text-slate-400 font-bold uppercase tracking-widest mt-1">JPG / PNG / WebP · Max 4MB</div>
                            </div>
                        </div>

                        <input type="file" id="featuredFileInput" name="featured_image_file"
                               accept="image/*" class="hidden"
                               onchange="handleFeaturedFile(this.files[0])">
                    </div>

                    
                    <div class="px-4 pb-4 pt-2 border-t border-slate-50 mt-2">
                        <div class="text-[0.58rem] font-black uppercase tracking-widest text-slate-400 mb-2">Or paste URL</div>
                        <input type="text" name="featured_image" id="featuredUrlInput"
                               value="<?php echo e(old('featured_image', $post->featured_image ?? '')); ?>"
                               class="w-full h-9 bg-slate-50 border border-slate-200 rounded-lg px-3 text-[0.7rem] font-mono text-slate-600 outline-none focus:border-[#ff6900] focus:bg-white transition-all shadow-sm"
                               placeholder="https://..."
                               oninput="if(this.value){showFeaturedPreview(this.value)}">
                    </div>
                </div>

                
                <div class="bg-[#f0f2f5] rounded-xl border border-slate-200 p-4 space-y-2">
                    <div class="text-[0.55rem] font-black uppercase tracking-widest text-slate-400 mb-2">Quick Links</div>
                    <a href="<?php echo e(route('admin.posts.index')); ?>" class="flex items-center gap-2 text-[0.68rem] font-bold text-slate-600 hover:text-[#ff6900] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                        All Articles
                    </a>
                    <a href="<?php echo e(route('admin.categories.index')); ?>" class="flex items-center gap-2 text-[0.68rem] font-bold text-slate-600 hover:text-[#ff6900] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        Categories
                    </a>
                    <a href="<?php echo e(route('blog.index')); ?>" target="_blank" class="flex items-center gap-2 text-[0.68rem] font-bold text-slate-600 hover:text-[#ff6900] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        View Blog
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Slug preview (auto from title) ──
    document.getElementById('postTitle')?.addEventListener('input', function () {
        const slug = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').trim();
        document.getElementById('postSlug').value = slug;
    });

    // ── Jodit Editor ──
    if (typeof Jodit !== 'undefined') {
        const editor = Jodit.make('#postContent', {
            height: 500,
            minHeight: 300,
            toolbarAdaptive: false,
            toolbarSticky: true,
            showCharsCounter: true,
            showWordsCounter: true,
            showXPathInStatusbar: false,
            uploader: { insertImageAsBase64URI: true },
            buttons: [
                'undo','redo','|',
                'bold','italic','underline','strikethrough','|',
                'ul','ol','|','outdent','indent','|',
                'font','fontsize','brush','paragraph','|',
                'image','table','link','|',
                'align','|','hr','eraser','|','fullsize','source'
            ],
            style: { font: '15px/1.8 sans-serif', color: '#334155' },
        });
        document.getElementById('postForm').addEventListener('submit', function () {
            document.getElementById('postContent').value = editor.value;
        });
    }

    // ── Featured Image Upload ─────────────────────────────────────────────
    window.showFeaturedPreview = function(src) {
        document.getElementById('featuredPreviewImg').src = src;
        document.getElementById('featuredPreview').classList.remove('hidden');
        document.getElementById('featuredEmpty').classList.add('hidden');
    };
    window.clearFeaturedImage = function() {
        document.getElementById('featuredPreviewImg').src = '';
        document.getElementById('featuredPreview').classList.add('hidden');
        document.getElementById('featuredEmpty').classList.remove('hidden');
        document.getElementById('featuredFileInput').value = '';
        document.getElementById('featuredUrlInput').value = '';
    };
    window.handleFeaturedFile = function(file) {
        if (!file) return;
        if (file.size > 4 * 1024 * 1024) {
            alert('Image too large — max 4MB.');
            return;
        }
        const reader = new FileReader();
        reader.onload = e => showFeaturedPreview(e.target.result);
        reader.readAsDataURL(file);
        // Clear URL field since we're using file
        document.getElementById('featuredUrlInput').value = '';
    };
    window.handleFeaturedDrop = function(e) {
        e.preventDefault();
        e.currentTarget.classList.remove('border-[#ff4605]','bg-orange-50');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            document.getElementById('featuredFileInput').files = e.dataTransfer.files;
            handleFeaturedFile(file);
        }
    };
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/posts/create.blade.php ENDPATH**/ ?>