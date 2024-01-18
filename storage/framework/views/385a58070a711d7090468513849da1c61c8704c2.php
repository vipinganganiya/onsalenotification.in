

<div class="modal-dialog" style="width: 90%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <div class="container-fluid" style="background: #09b109 !important;">
                <div class="pull-left">
                    <h4 class="modal-title">
                        <i class="<?php echo e($dataType->icon); ?>"></i> 
                        <?php echo e(__('voyager::generic.viewing')); ?> 
                        <?php echo e(ucfirst($dataType->getTranslatedAttribute('display_name_singular'))); ?> &nbsp;
                    </h4>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('browse', $dataTypeContent)): ?>
                        <a href="<?php echo e(route('voyager.'.$dataType->slug.'.index')); ?>" class="btn btn-warning">
                            <i class="glyphicon glyphicon-list"></i> <span class="hidden-xs hidden-sm"><?php echo e(__('voyager::generic.return_to_list')); ?></span>
                        </a>
                    <?php endif; ?>  
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <!-- form start -->
                    <?php $__currentLoopData = $dataType->readRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        if ($dataTypeContent->{$row->field.'_read'}) {
                            $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_read'};
                        }
                        ?>
                        <div class="panel-heading" style="border-bottom:0;">
                            <h3 class="panel-title"><?php echo e($row->getTranslatedAttribute('display_name')); ?></h3>
                        </div>

                        <div class="panel-body" style="padding-top:0;">
                            <?php if(isset($row->details->view_read)): ?>
                                <?php echo $__env->make($row->details->view_read, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'read', 'options' => $row->details], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif(isset($row->details->view)): ?>
                                <?php echo $__env->make($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => 'read', 'view' => 'read', 'options' => $row->details], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif($row->type == "image"): ?>
                                <img class="img-responsive"
                                     src="<?php echo e(filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Voyager::image($dataTypeContent->{$row->field})); ?>">
                            <?php elseif($row->type == 'multiple_images'): ?>
                                <?php if(json_decode($dataTypeContent->{$row->field})): ?>
                                    <?php $__currentLoopData = json_decode($dataTypeContent->{$row->field}); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img class="img-responsive"
                                             src="<?php echo e(filter_var($file, FILTER_VALIDATE_URL) ? $file : Voyager::image($file)); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <img class="img-responsive"
                                         src="<?php echo e(filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Voyager::image($dataTypeContent->{$row->field})); ?>">
                                <?php endif; ?>
                            <?php elseif($row->type == 'relationship'): ?>
                                 <?php echo $__env->make('voyager::formfields.relationship', ['view' => 'read', 'options' => $row->details], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif($row->type == 'select_dropdown' && property_exists($row->details, 'options') &&
                                    !empty($row->details->options->{$dataTypeContent->{$row->field}})
                            ): ?>
                                <?php echo $row->details->options->{$dataTypeContent->{$row->field}};?>
                            <?php elseif($row->type == 'select_multiple'): ?>
                                <?php if(property_exists($row->details, 'relationship')): ?>

                                    <?php $__currentLoopData = json_decode($dataTypeContent->{$row->field}); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($item->{$row->field}); ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php elseif(property_exists($row->details, 'options')): ?>
                                    <?php if(!empty(json_decode($dataTypeContent->{$row->field}))): ?>
                                        <?php $__currentLoopData = json_decode($dataTypeContent->{$row->field}); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(@$row->details->options->{$item}): ?>
                                                <?php echo e($row->details->options->{$item} . (!$loop->last ? ', ' : '')); ?>

                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <?php echo e(__('voyager::generic.none')); ?>

                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php elseif($row->type == 'date' || $row->type == 'timestamp'): ?>
                                <?php if( property_exists($row->details, 'format') && !is_null($dataTypeContent->{$row->field}) ): ?>
                                    <?php echo e(\Carbon\Carbon::parse($dataTypeContent->{$row->field})->formatLocalized($row->details->format)); ?>

                                <?php else: ?>
                                    <?php echo e($dataTypeContent->{$row->field}); ?>

                                <?php endif; ?>
                            <?php elseif($row->type == 'checkbox'): ?>
                                <?php if(property_exists($row->details, 'on') && property_exists($row->details, 'off')): ?>
                                    <?php if($dataTypeContent->{$row->field}): ?>
                                    <span class="label label-info"><?php echo e($row->details->on); ?></span>
                                    <?php else: ?>
                                    <span class="label label-primary"><?php echo e($row->details->off); ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                <?php echo e($dataTypeContent->{$row->field}); ?>

                                <?php endif; ?>
                            <?php elseif($row->type == 'color'): ?>
                                <span class="badge badge-lg" style="background-color: <?php echo e($dataTypeContent->{$row->field}); ?>"><?php echo e($dataTypeContent->{$row->field}); ?></span>
                            <?php elseif($row->type == 'coordinates'): ?>
                                <?php echo $__env->make('voyager::partials.coordinates', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif($row->type == 'rich_text_box'): ?>
                                <?php echo $__env->make('voyager::multilingual.input-hidden-bread-read', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php echo $dataTypeContent->{$row->field}; ?>

                            <?php elseif($row->type == 'file'): ?>
                                <?php if(json_decode($dataTypeContent->{$row->field})): ?>
                                    <?php $__currentLoopData = json_decode($dataTypeContent->{$row->field}); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: ''); ?>">
                                            <?php echo e($file->original_name ?: ''); ?>

                                        </a>
                                        <br/>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php elseif($dataTypeContent->{$row->field}): ?>
                                    <a href="<?php echo e(Storage::disk(config('voyager.storage.disk'))->url($row->field) ?: ''); ?>">
                                        <?php echo e(__('voyager::generic.download')); ?>

                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo $__env->make('voyager::multilingual.input-hidden-bread-read', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <p><?php echo e($dataTypeContent->{$row->field}); ?></p>
                            <?php endif; ?>
                        </div><!-- panel-body -->
                        <?php if(!$loop->last): ?>
                            <hr style="margin:0;">
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger view quote_cancel_btn" data-dismiss="modal">Close</button>
        </div>
    </div>
</div><?php /**PATH /home/adddepot/public_html/resources/views/vendor/voyager/bot-profiles/read.blade.php ENDPATH**/ ?>