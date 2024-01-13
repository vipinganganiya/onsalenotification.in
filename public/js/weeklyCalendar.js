"use strict";
/**
  * Weekly Calendar Control
  * @param {string} container
  * @param {date} options.defaultDate 
  * @param {Array} options.disabledDate
  * @param {Array} options.toDoDate
  * @param {function} options.clickDate
  * @param {function} options.getSelectedDate
*/
function weeklyCalendar(container,options) {
  var options = options || {};
  var $$ = function (selector) {
    return document.querySelector(container + ' [role=' + selector + ']');
  }
  /**
   * Zero Padding
  */
  var zeroize = function(n){
    var r = (n < 10 ? "0" + n : n);
    return r
  };

  var d = options['defaultDate'] ? new Date(options['defaultDate']) : new Date();
  var isStartMon = options['isStartMon']
  var activeDay =  isStartMon ? d.getDay() - 1 : d.getDay(),
    activeDate = zeroize(d.getDate()),
    activeMonth = zeroize(d.getMonth() + 1),
    activeYear = d.getFullYear();
  var lis = $$('weeklyCalendarView').getElementsByTagName('li'),
    aTags = $$('weeklyCalendarView').getElementsByTagName('a');

  /*Create Week*/
  var creatWeek = function () {
    var span = '';
    var weeks = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    var weeks_ch = isStartMon ? weeks.slice(1).concat(weeks.slice(0,1)) : weeks;
    for (var i = 0, len = weeks_ch.length; i < len; i++) {
      span += '<li>' + weeks_ch[i] + '</li>';
    };
    $$('weeks_ch').innerHTML = span;
  }
  creatWeek();

  /*Dynamically set year and month*/
  var setYearMonth = function () {
    $$('year_selector').innerHTML = zeroize(parseInt(aTags[0].dataset.year));

    var regExp = /^0[0-9].*$/;
    const month = ["January","February","March","April","May","June","July","August","September","October","November","December"]; 
    var monthValue = parseInt(aTags[0].title)-1; 

    $$('month_selector').innerHTML = month[monthValue];
  }

  /**
    * Calculate past or future time
    * @param {return} obj  
    * @param {number} num  
  */
  var calcTime = function (num) {
    var num = num || 0,
      someTime = d.getTime() + (24 * 60 * 60 * 1000) * num,
      someYear = new Date(someTime).getFullYear(),
      someMonth = zeroize(new Date(someTime).getMonth() + 1), 
      someDate = zeroize(new Date(someTime).getDate()); 
    var obj = {
      "year": someYear,
      "month": someMonth,
      "date": someDate
    };
    return obj;
  }

  /**
   * Create a weekly calendar
   * @param {Number} someDay
  */
  var creatWeekCalendar = function (someDay) {
    var _d = new Date();
    var currDate = zeroize(_d.getDate()),
      currMonth = zeroize(_d.getMonth() + 1),
      currYear = _d.getFullYear();    
    var a = '';
    for (var i = someDay, len = someDay + 7; i < len; i++) { 
      var dateParam = calcTime(i).year+"/"+calcTime(i).month+"/"+calcTime(i).date;
      callAjax(dateParam);
        //today's date
      if (calcTime(i).year === currYear && calcTime(i).month === currMonth && calcTime(i).date === currDate) {
        a += '<li data-date="' + calcTime(i).date + '" class="active" data-role="active"><a href="javascript:;" data-year="' + calcTime(i).year + '" data-month="' + calcTime(i).month + '" data-date="' + calcTime(i).date + '"  title="' + calcTime(i).month + '">' + calcTime(i).date + '</a></li>';
      }else if //selected date
       (calcTime(i).year === activeYear && calcTime(i).month === activeMonth && calcTime(i).date === activeDate) {
        a += '<li data-date="' + calcTime(i).date + '" class="clickActive"><a href="javascript:;" data-year="' + calcTime(i).year + '" data-month="' + calcTime(i).month + '" data-date="' + calcTime(i).date + '" title="' + calcTime(i).month + '">' + calcTime(i).date + '</a></li>';
      } else {
        a += '<li data-date="' + calcTime(i).date + '"><a href="javascript:;" data-year="' + calcTime(i).year + '" data-month="' + calcTime(i).month + '" data-date="' + calcTime(i).date + '" title="' + calcTime(i).month + '">' + calcTime(i).date + '</a></li>';
      }
    };
    $$('weeklyCalendarView').innerHTML = a;
  }

  /*set date status*/
  var setStatus = function(type){
      var typeMap = {
        disabledDate: 'is-disabled',
        toDoDate:'is-todo'
      }
      var arr = options[type] || [];
      var splitArr = null, arrYear = null, arrMonth = null, arrDate = null;
      var maxLen = 366;
      if (arr.length > maxLen) {
        throw new Error('Unavailable date limit' + maxLen +'within days')
      }
      //Disabled date
      if (arr.length) {          
        for (var index = 0; index < arr.length; index++) {
          splitArr = arr[index].split('-');
          arrYear = splitArr[0];
          arrMonth = splitArr[1];
          arrDate = splitArr[2]; 
          for (var j = 0; j < aTags.length; j++) { 
            if ((arrYear === aTags[j].dataset.year) && (arrMonth === aTags[j].dataset.month) && (arrDate === aTags[j].dataset.date)) {
              aTags[j].classList.add(typeMap[type])
            }
          }
        }
      }
  }

  /*Initialize the weekly calendar*/
  var init = function () {
    creatWeekCalendar(-activeDay);
    setYearMonth();
    setStatus('disabledDate');
    setStatus('toDoDate');
  }
  init()

  //Set initial clicks
  $$('weeklyCalendarView').setAttribute('clickedTimes', 0);
  //get clicks
  var clickedTimes = $$('weeklyCalendarView').getAttribute('clickedTimes');
  var weekNum = $$('week_selector').getAttribute('week');
  /*The week before and the week after*/
  var changeWeek = function (clickedTimes, weekNum) {
    creatWeekCalendar(-activeDay - (7 * clickedTimes));
    $$('weeklyCalendarView').setAttribute('clickedTimes', clickedTimes);   
    setYearMonth();
    setStatus('disabledDate');
    setStatus('toDoDate');
    /*
      Dynamically set the week, there is a problem with the week calculation here, so the week has been hidden, and the week setting attribute is only used to display the week view when clicking the calculation of the previous week and the next week
    */
    $$('week_selector').innerHTML = weekNum;
    $$('week_selector').setAttribute('week', weekNum);
  }

  /*The previous week*/
  $$('prev_week').onclick = function () {
    clickedTimes++;
    weekNum--; 
    changeWeek(clickedTimes, weekNum);
  }

  /*Next Week*/
  $$('next_week').onclick = function () {
    clickedTimes--;
    weekNum++;
    changeWeek(clickedTimes, weekNum);
  }

  /*select date*/
  var selectedYear = null,
      selectedMonth = null,
      selectedDate = null,
      selectedDateTime = {};
  $$('weeklyCalendarView').onclick = function (e) {
    var tagName = e.target.tagName.toLowerCase(); 
    if (tagName === "a") {
      for (var i = 0, len = lis.length; i < len; i++) {
        lis[i].className = '';
        if ((lis[i].className.indexOf('clickActive') < 0) && lis[i].dataset.role === 'active'){
          lis[i].className = 'active';
        }
      }
      e.target.parentNode.className = "clickActive";
      selectedYear = e.target.getAttribute('data-year');
      selectedMonth = zeroize(parseInt(e.target.title));
      selectedDate = e.target.innerHTML;
      selectedDateTime = {
        "year": selectedYear,
        "month": selectedMonth,
        "date": selectedDate
      }
      options['clickDate'] && options['clickDate'](selectedDateTime);
      options['getSelectedDate'] && options['getSelectedDate'](selectedDateTime)
    }
  }

  if (!selectedYear) {
    selectedDateTime = {
      "year": activeYear,
      "month": activeMonth,
      "date": activeDate
    }
    options['getSelectedDate'] && options['getSelectedDate'](selectedDateTime)
  }
}

function callAjax(d) {

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
  }); 

  var type = "GET";
  var ajaxurl = document.getElementById('dateRoute').value; 
  
  if(ajaxurl) {
    $.ajax({
      type: type,
      url: ajaxurl,
      data: {_dfilter: d, _token: '{{csrf_token()}}' },
      dataType: 'json',
      beforeSend: function() {
      },
      complete: function() { 
      },
      success: function (data) {  
        if(data['_c'] > 0 ) {  
          var el = document.querySelector("ul.weeklyCalendarBox li[data-date='"+data['_d']+"']");
          el.classList.add("has-event");
        }
      },
      error: function (data) {
         // console.log(data);
      }
    });
  } 
}