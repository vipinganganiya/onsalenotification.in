

<div class="modal-dialog" style="width: 90%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <div class="container-fluid" style="background: #09b109 !important;">
                <div class="pull-left">
                    <h4 class="modal-title">
                        <i class="<?php echo e($dataType->icon); ?>"></i> 
                        <?php echo e(__('voyager::generic.viewing')); ?> 
                        <?php echo e(ucfirst($dataType->getTranslatedAttribute('display_name_singular'))); ?> &nbsp; for: <?php echo e($data->profile_name); ?>

                    </h4>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('browse', $dataTypeContent)): ?>
                        <!-- <a href="<?php echo e(route('voyager.'.$dataType->slug.'.index')); ?>" class="btn btn-warning">
                            <i class="glyphicon glyphicon-list"></i> <span class="hidden-xs hidden-sm"><?php echo e(__('voyager::generic.return_to_list')); ?></span>
                        </a> -->
                    <?php endif; ?>  
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="border-color: #868484;">
                    <thead> 
                        <tr>
                            <th style="border-bottom-color: #868484;color:#918f8f;">Thread No.</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">Event Name</th>
                            <th style="border-bottom-color: #868484;border-right-color: #868484;color:#918f8f;">Status</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">Block</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">Row</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">Booked Seat</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">No. Of Seat</th>
                        </tr>
                    </thead>
                    <?php $__empty_1 = true; $__currentLoopData = $data->thread; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <thead> 
                        <tr>
                            <th style="border-bottom-color: #868484;color:#918f8f;"><?php echo e($list->thread_no); ?></th>
                            <th style="border-bottom-color: #868484;color:#918f8f;"><?php echo e($list->event_name); ?></th>
                            <th style="border-bottom-color: #868484;border-right-color: #868484;color:#918f8f;"><?php echo e($list->status); ?></th>
                            <th style="border-bottom-color: #868484;color:#918f8f;"><?php echo e($list->block); ?></th>
                            <th style="border-bottom-color: #868484;color:#918f8f;"><?php echo e($list->seat_row); ?></th>
                            <th style="border-bottom-color: #868484;color:#918f8f;"><?php echo e($list->booked_seat); ?></th>
                            <th style="border-bottom-color: #868484;color:#918f8f;"><?php echo e($list->no_of_seats); ?></th>
                        </tr>
                    </thead>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center" style="font-style: italic;">No Data Available</td>
                        </tr>
                    </tbody>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger view quote_cancel_btn" data-dismiss="modal">Close</button>
        </div>
    </div>
</div><?php /**PATH /home/adddepot/public_html/resources/views//vendor/voyager/bot-profiles/read.blade.php ENDPATH**/ ?>