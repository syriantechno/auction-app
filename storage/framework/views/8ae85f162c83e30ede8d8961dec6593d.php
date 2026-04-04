<?php
// Edit reuses the same create form template
// The $post variable is already passed from PostController::edit()
?>
<?php echo $__env->make('admin.posts.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH D:\auction_app\resources\views/admin/posts/edit.blade.php ENDPATH**/ ?>