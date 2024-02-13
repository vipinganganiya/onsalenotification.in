<?php $selected_value = old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? NULL) ?>
<ul class="radio">
    <?php if(isset($options->options)): ?>
        <?php $__currentLoopData = $options->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <input type="radio" id="option-<?php echo e(\Illuminate\Support\Str::slug($row->field, '-')); ?>-<?php echo e(\Illuminate\Support\Str::slug($key, '-')); ?>"
                       name="<?php echo e($row->field); ?>"
                       value="<?php echo e($key); ?>" <?php if($selected_value == $key): ?> checked <?php endif; ?>>
                <label for="option-<?php echo e(\Illuminate\Support\Str::slug($row->field, '-')); ?>-<?php echo e(\Illuminate\Support\Str::slug($key, '-')); ?>"><?php echo e($option); ?></label>
                <div class="check"></div>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</ul>
<?php /**PATH /home/adddepot/public_html/vendor/tcg/voyager/src/../resources/views/formfields/radio_btn.blade.php ENDPATH**/ ?>