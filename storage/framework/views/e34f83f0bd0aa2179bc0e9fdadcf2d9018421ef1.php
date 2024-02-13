<?php if($data): ?>
    <?php
        // need to recreate object because policy might depend on record data
        $class = get_class($action);
        $action = new $class($dataType, $data);
    ?> 
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($action->getPolicy(), $data)): ?>
        <?php if($action->shouldActionDisplayOnRow($data)): ?>
            <?php if($action->getTitle() == "View"): ?>
                <a href="#" title="<?php echo e($action->getTitle()); ?>" <?php echo $action->convertAttributesToHtml(); ?> onclick="editProfile('bot-profiles/read/<?php echo e($data->id); ?>',  );">
                    <i class="<?php echo e($action->getIcon()); ?>"></i> 
                    </span>
                </a>
                    <!-- <span class="hidden-xs hidden-sm"><?php echo e($action->getTitle()); ?> -->
                <!-- <span class="icon-stats">
                    <span title="<?php echo e($action->getTitle()); ?>"  onclick="loadThread('bot-profiles/read/<?php echo e($data->id); ?>');">
                        <i class="<?php echo e($action->getIcon()); ?>"></i> 
                    </span> 
                </span> -->
            <?php else: ?>
                <a href="#" title="<?php echo e($action->getTitle()); ?>" <?php echo $action->convertAttributesToHtml(); ?> onclick="editProfile('<?php echo e($action->getRoute($dataType->name)); ?>', '<?php echo e($data->id); ?>')">
                    <i class="<?php echo e($action->getIcon()); ?>"></i> 
                    </span>
                </a>&nbsp;&nbsp;&nbsp;&nbsp;
                <!-- <span class="icon-stats">
                    <span  title="<?php echo e($action->getTitle()); ?>" onclick="loadThread('<?php echo e($action->getRoute($dataType->name)); ?>')">
                        <i class="<?php echo e($action->getIcon()); ?>"></i> 
                    </span>  
                </span> --> 
            <?php endif; ?>  
            </span>
        <?php endif; ?>
    <?php endif; ?> 
<?php elseif(method_exists($action, 'massAction')): ?>
    <form method="post" action="<?php echo e(route('voyager.'.$dataType->slug.'.action')); ?>" style="display:inline">
        <?php echo e(csrf_field()); ?>

        <button type="submit" <?php echo $action->convertAttributesToHtml(); ?>><i class="<?php echo e($action->getIcon()); ?>"></i>  <?php echo e($action->getTitle()); ?></button>
        <input type="hidden" name="action" value="<?php echo e(get_class($action)); ?>">
        <input type="hidden" name="ids" value="" class="selected_ids">
    </form>
<?php endif; ?><?php /**PATH /home/adddepot/public_html/resources/views/vendor/voyager/bot-profiles/partials/actions.blade.php ENDPATH**/ ?>