<?php $__env->startSection('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural')); ?>

<?php $__env->startSection('page_header'); ?>
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="<?php echo e($dataType->icon); ?>"></i> <?php echo e($dataType->getTranslatedAttribute('display_name_plural')); ?>

        </h1>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', app($dataType->model_name))): ?>
            <?php echo $__env->make('voyager::partials.bulk-delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', app($dataType->model_name))): ?>
            <?php if(!empty($dataType->order_column) && !empty($dataType->order_display_column)): ?>
                <a href="<?php echo e(route('voyager.'.$dataType->slug.'.order')); ?>" class="btn btn-primary btn-add-new">
                    <i class="voyager-list"></i> <span><?php echo e(__('voyager::bread.order')); ?></span>
                </a>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', app($dataType->model_name))): ?>
            <?php if($usesSoftDeletes): ?>
                <input type="checkbox" <?php if($showSoftDeleted): ?> checked <?php endif; ?> id="show_soft_deletes" data-toggle="toggle" data-on="<?php echo e(__('voyager::bread.soft_deletes_off')); ?>" data-off="<?php echo e(__('voyager::bread.soft_deletes_on')); ?>">
            <?php endif; ?>
        <?php endif; ?>
        <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(method_exists($action, 'massAction')): ?>
                <?php echo $__env->make('voyager::bread.partials.actions', ['action' => $action, 'data' => null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('voyager::multilingual.language-selector', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-content browse container-fluid">  
        <?php echo $__env->make('voyager::alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="overflow: visible;">
                        <div class="container-fluid" style="background: white !important;">
                            <div class="pull-right d-flex">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add', app($dataType->model_name))): ?>
                                    <!-- <?php echo e(route('voyager.'.$dataType->slug.'.create')); ?> -->
                                    <?php
                                        $route = route('voyager.bot-profiles.create');
                                    ?> 
                                    <button class="btn-add-profile" data-toggle="modal" data-dismiss="modal" onclick="addModel('<?php echo e($route); ?>');">
                                        <i class="voyager-plus"></i> <?php echo e(__('voyager::generic.add_new')); ?>

                                    </button>
                                    <button class="btn-config-profile" data-toggle="modal" data-dismiss="modal" data-target="#config_modal">
                                        <i class="voyager-settings"></i> Config
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <br />
                        <?php if($isServerSide): ?>
                            <form method="get" class="form-search">
                                <div id="search-input">
                                    <div class="col-2">
                                        <select id="search_key" name="key">
                                            <?php $__currentLoopData = $searchNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>" <?php if($search->key == $key || (empty($search->key) && $key == $defaultSearchKey)): ?> selected <?php endif; ?>><?php echo e($name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <select id="filter" name="filter">
                                            <option value="contains" <?php if($search->filter == "contains"): ?> selected <?php endif; ?>><?php echo e(__('voyager::generic.contains')); ?></option>
                                            <option value="equals" <?php if($search->filter == "equals"): ?> selected <?php endif; ?>>=</option>
                                        </select>
                                    </div>
                                    <div class="input-group col-md-12">
                                        <input type="text" class="form-control" placeholder="<?php echo e(__('voyager::generic.search')); ?>" name="s" value="<?php echo e($search->value); ?>">
                                        <span class="input-group-btn">
                                            <button class="btn btn-info btn-lg" type="submit">
                                                <i class="voyager-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <?php if(Request::has('sort_order') && Request::has('order_by')): ?>
                                    <input type="hidden" name="sort_order" value="<?php echo e(Request::get('sort_order')); ?>">
                                    <input type="hidden" name="order_by" value="<?php echo e(Request::get('order_by')); ?>">
                                <?php endif; ?>
                            </form>
                        <?php endif; ?>
                        <div class="row">
                            <div class="col-md-12 listing-table" style="margin-bottom: 0; display: none;">
                                <div class="panel panel-default" style="position: sticky; top: 0;">
                                    <div class="header-panel-heading collapsed">
                                        <div class="panel-title">
                                            <div class="accordion-toggle flexbox">
                                                <?php if($showCheckboxColumn): ?>
                                                    <div class="flexbox-item flexbox-item-assign">
                                                        <input type="checkbox" class="select_all">
                                                    </div>
                                                <?php endif; ?>
                                                <?php $__currentLoopData = $dataType->browseRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="flexbox-item flexbox-item-<?php echo e(str_replace(' ', '-', strtolower($row->getTranslatedAttribute('display_name')))); ?>">
                                                    <?php if($isServerSide && in_array($row->field, $sortableColumns)): ?>
                                                        <a href="<?php echo e($row->sortByUrl($orderBy, $sortOrder)); ?>">
                                                    <?php endif; ?>
                                                    <?php echo e($row->getTranslatedAttribute('display_name')); ?>

                                                    <?php if($isServerSide): ?>
                                                        <?php if($row->isCurrentSortField($orderBy)): ?>
                                                            <?php if($sortOrder == 'asc'): ?>
                                                                <i class="voyager-angle-up pull-right"></i>
                                                            <?php else: ?>
                                                                <i class="voyager-angle-down pull-right"></i>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <div class="flexbox-item flexbox-item-action"><?php echo e(__('voyager::generic.actions')); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <?php $__empty_1 = true; $__currentLoopData = $dataTypeContent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="panel panel-default data-panel" panel_div_id="<?php echo e($data->id); ?>">
                                        <div class="panel-heading collapsed" data-iteration="<?php echo e($loop->iteration); ?>" href="#collapse<?php echo e($loop->iteration); ?>">
                                            <div class="panel-title">
                                                <div class="accordion-toggle flexbox">
                                                    <?php if($showCheckboxColumn): ?>
                                                        <div class="flexbox-item flexbox-item-assign text-center" id="map-tgces-<?php echo e($data->id); ?>">
                                                            <input type="checkbox" name="row_id" id="checkbox_<?php echo e($data->getKey()); ?>" value="<?php echo e($data->getKey()); ?>">
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php $__currentLoopData = $dataType->browseRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                        if ($data->{$row->field.'_browse'}) {
                                                            $data->{$row->field} = $data->{$row->field.'_browse'};
                                                        }
                                                        ?>
                                                         <div class="flexbox-item flexbox-item-<?php echo e(str_replace(' ', '-', strtolower($row->getTranslatedAttribute('display_name')))); ?> <?php echo e(str_replace(' ', '-', strtolower($row->getTranslatedAttribute('display_name')))); ?>-<?php echo e($data->id); ?>" id="map-tgces-<?php echo e($data->id); ?>">
                                                            <?php if(isset($row->details->view_browse)): ?>
                                                                <?php echo $__env->make($row->details->view_browse, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $data->{$row->field}, 'view' => 'browse', 'options' => $row->details], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php elseif(isset($row->details->view)): ?>
                                                                <?php echo $__env->make($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $data->{$row->field}, 'action' => 'browse', 'view' => 'browse', 'options' => $row->details], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php elseif($row->type == 'image'): ?>
                                                                <img src="<?php if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)): ?><?php echo e(Voyager::image( $data->{$row->field} )); ?><?php else: ?><?php echo e($data->{$row->field}); ?><?php endif; ?>" style="width:100px">
                                                            <?php elseif($row->type == 'relationship'): ?>
                                                                <?php echo $__env->make('voyager::formfields.relationship', ['view' => 'read','options' => $row->details], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php elseif($row->type == 'select_multiple'): ?>
                                                                <?php if(property_exists($row->details, 'relationship')): ?>

                                                                    <?php $__currentLoopData = $data->{$row->field}; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php echo e($item->{$row->field}); ?>

                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                <?php elseif(property_exists($row->details, 'options')): ?>
                                                                    <?php if(!empty(json_decode($data->{$row->field}))): ?>
                                                                        <?php $__currentLoopData = json_decode($data->{$row->field}); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php if(@$row->details->options->{$item}): ?>
                                                                                <?php echo e($row->details->options->{$item} . (!$loop->last ? ', ' : '')); ?>

                                                                            <?php endif; ?>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php else: ?>
                                                                        <?php echo e(__('voyager::generic.none')); ?>

                                                                    <?php endif; ?>
                                                                <?php endif; ?>

                                                                <?php elseif($row->type == 'multiple_checkbox' && property_exists($row->details, 'options')): ?>
                                                                    <?php if(@count(json_decode($data->{$row->field}, true)) > 0): ?>
                                                                        <?php $__currentLoopData = json_decode($data->{$row->field}); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php if(@$row->details->options->{$item}): ?>
                                                                                <?php echo e($row->details->options->{$item} . (!$loop->last ? ', ' : '')); ?>

                                                                            <?php endif; ?>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php else: ?>
                                                                        <?php echo e(__('voyager::generic.none')); ?>

                                                                    <?php endif; ?>

                                                            <?php elseif(($row->type == 'select_dropdown' || $row->type == 'radio_btn') && property_exists($row->details, 'options')): ?>

                                                                <?php echo $row->details->options->{$data->{$row->field}} ?? ''; ?>


                                                            <?php elseif($row->type == 'date' || $row->type == 'timestamp'): ?>
                                                                <?php if( property_exists($row->details, 'format') && !is_null($data->{$row->field}) ): ?>
                                                                    <?php echo e(\Carbon\Carbon::parse($data->{$row->field})->formatLocalized($row->details->format)); ?>

                                                                <?php else: ?>
                                                                    <?php echo e($data->{$row->field}); ?>

                                                                <?php endif; ?>
                                                            <?php elseif($row->type == 'checkbox'): ?>
                                                                <?php if(property_exists($row->details, 'on') && property_exists($row->details, 'off')): ?>
                                                                    <?php if($data->{$row->field}): ?>
                                                                        <span class="label label-info"><?php echo e($row->details->on); ?></span>
                                                                    <?php else: ?>
                                                                        <span class="label label-primary"><?php echo e($row->details->off); ?></span>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                <?php echo e($data->{$row->field}); ?>

                                                                <?php endif; ?>
                                                            <?php elseif($row->type == 'color'): ?>
                                                                <span class="badge badge-lg" style="background-color: <?php echo e($data->{$row->field}); ?>"><?php echo e($data->{$row->field}); ?></span>
                                                            <?php elseif($row->type == 'text'): ?>
                                                                <?php echo $__env->make('voyager::multilingual.input-hidden-bread-browse', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                               <?php echo e(mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field}); ?>

                                                            <?php elseif($row->type == 'text_area'): ?>
                                                                <?php echo $__env->make('voyager::multilingual.input-hidden-bread-browse', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                                <div><?php echo e(mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field}); ?></div>
                                                            <?php elseif($row->type == 'file' && !empty($data->{$row->field}) ): ?>
                                                                <?php echo $__env->make('voyager::multilingual.input-hidden-bread-browse', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                                <?php if(json_decode($data->{$row->field}) !== null): ?>
                                                                    <?php $__currentLoopData = json_decode($data->{$row->field}); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <a href="<?php echo e(Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: ''); ?>" target="_blank">
                                                                            <?php echo e($file->original_name ?: ''); ?>

                                                                        </a>
                                                                        <br/>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php else: ?>
                                                                    <a href="<?php echo e(Storage::disk(config('voyager.storage.disk'))->url($data->{$row->field})); ?>" target="_blank">
                                                                        <?php echo e(__('voyager::generic.download')); ?>

                                                                    </a>
                                                                <?php endif; ?>
                                                            <?php elseif($row->type == 'rich_text_box'): ?>
                                                                <?php echo $__env->make('voyager::multilingual.input-hidden-bread-browse', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                                <div><?php echo e(mb_strlen( strip_tags($data->{$row->field}, '<b><i><u>') ) > 200 ? mb_substr(strip_tags($data->{$row->field}, '<b><i><u>'), 0, 200) . ' ...' : strip_tags($data->{$row->field}, '<b><i><u>')); ?></div>
                                                            <?php elseif($row->type == 'coordinates'): ?>
                                                                <?php echo $__env->make('voyager::partials.coordinates-static-image', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php elseif($row->type == 'multiple_images'): ?>
                                                                <?php $images = json_decode($data->{$row->field}); ?>
                                                                <?php if($images): ?>
                                                                    <?php $images = array_slice($images, 0, 3); ?>
                                                                    <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <img src="<?php if( !filter_var($image, FILTER_VALIDATE_URL)): ?><?php echo e(Voyager::image( $image )); ?><?php else: ?><?php echo e($image); ?><?php endif; ?>" style="width:50px">
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php endif; ?>
                                                            <?php elseif($row->type == 'media_picker'): ?>
                                                                <?php
                                                                    if (is_array($data->{$row->field})) {
                                                                        $files = $data->{$row->field};
                                                                    } else {
                                                                        $files = json_decode($data->{$row->field});
                                                                    }
                                                                ?>
                                                                <?php if($files): ?>
                                                                    <?php if(property_exists($row->details, 'show_as_images') && $row->details->show_as_images): ?>
                                                                        <?php $__currentLoopData = array_slice($files, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <img src="<?php if( !filter_var($file, FILTER_VALIDATE_URL)): ?><?php echo e(Voyager::image( $file )); ?><?php else: ?><?php echo e($file); ?><?php endif; ?>" style="width:50px">
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php else: ?>
                                                                        <ul>
                                                                        <?php $__currentLoopData = array_slice($files, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <li><?php echo e($file); ?></li>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </ul>
                                                                    <?php endif; ?>
                                                                    <?php if(count($files) > 3): ?>
                                                                        <?php echo e(__('voyager::media.files_more', ['count' => (count($files) - 3)])); ?>

                                                                    <?php endif; ?>
                                                                <?php elseif(is_array($files) && count($files) == 0): ?>
                                                                    <?php echo e(trans_choice('voyager::media.files', 0)); ?>

                                                                <?php elseif($data->{$row->field} != ''): ?>
                                                                    <?php if(property_exists($row->details, 'show_as_images') && $row->details->show_as_images): ?>
                                                                        <img src="<?php if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)): ?><?php echo e(Voyager::image( $data->{$row->field} )); ?><?php else: ?><?php echo e($data->{$row->field}); ?><?php endif; ?>" style="width:50px">
                                                                    <?php else: ?>
                                                                        <?php echo e($data->{$row->field}); ?>

                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php echo e(trans_choice('voyager::media.files', 0)); ?>

                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php echo $__env->make('voyager::multilingual.input-hidden-bread-browse', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                                <span><?php echo e($data->{$row->field}); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="flexbox-item flexbox-item-thread-input" id="map-tgces-<?php echo e($data->id); ?>" style="text-align: center;">
                                                        <input type="text" name="thread_input" id="thread_input_<?php echo e($data->id); ?>" style="color: #000; line-height: normal !important; width: 25px;" />
                                                    </div>
                                                    <div class="flexbox-item flexbox-item-action arrow-1" id="map-tgces-<?php echo e($data->id); ?>">
                                                        <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if(!method_exists($action, 'massAction')): ?>
                                                                <?php echo $__env->make('voyager::bot-profiles.partials.actions', ['action' => $action], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="panel panel-default panel-empty">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                No Data Available
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?> 
                            </div>
                        </div>
                        <?php if($isServerSide): ?>
                            <div class="pull-left">
                                <div role="status" class="show-res" aria-live="polite"><?php echo e(trans_choice(
                                    'voyager::generic.showing_entries', $dataTypeContent->total(), [
                                        'from' => $dataTypeContent->firstItem(),
                                        'to' => $dataTypeContent->lastItem(),
                                        'all' => $dataTypeContent->total()
                                    ])); ?></div>
                            </div>
                            <div class="pull-right">
                                <?php echo e($dataTypeContent->appends([
                                    's' => $search->value,
                                    'filter' => $search->filter,
                                    'key' => $search->key,
                                    'order_by' => $orderBy,
                                    'sort_order' => $sortOrder,
                                    'showSoftDeleted' => $showSoftDeleted,
                                ])->links()); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade modal-info in" id="config_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><i class="voyager-rocket"></i> <?php echo e($dataType->getTranslatedAttribute('display_name_plural')); ?> - Configuration 
                        </h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                       <div class="card card-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-2 col-add">
                                        <span class="master-table machine"> 
                                            <a onclick="showOthers(' <?php echo e(route("voyager.bot-machines.index")); ?>')" data-dismiss="modal" aria-hidden="true">
                                                <img width="50" height="50" src="https://img.icons8.com/ios/50/gears--v1.png" alt="Machine" title="Machine" />
                                            </a>
                                            <br />
                                            <b>Machine</b> 
                                        </span>
                                    </div>

                                    <div class="col-sm-2 col-add">
                                        <span class="master-table proxy">
                                            <a onclick="showOthers(' <?php echo e(route("voyager.bot-proxies.index")); ?>')" data-dismiss="modal" aria-hidden="true">
                                                <img width="50" height="50" src="https://img.icons8.com/external-solidglyph-m-oki-orlando/64/external-proxy-information-technology-solid-solidglyph-m-oki-orlando.png" alt="Proxy" title="Proxy" />
                                            </a>
                                            <br />
                                            <b>Proxy</b>
                                        </span>
                                    </div>

                                    <div class="col-sm-2 col-add">
                                        <span class="master-table login">
                                            <a onclick="showOthers(' <?php echo e(route("voyager.bot-logins.index")); ?>')" data-dismiss="modal" aria-hidden="true">
                                                <img width="50" height="50" src="https://img.icons8.com/ios/50/enter-2.png" alt="Login" title="Login" />
                                            </a>
                                            <br />
                                            <b>Login</b>
                                        </span>
                                    </div>

                                    <div class="col-sm-2 col-add">
                                        <span class="master-table club">
                                            <a onclick="showOthers(' <?php echo e(route("voyager.bot-clubs.index")); ?>')" data-dismiss="modal" aria-hidden="true">
                                                <img width="48" height="48" src="https://img.icons8.com/external-those-icons-lineal-those-icons/48/external-Card-casino-and-leisure-those-icons-lineal-those-icons-2.png" alt="Club" title="Club" />
                                            </a>
                                            <br />
                                            <a>Club</a>
                                        </span>   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger view quote_cancel_btn" data-dismiss="modal">Close</button>
                </div> -->
            </div>
        </div>
    </div>

    
    <div class="modal fade modal-info event_detail_modal in" id="loadData" style="padding-right: 17px;" role="bot-mgt">

    </div>

    
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo e(__('voyager::generic.close')); ?>"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> <?php echo e(__('voyager::generic.delete_question')); ?> <?php echo e(strtolower($dataType->getTranslatedAttribute('display_name_singular'))); ?>?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        <?php echo e(method_field('DELETE')); ?>

                        <?php echo e(csrf_field()); ?>

                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="<?php echo e(__('voyager::generic.delete_confirm')); ?>">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?php echo e(__('voyager::generic.cancel')); ?></button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<?php if(!$dataType->server_side && config('dashboard.data_tables.responsive')): ?>
    <link rel="stylesheet" href="<?php echo e(voyager_asset('lib/css/responsive.dataTables.min.css')); ?>">
<?php endif; ?>
<link href="<?php echo e(asset('css/multiselect.css')); ?>" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?php echo e(asset('css/scraper.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
    <!-- DataTables -->
    <?php if(!$dataType->server_side && config('dashboard.data_tables.responsive')): ?>
        <script src="<?php echo e(voyager_asset('lib/js/dataTables.responsive.min.js')); ?>"></script>
    <?php endif; ?>
    <script src="<?php echo e(asset('js/moment.js')); ?>"></script>
    <script>
        $(document).ready(function () {

            $('.listing-table').slideDown();

            $('#config_btn').on('click', function (e) {
                $('#configInfo').modal('show');
            });

            <?php if(!$dataType->server_side): ?>
                var table = $('#dataTable').DataTable(<?php echo json_encode(
                    array_merge([
                        "order" => $orderColumn,
                        "language" => __('voyager::datatable'),
                        "columnDefs" => [
                            ['targets' => 'dt-not-orderable', 'searchable' =>  false, 'orderable' => false],
                        ],
                    ],
                    config('voyager.dashboard.data_tables', []))
                , true); ?>);
            <?php else: ?>
                $('#search-input select').select2({
                    minimumResultsForSearch: Infinity
                });
            <?php endif; ?>

            <?php if($isModelTranslatable): ?>
                $('.side-body').multilingual();
                //Reinitialise the multilingual features when they change tab
                $('#dataTable').on('draw.dt', function(){
                    $('.side-body').data('multilingual').init();
                })
            <?php endif; ?>
            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked')).trigger('change');
            });
        });


        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '<?php echo e(route('voyager.'.$dataType->slug.'.destroy', '__id')); ?>'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });

        <?php if($usesSoftDeletes): ?>
            <?php
                $params = [
                    's' => $search->value,
                    'filter' => $search->filter,
                    'key' => $search->key,
                    'order_by' => $orderBy,
                    'sort_order' => $sortOrder,
                ];
            ?>
            $(function() {
                $('#show_soft_deletes').change(function() {
                    if ($(this).prop('checked')) {
                        $('#dataTable').before('<a id="redir" href="<?php echo e((route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 1]), true))); ?>"></a>');
                    }else{
                        $('#dataTable').before('<a id="redir" href="<?php echo e((route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 0]), true))); ?>"></a>');
                    }

                    $('#redir')[0].click();
                })
            })
        <?php endif; ?>
        $('input[name="row_id"]').on('change', function () {
            var ids = [];
            $('input[name="row_id"]').each(function() {
                if ($(this).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            $('.selected_ids').val(ids);
        });

        function addModel(slug) {   
            $.ajax({
                url:slug,
                type: "get",
                success: function(res) {
                   $("#loadData").html(res);
                   $('#loadData').modal('show');
                },
                error: function() {
                    toastr.error('Something went wrong', 'Error');
                },
                complete: function() {
                    
                }
            });
        }
        function editProfile(url, id) { 
            var thread_input = $("#thread_input_"+id).val();
            console.log(thread_input);
            $.ajax({
                url: url,
                type: "get",
                data: {'thread_input':thread_input},
                success: function(res) {

                    if (res.id) {
                        toastr.success(res.msg, 'Success');

                        $("#thread_input_"+res.id).val('');
                        if(res.type=='start') { 
                            console.log("#running-threads-"+res.id);
                            $(".running-threads-"+res.id).text(res.thread_input);
                        }

                    } else { 
                       $("#loadData").html(res);
                       $('#loadData').modal('show'); 
                    }
                  
                },
                error: function() {
                    toastr.error('Something went wrong', 'Error');
                },
                complete: function() {
                    
                }
            });
        }
        function showOthers(url) { 
            console.log(url);  
            $.ajax({
                url: url,
                type: "get", 
                success: function(res) {
                   $("#loadData").html(res);
                   $('#config_modal').modal('hide');  
                   $('#loadData').modal('show');  
                  
                },
                error: function() {
                    toastr.error('Something went wrong', 'Error');
                },
                complete: function() {
                    
                }
            });
        }
        <?php if(!empty(Session::get('popup')) && Session::get('popup')  == "bot-machines-listing"): ?>
            showOthers(<?php echo json_encode(route('voyager.bot-machines.index') , 15, 512) ?>);
        <?php endif; ?>

        <?php if(!empty(Session::get('popup')) && Session::get('popup')  == "bot-clubs-listing"): ?>
            showOthers(<?php echo json_encode(route('voyager.bot-clubs.index') , 15, 512) ?>);
            //toastr.success('Bot Machine is added successfully', 'Success');
        <?php endif; ?>

        <?php if(!empty(Session::get('popup')) && Session::get('popup')  == "bot-logins-listing"): ?>
            showOthers(<?php echo json_encode(route('voyager.bot-logins.index') , 15, 512) ?>); 
        <?php endif; ?>
        // <?php if(count($errors) > 0): ?>
        //     showOthers(<?php echo json_encode(route('clubs-bot.create') , 15, 512) ?>); 
            
        // <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('voyager::master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/adddepot/public_html/resources/views/vendor/voyager/bot-profiles/browse.blade.php ENDPATH**/ ?>