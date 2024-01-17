
<?php $__env->startSection('css'); ?>
    <!--<link href="<?php echo e(asset('/css/event_notification.css')); ?>" />-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_title','Event Notifications'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row content">
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                <div class="col-sm-6">  
                    <h1 class="page-title">
                        <i class="voyager-star"></i>
                        <?php echo e(setting('admin.admin_notification_title')); ?>

                    </h1> 
                </div>
                <div class="col-sm-6 text-right">  
                    <span class="page-title">  
                        <a href="#" class="see-all" onclick="filter(event, 'seeAll');" id="seeAll">See all  <i class="glyphicon glyphicon-arrow-right"></i></a>  
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('voyager::alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="page-content browse container-fluid">
        <div class="row"> 
            <!-- Schedule Item 1 -->
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8" id="listingWidget">
                <!-- Schedule Top Navigation -->
                <nav class="nav nav-tabs notification-nav-tabs" id="navParent">
                    <a href="#" class="nav-link active" onclick="filter(event, 'upTo2Days');" id="upTo2Days">UpTo 2 Days</a>
                    <a href="#" class="nav-link" onclick="filter(event, 'nxtWeek');" id="nxtWeek" >Next Week</a>
                    <a href="#" class="nav-link" onclick="filter(event, 'nxtMonth');" id="nxtMonth">All Upcoming</a>
                    <a href="#" class="nav-link" onclick="filter(event, 'past');" id="past">Past</a>
                </nav> 
                <div class="table-responsive" id="listingBlocks">
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
                            <button id="load_more_button" disabled="disabled" onclick="filter(event, 'onLoadData', 2)" class="btn btn-primary" style="background: #a2caee !important">Load More</button>
                        </div>
                    </div>
                <?php else: ?> 
                    <div class="card mb-3 card-body">
                        <div class="timetable-item norecord-found">
                            <span>No Record Found</span>
                        </div>
                    </div>
                <?php endif; ?>
                </div>


            </div> 
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <input type="hidden" name="dateRoute" value="<?php echo e(route('notification/dateFilter')); ?>" id="dateRoute" />
                <div id="j_weeklyCalendar" class="myWeeklyCalendarJ">
                    <div class="text-center">
                        <div class="month">
                            <div class="date">
                                <h1>
                                    <b role="month_selector"></b>
                                    <b role="year_selector"></b>
                                </h1>
                            </div>
                            <div class="arrows">
                                <a href="javascript:;" title="Last Week" class="prev_icon" role="prev_week">
                                    <i class="voyager-angle-left"></i>
                                </a> 
                                <a href="javascript:;" title="Next Week" class="next_icon" role="next_week">
                                    <i class="voyager-angle-right"></i>
                                </a>
                            </div>
                        </div> 
                        <span style="margin-left: 20px;display: none">No.<b role="week_selector"></b>Week</span>

                        <div class="wrapper">
                            <div class="weekdays">
                                <ul role="weeks_ch" class="weeklyBox"></ul>
                            </div>
                            <div class="days">
                                <ul data-group="calendar" role="weeklyCalendarView" class="weeklyCalendarBox"></ul>
                            </div>
                        </div>
                    </div> 
                </div>  
                <div class="bst-time-zone bst-border">
                    <div class=" text-center">
                        <iframe src="https://free.timeanddate.com/clock/i8ybqip2/n136/fs16/fc76838f/tct/pct/ftb/pa20/tt0/tw1/th1/ta1/tb2" frameborder="0" height="58" allowtransparency="true"></iframe>

                    </div> 
                </div>

                <div class="ist-time-zone ist-border">
                    <div class=" text-center">
                        <iframe src="https://free.timeanddate.com/clock/i8ybqip2/n176/fs16/fc76838f/tct/pct/ftb/pa20/tt0/tw1/th1/ta1/tb2" frameborder="0"  height="58" allowtransparency="true"></iframe> 
                    </div> 
                </div> 

            </div>  
        </div>
    </div>
<?php $__env->stopSection(); ?> 

<?php $__env->startSection('javascript'); ?>  
    <script src="<?php echo e(asset('/js/weeklyCalendar.js')); ?>"></script> 
    <script src="https://cdn.jsdelivr.net/gh/hilios/jQuery.countdown@2.2.0/dist/jquery.countdown.min.js"></script> 
    <script> 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });   

        /**
         * function: Filter
         * args: event, filterName, pageStart with default 1;
         * Provide Listing of Events onclick of filter
         */
        function filter(e, fname, start = 1) { 

            /**
             * remove tab active class
             */
            const allElements = document.querySelectorAll('.notification-nav-tabs *'); 
            allElements.forEach((element) => {
                element.classList.remove('active');
            }); 

            if(e !== null) {
                e.preventDefault(); 
            }    

            var type = "GET";
            var ajaxurl = "<?php echo e(route('notification/filter')); ?>"; 
            //var loding_url = "<?php echo e(asset('/resources/css/voyager-assets.png')); ?>";
            var loding_url = 'https://adddepot.co/admin/voyager-assets?path=images%2Flogo-icon.png';

            $.ajax({
                type: type,
                url: ajaxurl+'?page='+start,
                data: {filter: fname, _token: '<?php echo e(csrf_token()); ?>' },
                dataType: 'json',
                beforeSend: function() {
                    $('#'+fname).attr('disabled', true);
                    $('#listingBlocks').after('<span class="wait">&nbsp;<img src="'+loding_url+'" alt="loading" /></span>');
                },
                complete: function() {
                    $('#'+fname).attr('disabled', false);
                    $('.wait').remove();
                },
                success: function (data) {  

                    start = data.next;
                    /**
                     * Make tab active
                     */
                    if(fname == 'upTo2Days' || fname == 'nxtWeek' || fname== 'nxtMonth' || fname=='past') {
                        document.getElementById(fname).classList.add('active');   
                    }  
                     
                    /**
                     * add ajax data to container
                     */
                     console.log(data.html)
                    document.getElementById("listingBlocks").innerHTML = data.html; 

                    /**
                     * run timer/countdown
                     */
                    countDownCall(); 

                    if(data.next > data.lastPage) {  
                        $('#load_more_button').attr('disabled', true);
                        $('#load_more_button').attr('style', 'background: #a2caee !important');
                    } else { 
                        $('#load_more_button').attr('disabled', false);
                    }
                },
                error: function (data) {
                   alert("There is error in filter Ajax function: "+data);
                }
            }); 

            return false;
        }

        /**
         * default weekly calendar
         */
        weeklyCalendar('#j_weeklyCalendar',{
            /**
             * click specific date callback
             */
            clickDate:function(dateTime) {
                /**
                 * var: onsale_date
                 */
                var onsale_date = dateTime['year']+'-'+dateTime['month']+'-'+dateTime['date'];

                /**
                 * this filter event will trigger
                 * once user click on specific date
                 * from the calender
                 * onsale_date: is the specific date 
                 * clicked by the user;
                 */
                filter(e=null, onsale_date);
            }
        });  

        /**
         *  timer/countdown
         */
        var countDownCall = function () {
            $('[data-countdown]').each(function() {
                var $this = $(this), finalDate = $(this).data('countdown');
                $this.countdown(finalDate)
                    .on('update.countdown', function(event) {
                        var format = '%H:%M:%S';
                        if(event.offset.totalDays > 0) {
                            format = '%D day%!d ' + format;
                        } 
                        $(this).html(event.strftime(format));
                    })
                    .on('finish.countdown', function(event) {
                        $(this).html('Event time has expired!').parent().addClass('disabled');
                        $(this).parent().addClass('text-expired');
                     });
            });
        }
        
        countDownCall();  
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('voyager::master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/adddepot/public_html/resources/views//vendor/voyager/notification/browse.blade.php ENDPATH**/ ?>