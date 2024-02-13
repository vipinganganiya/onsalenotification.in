<?php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
?>


<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">
                <i class="voyager-plus"></i> 
                <!-- <?php echo e($dataType->icon); ?> -->
                <?php echo e(__('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular')); ?>

            </h4>
            <!-- <div class="container-fluid" style="background: #09b109 !important;">
                <div class="pull-left">

                </div>
            </div> -->
        </div>

        
        <form role="form"
                class="form-edit-add"
                action="<?php echo e($edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store')); ?>"
                method="POST" enctype="multipart/form-data">
            <!-- PUT Method if we are editing -->
            <?php if($edit): ?>
                <?php echo e(method_field("PUT")); ?>

            <?php endif; ?>

            <!-- CSRF TOKEN -->
            <?php echo e(csrf_field()); ?>


            <div class="form-group">

                <?php if(count($errors) > 0): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Adding / Editing -->
                <?php
                    $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                ?>

                <?php $__currentLoopData = $dataTypeRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!-- GET THE DISPLAY OPTIONS -->
                    <?php
                        $display_options = $row->details->display ?? NULL;
                        if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                            $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                        }
                    ?>
                    <?php if(isset($row->details->legend) && isset($row->details->legend->text)): ?>
                        <legend class="text-<?php echo e($row->details->legend->align ?? 'center'); ?>" style="background-color: <?php echo e($row->details->legend->bgcolor ?? '#f0f0f0'); ?>;padding: 5px;"><?php echo e($row->details->legend->text); ?></legend>
                    <?php endif; ?>

                    <div class=" <?php if($row->type == 'hidden'): ?> hidden <?php endif; ?> col-md-<?php echo e($display_options->width ?? 12); ?> <?php echo e($errors->has($row->field) ? 'has-error' : ''); ?>" <?php if(isset($display_options->id)): ?><?php echo e("id=$display_options->id"); ?><?php endif; ?>>
                        <div class="form-group">
                            <?php echo e($row->slugify); ?>

                            <label style="color: #112638;font-weight: 400;" class="control-label" for="name"><?php echo e($row->getTranslatedAttribute('display_name')); ?></label>
                            <?php echo $__env->make('voyager::multilingual.input-hidden-bread-edit-add', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php if($add && isset($row->details->view_add)): ?>
                                <?php echo $__env->make($row->details->view_add, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'add', 'options' => $row->details], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif($edit && isset($row->details->view_edit)): ?>
                                <?php echo $__env->make($row->details->view_edit, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'edit', 'options' => $row->details], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif(isset($row->details->view)): ?>
                                <?php echo $__env->make($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif($row->type == 'relationship'): ?>
                                <?php echo $__env->make('voyager::formfields.relationship', ['options' => $row->details], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php else: ?>
                                <?php echo app('voyager')->formField($row, $dataType, $dataTypeContent); ?>

                            <?php endif; ?>

                            <?php $__currentLoopData = app('voyager')->afterFormFields($row, $dataType, $dataTypeContent); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $after): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo $after->handle($row, $dataType, $dataTypeContent); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($errors->has($row->field)): ?>
                                <?php $__currentLoopData = $errors->get($row->field); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="help-block"><?php echo e($error); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div><!-- panel-body -->

            <div class="panel-footer" style="text-align:right">
                <?php $__env->startSection('submit-buttons'); ?>
                    <button type="submit" class="btn btn-primary save"><?php echo e(__('voyager::generic.save')); ?></button>
                <?php $__env->stopSection(); ?>
                <?php echo $__env->yieldContent('submit-buttons'); ?>
                <button type="button" class="btn btn-sm btn-danger view quote_cancel_btn" data-dismiss="modal">Close</button>
            </div>
        </form>

        <div style="display:none">
            <input type="hidden" id="upload_url" value="<?php echo e(route('voyager.upload')); ?>">
            <input type="hidden" id="upload_type_slug" value="<?php echo e($dataType->slug); ?>">
        </div> 
        <div class="modal-footer">
            
        </div>
    </div>
</div>
<script type="text/javascript" src="https://adddepot.co/admin/voyager-assets?path=js%2Fapp.js"></script><?php /**PATH /home/adddepot/public_html/resources/views/vendor/voyager/bot-profiles/edit-add.blade.php ENDPATH**/ ?>