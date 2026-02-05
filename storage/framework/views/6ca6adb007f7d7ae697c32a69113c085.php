<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-0">SEO Entries</h1>
            <small class="text-muted">Manage page titles and meta descriptions</small>
        </div>

        <div class="d-flex align-items-center">
            <form method="GET" class="mr-2" style="min-width:260px;">
                <div class="input-group">
                    <input type="search" name="q" value="<?php echo e(request('q')); ?>" class="form-control" placeholder="Search page, title or description...">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
            </form>

            <a href="<?php echo e(route('admin.seo.create')); ?>" class="btn btn-success">Create</a>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Page</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th style="width:100px">Default</th>
                            <th style="width:160px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($item->id); ?></td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->is_site_default): ?>
                                        <span class="badge badge-info">Site default</span>
                                    <?php else: ?>
                                        <code><?php echo e($item->page_slug ?? '-'); ?></code>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td style="max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?php echo e($item->title ?? '-'); ?></td>
                                <td style="max-width:420px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?php echo e(\Illuminate\Support\Str::limit($item->meta_description ?? '', 140)); ?></td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->is_site_default): ?>
                                        <span class="badge badge-success">Yes</span>
                                    <?php else: ?>
                                        <span class="text-muted">No</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-outline-secondary mr-1" data-toggle="modal" data-target="#seoPreviewModal" data-title="<?php echo e(e($item->title)); ?>" data-description="<?php echo e(e($item->meta_description)); ?>" data-slug="<?php echo e(e($item->is_site_default ? 'Site default' : $item->page_slug)); ?>">Preview</button>
                                    <a href="<?php echo e(route('admin.seo.edit', $item)); ?>" class="btn btn-sm btn-primary mr-1">Edit</a>
                                    <button class="btn btn-sm btn-danger js-delete" data-action="<?php echo e(route('admin.seo.destroy', $item)); ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing <?php echo e($items->count()); ?> of <?php echo e($items->total()); ?> entries</small>
            <div><?php echo e($items->appends(request()->query())->links()); ?></div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="seoPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SEO Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="mb-2 text-muted"><small>Slug: <span id="preview-slug"></span></small></div>
                <div class="border p-3 rounded">
                    <h5 id="preview-title" class="mb-1">Title Placeholder</h5>
                    <div class="text-primary mb-2"><?php echo e(url('/')); ?></div>
                    <p id="preview-description" class="text-muted mb-0">Meta description placeholder</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this SEO entry?
            </div>
            <div class="modal-footer">
                <form id="delete-form" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        // Preview modal population
        $('#seoPreviewModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            $('#preview-title').text(button.data('title') || '(no title)');
            $('#preview-description').text(button.data('description') || '(no description)');
            $('#preview-slug').text(button.data('slug') || '-');
        });

        // Delete button handler
        document.querySelectorAll('.js-delete').forEach(function(btn){
            btn.addEventListener('click', function(){
                var action = this.getAttribute('data-action');
                var form = document.getElementById('delete-form');
                form.setAttribute('action', action);
                $('#deleteConfirmModal').modal('show');
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/seo/index.blade.php ENDPATH**/ ?>