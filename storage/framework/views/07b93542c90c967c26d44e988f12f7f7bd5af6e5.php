<?php if(($listings) && count($listings) > 0): ?>  
    <?php $__currentLoopData = $listings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card mb-3 card-body">
        <div class="row">
            <div class="col-sm-2">   
                <?php if(!empty($list->club->image)): ?>
                    <img src="<?php echo e(Voyager::image($list->getThumbnail($list->club->image, 'cropped'))); ?>" alt="<?php echo e($list->club->title); ?>" title="<?php echo e($list->club->title); ?>" class="width-90 rounded-3" />
                <?php else: ?> 
                  <img src="https://www.bootdey.com/image/100x80/FFB6C1/000000" alt="Contemporary Club" />
                <?php endif; ?>  
            </div>
            <div class="col-sm-6">
                <div class="overflow-hidden flex-nowrap">
                    <h6 class="mb-1"><?php echo e($list->title); ?></h6> 
                    <p><i class="voyager-location"></i> <?php echo e($list->club->title); ?></p>
                    <span class="text-muted d-block mb-2 small">
                        <i class="glyphicon glyphicon-calendar"></i>  <?php echo e(\Carbon\Carbon::parse($list->date)->format('d M Y')); ?>   
                        &nbsp; <i class="glyphicon glyphicon-time"></i> &nbsp;<?php echo e($list->time); ?>

                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2 text-text">
                <span class="text-criteria"> <i class="glyphicon glyphicon-asterisk yellow"></i> <?php echo e($list->criteria); ?> </span>
            </div>
            <div class="col-sm-3">
                <h5 class="pt-3 text-onsale text-600 text-primary-d1 letter-spacing"> 
                    <?php echo e(\Carbon\Carbon::parse($list->onsale_date)->format('d M Y')); ?> <?php echo e($list->onsale_time); ?>

                </h5> 
            </div> 
            <div class=" col-sm-3"> 
                <h4 class="pt-3 text-countdown text-600 text-primary-d1"> 
                    <span data-countdown="<?php echo e(\Carbon\Carbon::parse($list->onsale_date)->format('Y/m/d')); ?> <?php echo e($list->onsale_time); ?>"> </span>
                </h4> 
            </div>
            <div class="col-sm-4">
                <?php if(!empty($list->URL)): ?>
                    <a href="<?php echo e($list->URL); ?>" class="f-n-hover btn btn-info btn-raised px-4 py-25 w-75 text-600" target="_blank">Go to Web</a>
                 <?php endif; ?>
            </div>
        </div>
    </div>  
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-12">
        <div class="text-center">
            <button id="load_more_button" onclick="filter(event, '<?php echo e($filterValue); ?>', '<?php echo e($next); ?>')" class="btn btn-primary">Load More</button>
        </div>
    </div>
<?php else: ?> 
    <div class="card mb-3 card-body">
        <div class="timetable-item norecord-found">
            <span>No Record Found</span>
        </div>
    </div>
<?php endif; ?><?php /**PATH /home/adddepot/public_html/resources/views//vendor/voyager/notification/card-filter.blade.php ENDPATH**/ ?>