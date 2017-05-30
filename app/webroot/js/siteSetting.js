function smtpDetails() {
    if ($("#SitesettingMailOptionSMTP").is(":checked")) {
        $("#smtp").show();
    } else {
        $("#smtp").hide();
    }
}

$(document).ready(function () {
    smtpDetails();
    offlineDetails();
    $("input[name='data[Sitesetting][mail_option]']").click(function () {
        smtpDetails();
    });
    $("input[name='data[Sitesetting][offline_status]']").click(function () {
        offlineDetails();
    });

    $(".otherLanguage").click(function () {
        $("#others").slideToggle(500);

    });

    setTimeout(function () {
        $('#flashMessage').fadeOut();
    }, 3000);


    $(".slotTime").each(function () {
        var slotId = $(this).attr('id');
        var OpenTime = $('#' + slotId + '_opentime').val();
        var CloseTime = $('#' + slotId + '_closetime').val();

        $('#' + slotId + '_from').html(OpenTime);
        $('#' + slotId + '_to').html(CloseTime);

        var hours = Number(OpenTime.match(/^(\d+)/)[1]);
        var minutes = Number(OpenTime.match(/:(\d+)/)[1]);
        // var AMPM = OpenTime.match(/\s(.*)$/)[1];
        // if(hours<12) hours = hours+12;
        // if(hours==12) hours = hours-12;
        var sHours = hours.toString();
        var sMinutes = minutes.toString();
        if (hours < 10)
            sHours = "0" + sHours;
        if (minutes < 10)
            sMinutes = "0" + sMinutes;

        var open = parseFloat(sHours + "." + sMinutes) * 60;

        var hours = Number(CloseTime.match(/^(\d+)/)[1]);
        var minutes = Number(CloseTime.match(/:(\d+)/)[1]);
        // var AMPM = CloseTime.match(/\s(.*)$/)[1];
        // if(hours<12) hours = hours+12;
        // if(hours==12) hours = hours-12;
        var sHours = hours.toString();
        var sMinutes = minutes.toString();
        if (hours < 10)
            sHours = "0" + sHours;
        if (minutes < 10)
            sMinutes = "0" + sMinutes;

        var close = parseFloat(sHours + "." + sMinutes) * 60;


        $('#' + slotId).slider({
            range: true,
            min: 0,
            max: 1440,
            step: 15,
            values: [open, close],
            slide: function (e, ui) {
                var hours1 = Math.floor(ui.values[0] / 60);
                var minutes1 = ui.values[0] - (hours1 * 60);

                if (hours1.length == 1)
                    hours1 = '0' + hours1;
                if (minutes1.length == 1)
                    minutes1 = '0' + minutes1;
                if (minutes1 == 0)
                    minutes1 = '00';
                if (hours1 >= 12) {
                    if (hours1 == 12) {
                        hours1 = hours1;
                        minutes1 = minutes1;
                    } else {
                        hours1 = hours1; // hours1 = hours1 - 12;
                        minutes1 = minutes1;
                    }
                } else {
                    hours1 = hours1;
                    minutes1 = minutes1;
                }
                /*if (hours1 == 0) {
                 hours1 = 0;
                 minutes1 = minutes1;
                 }*/

                $('#' + slotId + '_from').html(hours1 + ':' + minutes1);
                $('#' + slotId + '_opentime').val(hours1 + ':' + minutes1);

                var hours2 = Math.floor(ui.values[1] / 60);
                var minutes2 = ui.values[1] - (hours2 * 60);


                if (hours2.length == 1)
                    hours2 = '0' + hours2;
                if (minutes2.length == 1)
                    minutes2 = '0' + minutes2;
                if (minutes2 == 0)
                    minutes2 = '00';
                if (hours2 >= 12) {
                    if (hours2 == 12) {
                        hours2 = hours2;
                        minutes2 = minutes2;
                    } else if (hours2 == 24) {
                        hours2 = 23;
                        minutes2 = "59";
                    } else {
                        hours2 = hours2;
                        minutes2 = minutes2;
                    }
                } else {
                    hours2 = hours2;
                    minutes2 = minutes2;
                }

                $('#' + slotId + '_to').html(hours2 + ':' + minutes2);
                $('#' + slotId + '_closetime').val(hours2 + ':' + minutes2);
            }
        });
    });

    $(".slotTimeAdd").each(function () {
        var slotId = $(this).attr('id');

        $('#' + slotId).slider({
            range: true,
            min: 0,
            max: 1440,
            step: 15,
            values: [600, 720],
            slide: function (e, ui) {
                var hours1 = Math.floor(ui.values[0] / 60);
                var minutes1 = ui.values[0] - (hours1 * 60);

                if (hours1.length == 1)
                    hours1 = '0' + hours1;
                if (minutes1.length == 1)
                    minutes1 = '0' + minutes1;
                if (minutes1 == 0)
                    minutes1 = '00';
                if (hours1 >= 12) {
                    if (hours1 == 12) {
                        hours1 = hours1;
                        minutes1 = minutes1;
                    } else {
                        hours1 = hours1 - 12;
                        minutes1 = minutes1;
                    }
                } else {
                    hours1 = hours1;
                    minutes1 = minutes1;
                }
                /*if (hours1 == 0) {
                 hours1 = 12;
                 minutes1 = minutes1;
                 }*/

                $('#' + slotId + '_from').html(hours1 + ':' + minutes1);
                $('#' + slotId + '_opentime').val(hours1 + ':' + minutes1);

                var hours2 = Math.floor(ui.values[1] / 60);
                var minutes2 = ui.values[1] - (hours2 * 60);

                if (hours2.length == 1)
                    hours2 = '0' + hours2;
                if (minutes2.length == 1)
                    minutes2 = '0' + minutes2;
                if (minutes2 == 0)
                    minutes2 = '00';
                if (hours2 >= 12) {
                    if (hours2 == 12) {
                        hours2 = hours2;
                        minutes2 = minutes2;
                    } else if (hours2 == 24) {
                        hours2 = 23;
                        minutes2 = "59 PM";
                    } else {
                        hours2 = hours2;
                        minutes2 = minutes2;
                    }
                } else {
                    hours2 = hours2;
                    minutes2 = minutes2;
                }

                $('#' + slotId + '_to').html(hours2 + ':' + minutes2);
                $('#' + slotId + '_closetime').val(hours2 + ':' + minutes2);
            }
        });
    });



    //Admin Statistics Start
    $(".city_sales").datepicker({
        dateFormat: 'm yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('m yy', new Date(year, month, 1)));
            var date = $(this).val();
            city_sales_generate_graph(date);
        }
    });

    $(".city_sales").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    $(".average_budget").datepicker({
        dateFormat: 'm yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('m yy', new Date(year, month, 1)));
            var date = $(this).val();
            average_budget_generate_graph(date);
        }
    });

    $(".average_budget").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });


    function average_budget(jsString, date) {

        Highcharts.chart('average_budget', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Average budget / Category'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: 0,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Average Budget'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Orders at ' + date + ': <b>{point.y:.1f}€</b>'
            },
            series: [{
                    name: 'Average budget',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }
    function average_budget_generate_graph(date) {
        $.post(rp + '/Commons/storestatistics', {action: "average_budget", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {

                var arr = [];
                arr.push("Average Pickup Budget");
                arr.push(parseFloat(js[i][0].collection_average));
                jsString.push(arr);

                var arr = [];
                arr.push("Average Delivery Budget");
                arr.push(parseFloat(js[i][0].delivery_average));
                jsString.push(arr);

                var arr = [];
                arr.push("Average Total Budget");
                arr.push(parseFloat(js[i][0].total));
                jsString.push(arr);
            }
            average_budget(jsString, date);
        });
    }


    function city_sales(jsString, date) {

        Highcharts.chart('city_sales', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Orders percentage per month'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Orders Percentage'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Orders at ' + date + ': <b>{point.y:.1f}%</b>'
            },
            series: [{
                    name: 'Orders',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }

    function city_sales_generate_graph(date) {
        $.post(rp + '/Commons/storestatistics', {action: "rushtime", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                var arr = [];
                arr.push(js[i][0].name);
                arr.push(parseFloat(js[i][0].y));
                jsString.push(arr);
            }
            city_sales(jsString, date);
        });
    }

    $(".most_requested_item").datepicker({
        dateFormat: 'm yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('m yy', new Date(year, month, 1)));
            var date = $(this).val();
            most_requested_item_generate_graph(date);
        }
    });

    $(".most_requested_item").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    function most_requested_item(jsString, date) {

        Highcharts.chart('most_requested_item', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Most requested items per month'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Most requested items'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Most requested items at ' + date + ': <b>{point.y:.1f}</b>'
            },
            series: [{
                    name: 'most_request_items',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }
    function most_requested_item_generate_graph(date) {
        $.post(rp + '/Commons/storestatistics', {action: "most_requested_item", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                var arr = [];
                arr.push(js[i]["shopping_carts"].name);
                arr.push(parseFloat(js[i][0].y));
                jsString.push(arr);
            }
            most_requested_item(jsString, date);
        });
    }

    $(".time_more_money").datepicker({
        dateFormat: 'm yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('m yy', new Date(year, month, 1)));
            var date = $(this).val();
            time_more_money_generate_graph(date);
        }
    });

    $(".time_more_money").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    function time_more_money(jsString, date) {

        Highcharts.chart('time_more_money', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Total revenue generated / day and hour'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total revenue generated / day and hour'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Total revenue generated on ' + date + ': <b>{point.y:.1f}€</b>'
            },
            series: [{
                    name: 'time_more_money',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }
    function time_more_money_generate_graph(date) {
        $.post(rp + '/Commons/storestatistics', {action: "time_more_money", date: date, storeId: $("#storeId").val()}, function (response) {

            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                var arr = [];
                arr.push(js[i][0].name);
                arr.push(parseFloat(js[i][0].y));
                jsString.push(arr);
            }
            time_more_money(jsString, date);
        });
    }

    function store_reservations(monthsArr, date) {

        Highcharts.chart('store_reservations', {
            title: {
                text: 'Total Reservations / Month'
            },
            xAxis: {
                tickInterval: 1,
                name: 'Months'
            },
            yAxis: {
                type: 'reservations',
                minorTickInterval: 0.1,
                name: 'Total Reservations / month'
            },
            tooltip: {
                headerFormat: '<b>Total Reservations / Month</b><br />',
                pointFormat: 'Month = {point.x}, Total Reservations = {point.y}'
            },
            series: [{
                    data: monthsArr,
                    pointStart: 1
                }]
        });
    }
    function store_reservations_generate_graph(date) {
        var monthsArr = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $.post(rp + '/Commons/storestatistics', {action: "store_reservations", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                monthsArr[parseInt(js[i][0].name) - 1] = parseFloat(js[i][0].y);
            }
            store_reservations(monthsArr, date);
        });
    }

    function break_down_revenue(jsString, date) {
        var js = JSON.parse(jsString);
        Highcharts.chart('break_down_revenue', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Total revenue generated by Pickup and Delivery'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    }
                }
            },
            series: [{
                    name: 'Total Revenue',
                    colorByPoint: true,
                    data: js
                }]
        });

    }

    function break_down_revenue_generate_graph(date) {

        $.post(rp + '/Commons/storestatistics', {action: "break_down_revenue", date: date, storeId: $("#storeId").val()}, function (response) {
            var arr = [];
            var jsString = "";
            var js = JSON.parse(response);
            for (var i = 0; i < js.length; i++) {
                var total = (parseFloat(js[0][0].delivery) + parseFloat(js[1][0].delivery));
                if (i == 0) {
                    jsString += '[{ "name":"Delivery" , "y":' + (parseFloat(js[i][0].delivery) / total) * 100 + ' },';
                } else {
                    jsString += ' { "name":"Pickup" , "y":' + (parseFloat(js[i][0].delivery) / total) * 100 + ' }]';
                }
            }
            break_down_revenue(jsString, date);
        });
    }


    //most requested delivery items
    $(".most_requested_delivery_item").datepicker({
        dateFormat: 'm yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('m yy', new Date(year, month, 1)));
            var date = $(this).val();
            most_requested_delivery_item_generate_graph(date);
        }
    });

    $(".most_requested_delivery_item").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    function most_requested_delivery_item(jsString, date) {

        Highcharts.chart('most_requested_delivery_item', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Most requested delivery items per month'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Most requested delivery items'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Most requested delivery items at ' + date + ': <b>{point.y:.1f}</b>'
            },
            series: [{
                    name: 'most_request_items',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }
    function most_requested_delivery_item_generate_graph(date) {
        $.post(rp + '/Commons/storestatistics', {action: "most_requested_delivery_item", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                var arr = [];
                arr.push(js[i]["sc"].name);
                arr.push(parseFloat(js[i][0].y));
                jsString.push(arr);
            }
            most_requested_delivery_item(jsString, date);
        });
    }

    //most request collection items
    $(".most_requested_collection_item").datepicker({
        dateFormat: 'm yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('m yy', new Date(year, month, 1)));
            var date = $(this).val();
            most_requested_collection_item_generate_graph(date);
        }
    });

    $(".most_requested_collection_item").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    function most_requested_collection_item(jsString, date) {

        Highcharts.chart('most_requested_collection_item', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Most requested Pick up items per month'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Most requested pickup items'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Most requested pickup items on ' + date + ': <b>{point.y:.1f}</b>'
            },
            series: [{
                    name: 'most_request_collection_item',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }
    function most_requested_collection_item_generate_graph(date) {
        $.post(rp + '/Commons/storestatistics', {action: "most_requested_collection_item", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                var arr = [];
                arr.push(js[i]["sc"].name);
                arr.push(parseFloat(js[i][0].y));
                jsString.push(arr);
            }
            most_requested_collection_item(jsString, date);
        });
    }

    function total_orders_month(monthsArr, date) {

        Highcharts.chart('total_orders_month', {
            title: {
                text: 'Total Orders / Month in ' + date
            },
            xAxis: {
                tickInterval: 1,
                name: 'Months'
            },
            yAxis: {
                type: 'Orders',
                minorTickInterval: 0.1,
                name: 'Total Orders / month'
            },
            tooltip: {
                headerFormat: '<b>Total Orders / Month</b><br />',
                pointFormat: 'Month = {point.x}, Total Orders = {point.y}'
            },
            series: [{
                    data: monthsArr,
                    pointStart: 1
                }]
        });
    }
    function total_orders_month_generate_graph(date) {
        var monthsArr = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $.post(rp + '/Commons/storestatistics', {action: "total_orders_month", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                monthsArr[parseInt(js[i][0].name) - 1] = parseFloat(js[i][0].y);
            }
            total_orders_month(monthsArr, date);
        });
    }

    function total_revenue_month(monthsArr, date) {

        Highcharts.chart('total_revenue_month', {
            title: {
                text: 'Total Revenue / Month in ' + date
            },
            xAxis: {
                tickInterval: 1,
                name: 'Months'
            },
            yAxis: {
                type: 'Orders',
                minorTickInterval: 0.1,
                name: 'Total Orders / month'
            },
            tooltip: {
                headerFormat: '<b>Total Revenue / Month</b><br />',
                pointFormat: 'Month = {point.x}, Total Revenue = {point.y}€'
            },
            series: [{
                    data: monthsArr,
                    pointStart: 1
                }]
        });
    }
    function total_revenue_month_generate_graph(date) {
        var monthsArr = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $.post(rp + '/Commons/storestatistics', {action: "total_revenue_month", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                monthsArr[parseInt(js[i][0].name) - 1] = parseFloat(js[i][0].y);
            }
            total_revenue_month(monthsArr, date);
        });
    }

    $("#storeId").change(function () {
        if ($.trim($("#storeId").val()).length == 0) {
            $("#errorMessage").html("Sélectionnez un restaurant de la liste");
            $("#errorMessage").show();
        } else {
            $("#errorMessage").html("");
            $("#errorMessage").hide();
            generateGraph();
        }
    });

    function generateGraph() {
        var currentTime = new Date();
        var month = currentTime.getMonth() + 1;
        var year = currentTime.getFullYear();
        city_sales_generate_graph(month + " " + year);
        average_budget_generate_graph(month + " " + year);
        most_requested_item_generate_graph(month + " " + year);
        time_more_money_generate_graph(month + " " + year);
        store_reservations_generate_graph(year);
        break_down_revenue_generate_graph(year);
        most_requested_delivery_item_generate_graph(month + " " + year);
        most_requested_collection_item_generate_graph(month + " " + year);
        total_orders_month_generate_graph(year);
        total_revenue_month_generate_graph(year);
    }

//statisticss end
    //Admin Statistics End

//admin statistics start

    var currentTime = new Date();
    var month = currentTime.getMonth() + 1;
    var year = currentTime.getFullYear();
    admin_most_sales_generate_graph();
    admin_break_down_revenue_generate_graph();
    aggregated_delivery_pickup_total_budget_generate_graph();
    admin_reservations_generate_graph();
    admin_total_orders_month_generate_graph(year);
    admin_total_revenue_month_generate_graph(year);
    admin_rush_time_generate_graph();

    function admin_most_sales(jsString) {

        Highcharts.chart('admin_most_sales', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Total Revenue / Top 20 Restaurants'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total Revenue'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Total Revenue <b>{point.y:.1f}€</b>'
            },
            series: [{
                    name: 'admin_most_sales',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }
    function admin_most_sales_generate_graph() {
        $.post(rp + '/Commons/adminstatistics', {action: "admin_most_sales"}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                var arr = [];
                arr.push(js[i]["s"].name);
                arr.push(parseFloat(js[i][0].y));
                jsString.push(arr);
            }
            admin_most_sales(jsString);
        });
    }

    function admin_break_down_revenue(jsString) {
        var js = JSON.parse(jsString);
        Highcharts.chart('admin_break_down_revenue', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Total revenue generated by Pickup and Delivery'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    }
                }
            },
            series: [{
                    name: 'Total Revenue',
                    colorByPoint: true,
                    data: js
                }]
        });

    }
    function admin_break_down_revenue_generate_graph() {

        $.post(rp + '/Commons/adminstatistics', {action: "admin_break_down_revenue"}, function (response) {
            var arr = [];
            var jsString = "";
            var js = JSON.parse(response);
            for (var i = 0; i < js.length; i++) {
                var total = (parseFloat(js[0][0].delivery) + parseFloat(js[1][0].delivery));
                if (i == 0) {
                    jsString += '[{ "name":"Delivery" , "y":' + (parseFloat(js[i][0].delivery) / total) * 100 + ' },';
                } else {
                    jsString += ' { "name":"Pickup" , "y":' + (parseFloat(js[i][0].delivery) / total) * 100 + ' }]';
                }
            }
            admin_break_down_revenue(jsString);
        });
    }

    function aggregated_delivery_pickup_total_budget(jsString) {

        Highcharts.chart('aggregated_delivery_pickup_total_budget', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Average budget / Category'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: 0,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Average Budget'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: '<b>{point.y:.1f}€</b>'
            },
            series: [{
                    name: 'aggregated_delivery_pickup_total_budget',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }
    function aggregated_delivery_pickup_total_budget_generate_graph() {
        $.post(rp + '/Commons/adminstatistics', {action: "aggregated_delivery_pickup_total_budget"}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {

                var arr = [];
                arr.push("Average Pickup Budget");
                arr.push(parseFloat(js[i][0].collection_average));
                jsString.push(arr);

                var arr = [];
                arr.push("Average Delivery Budget");
                arr.push(parseFloat(js[i][0].delivery_average));
                jsString.push(arr);

                var arr = [];
                arr.push("Average Total Budget");
                arr.push(parseFloat(js[i][0].total));
                jsString.push(arr);
            }
            aggregated_delivery_pickup_total_budget(jsString);
        });
    }

    function admin_reservations(monthsArr) {

        Highcharts.chart('admin_reservations', {
            title: {
                text: 'Total Reservations / Month'
            },
            xAxis: {
                tickInterval: 1,
                name: 'Months'
            },
            yAxis: {
                type: 'reservations',
                minorTickInterval: 0.1,
                name: 'Total Reservations / month'
            },
            tooltip: {
                headerFormat: '<b>Total Reservations / Month</b><br />',
                pointFormat: 'Month = {point.x}, Total Reservations = {point.y}'
            },
            series: [{
                    data: monthsArr,
                    pointStart: 1
                }]
        });
    }
    function admin_reservations_generate_graph() {
        var monthsArr = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $.post(rp + '/Commons/adminstatistics', {action: "admin_reservations"}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                monthsArr[parseInt(js[i][0].name) - 1] = parseFloat(js[i][0].y);
            }
            store_reservations(monthsArr);
        });
    }

    function admin_total_orders_month(monthsArr, date) {

        Highcharts.chart('admin_total_orders_month', {
            title: {
                text: 'Total Orders / Month in ' + date
            },
            xAxis: {
                tickInterval: 1,
                name: 'Months'
            },
            yAxis: {
                type: 'Orders',
                minorTickInterval: 0.1,
                name: 'Total Orders / month'
            },
            tooltip: {
                headerFormat: '<b>Total Orders / Month</b><br />',
                pointFormat: 'Month = {point.x}, Total Orders = {point.y}'
            },
            series: [{
                    data: monthsArr,
                    pointStart: 1
                }]
        });
    }
    function admin_total_orders_month_generate_graph(date) {
        var monthsArr = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $.post(rp + '/Commons/adminstatistics', {action: "admin_total_orders_month", date: date}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                monthsArr[parseInt(js[i][0].name) - 1] = parseFloat(js[i][0].y);
            }
            admin_total_orders_month(monthsArr, date);
        });
    }

    function admin_total_revenue_month(monthsArr, date) {

        Highcharts.chart('admin_total_revenue_month', {
            title: {
                text: 'Total Revenue / Month in ' + date
            },
            xAxis: {
                tickInterval: 1,
                name: 'Months'
            },
            yAxis: {
                type: 'Orders',
                minorTickInterval: 0.1,
                name: 'Total Orders / month'
            },
            tooltip: {
                headerFormat: '<b>Total Revenue / Month</b><br />',
                pointFormat: 'Month = {point.x}, Total Revenue = {point.y}€'
            },
            series: [{
                    data: monthsArr,
                    pointStart: 1
                }]
        });
    }
    function admin_total_revenue_month_generate_graph(date) {
        var monthsArr = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $.post(rp + '/Commons/adminstatistics', {action: "admin_total_revenue_month", date: date}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                monthsArr[parseInt(js[i][0].name) - 1] = parseFloat(js[i][0].y);
            }
            admin_total_revenue_month(monthsArr, date);
        });
    }

    function admin_rush_time(jsString) {

        Highcharts.chart('admin_rush_time', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Orders percentage / Hour'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Orders Percentage'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: '<b>{point.y:.1f}%</b>'
            },
            series: [{
                    name: 'Orders',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }

    function admin_rush_time_generate_graph() {
        $.post(rp + '/Commons/adminstatistics', {action: "admin_rush_time"}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                var arr = [];
                arr.push(js[i][0].name);
                arr.push(parseFloat(js[i][0].y));
                jsString.push(arr);
            }
            admin_rush_time(jsString);
        });
    }

//admin statistics end

});

function offlineDetails() {
    if ($("#SitesettingOfflineStatusYes").is(":checked")) {
        $("#offlineReason").show();
    } else {
        $("#offlineReason").hide();
    }
}



function validate() {

    var SitesettingSiteName = $.trim($("#SitesettingSiteName").val());
    var SitesettingAdminName = $.trim($("#SitesettingAdminName").val());
    var SitesettingAdminEmail = $.trim($("#SitesettingAdminEmail").val());
    var SitesettingContactUsEmail = $.trim($("#SitesettingContactUsEmail").val());
    var SitesettingInvoiceEmail = $.trim($("#SitesettingInvoiceEmail").val());
    var SitesettingContactPhone = $.trim($("#SitesettingContactPhone").val());
    var SitesettingOrderEmail = $.trim($("#SitesettingOrderEmail").val());

    var SitesettingSiteAddress = $.trim($("#SitesettingSiteAddress").val());
    var SitesettingSiteCountry = $.trim($("#SitesettingSiteCountry").val());
    var SitesettingSiteState = $.trim($("#SitesettingSiteState").val());
    var SitesettingSiteCity = $.trim($("#SitesettingSiteCity").val());
    var SitesettingSiteZip = $.trim($("#SitesettingSiteZip").val());

    var SitesettingSmtpHost = $.trim($("#SitesettingSmtpHost").val());
    var SitesettingSmtpPort = $.trim($("#SitesettingSmtpPort").val());
    var SitesettingSmtpUsername = $.trim($("#SitesettingSmtpUsername").val());
    var SitesettingSmtpPassword = $.trim($("#SitesettingSmtpPassword").val());

    var SitesettingVatNo = $.trim($("#SitesettingVatNo").val());
    var SitesettingVatPercent = $.trim($("#SitesettingVatPercent").val());
    var SitesettingCardFee = $.trim($("#SitesettingCardFee").val());
    var SitesettingInvoiceDuration = $.trim($("#SitesettingInvoiceDuration").val());

    var SitesettingSmsToken = $.trim($("#SitesettingSmsToken").val());
    var SitesettingSmsId = $.trim($("#SitesettingSmsId").val());
    var SitesettingSmsSourceNumber = $.trim($("#SitesettingSmsSourceNumber").val());

    var SitesettingOtherLanguage = $.trim($('#SitesettingOtherLanguage').val());

    var Sitesettingmailchimpkey = $.trim($("#SitesettingMailchimpKey").val());
    var Sitesettingmailchimplist = $.trim($("#SitesettingMailchimpListId").val());

    var Sitesettingfacbookapi = $.trim($("#SitesettingFacebookApiId").val());
    var Sitesettingfacbooksecret = $.trim($("#SitesettingFacebookSecretKey").val());

    var Sitesettinggoogleapi = $.trim($("#SitesettingGoogleApiId").val());
    var Sitesettinggooglesecret = $.trim($("#SitesettingGoogleSecretKey").val());

    var SitesettingPusherKey = $.trim($("#SitesettingPusherKey").val());
    var SitesettingPusherSecret = $.trim($("#SitesettingPusherSecret").val());
    var SitesettingPusherId = $.trim($("#SitesettingPusherId").val());
    var SitesettingHomepageYoutubeLink = $.trim($("#SitesettingHomepageYoutubeLink").val());

    if (SitesettingSiteName == '') {
        $("[href=#site]").trigger('click');
        $("#siteError").html("Entrez le nom du site svp");
        $("#SitesettingSiteName").focus();
        return false;
    } else if (($("#SitesettingHomepageYoutubeLink").val() != '') && validateYouTubeUrl($("#SitesettingHomepageYoutubeLink").val())) {
        $("#siteError").html("Veuillez saisir un lien youtube valide pour la page d'accueil");
        $("#SitesettingHomepageYoutubeLink").focus();
        return false;
    } else if (SitesettingAdminName == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrez le nom du administrateur svp");
        $("#SitesettingAdminName").focus();
        return false;
    } else if (SitesettingAdminEmail == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrer l’email de l’admin svp");
        $("#SitesettingAdminEmail").focus();
        return false;
    } else if (SitesettingContactUsEmail == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrer l’email du contactez-nous svp");
        $("#SitesettingContactUsEmail").focus();
        return false;
    } else if (SitesettingInvoiceEmail == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrer l’email de facturation svp");
        $("#SitesettingInvoiceEmail").focus();
        return false;
    } else if (SitesettingContactPhone == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrez site numéro de téléphone svp");
        $("#SitesettingContactPhone").focus();
        return false;
    } else if (SitesettingOrderEmail == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrer l’email pour la réception des commandes svp");
        $("#SitesettingOrderEmail").focus();
        return false;
    } else if (SitesettingSiteAddress == '') {
        $("[href=#location]").trigger('click');
        $("#locationError").html("Entrer l’adresse du site svp");
        $("#SitesettingSiteAddress").focus();
        return false;
    } else if (SitesettingSiteCountry == '') {
        $("[href=#location]").trigger('click');
        $("#locationError").html("Sélectionnez le pays svp");
        $("#SitesettingSiteCountry").focus();
        return false;
    } else if (SitesettingSiteState == '') {
        $("[href=#location]").trigger('click');
        $("#locationError").html("Sélectionnez le departement svp");
        $("#SitesettingSiteState").focus();
        return false;
    } else if (SitesettingSiteCity == '') {
        $("[href=#location]").trigger('click');
        $("#locationError").html("Sélectionnez le ville svp");
        $("#SitesettingSiteCity").focus();
        return false;
    } else if (SitesettingSiteZip == '') {
        $("[href=#location]").trigger('click');
        $("#locationError").html("Sélectionnez le code postal svp");
        $("#SitesettingSiteZip").focus();
        return false;
    } else if ($("#SitesettingMailOptionSMTP").is(":checked")) {

        if (SitesettingSmtpHost == '') {
            $("[href=#mail]").trigger('click');
            $("#mailError").html("Entrez smtp host svp");
            $("#SitesettingSmtpHost").focus();
            return false;
        } else if (SitesettingSmtpPort == '') {
            $("[href=#mail]").trigger('click');
            $("#mailError").html("Entrez smtp port svp");
            $("#SitesettingSmtpPort").focus();
            return false;
        } else if (SitesettingSmtpUsername == '') {
            $("[href=#mail]").trigger('click');
            $("#mailError").html("Entrez smtp nom utilisateur svp");
            $("#SitesettingSmtpUsername").focus();
            return false;
        } else if (SitesettingSmtpPassword == '') {
            $("[href=#mail]").trigger('click');
            $("#mailError").html("Entrez le smtp mot de passe");
            $("#SitesettingSmtpPassword").focus();
            return false;
        }
    } else if (SitesettingVatNo == '') {
        $("[href=#invoice]").trigger('click');
        $("#invoiceError").html("Please enter VAT no");
        $("#SitesettingVatNo").focus();
        return false;
    } else if (SitesettingVatPercent == '') {
        $("[href=#invoice]").trigger('click');
        $("#invoiceError").html("Please enter VAT percentage");
        $("#SitesettingVatPercent").focus();
        return false;
    } else if (SitesettingCardFee == '') {
        $("#invoiceError").html("Please enter card fee");
        $("#SitesettingCardFee").focus();
        return false;
    } else if (SitesettingInvoiceDuration == '') {
        $("[href=#invoice]").trigger('click');
        $("#invoiceError").html("Please select invoice time period");
        $("#SitesettingInvoiceDuration").focus();
        return false;
    } else if (SitesettingSmsToken == '') {
        $("[href=#sms]").trigger('click');
        $("#smsError").html("Please enter sms token id");
        $("#SitesettingSmsToken").focus();
        return false;
    } else if (SitesettingSmsId == '') {
        $("[href=#sms]").trigger('click');
        $("#smsError").html("Please enter sms auth id");
        $("#SitesettingSmsId").focus();
        return false;
    } else if (SitesettingSmsSourceNumber == '') {
        $("[href=#sms]").trigger('click');
        $("#smsError").html("Please enter sms source number");
        $("#SitesettingSmsSourceNumber").focus();
        return false;
    } else if ($('#others').is(':visible') && SitesettingOtherLanguage == '') {

        $("[href=#Language]").trigger('click');
        $("#languageError").html("Entrez le un autre langage svp");
        $("#SitesettingOtherLanguage").focus();
        return false;
    } else if (Sitesettingmailchimpkey == '') {
        $("[href=#mailchimp]").trigger('click');
        $("#mailchimpError").html("Please enter mailchimp key");
        $("#Sitesettingmailchimpkey").focus();
        return false;
    } else if (Sitesettingmailchimplist == '') {
        $("[href=#mailchimp]").trigger('click');
        $("#mailchimpError").html("Please enter mailchimp list");
        $("#Sitesettingmailchimplist").focus();
        return false;
    } else if (Sitesettingfacbookapi == '') {
        $("[href=#facebook]").trigger('click');
        $("#facebookError").html("Please enter facebook api key");
        $("#Sitesettingfacbookapi").focus();
        return false;
    } else if (Sitesettingfacbooksecret == '') {
        $("[href=#facebook]").trigger('click');
        $("#facebookError").html("Please enter facebook secret key");
        $("#Sitesettingfacbooksecret").focus();
        return false;
    } else if (Sitesettinggoogleapi == '') {
        $("[href=#google]").trigger('click');
        $("#googleError").html("Please enter google api key");
        $("#Sitesettinggoogleapi").focus();
        return false;
    } else if (Sitesettinggooglesecret == '') {
        $("[href=#google]").trigger('click');
        $("#googleError").html("Please enter google secret key");
        $("#Sitesettinggooglesecret").focus();
        return false;
    } else if (SitesettingPusherKey == '') {
        $("[href=#pusher]").trigger('click');
        $("#pusherError").html("Please enter pusher key");
        $("#SitesettingPusherKey").focus();
        return false;
    } else if (SitesettingPusherSecret == '') {
        $("[href=#pusher]").trigger('click');
        $("#pusherError").html("Please enter pusher secret key");
        $("#SitesettingPusherSecret").focus();
        return false;
    } else if (SitesettingPusherId == '') {
        $("[href=#pusher]").trigger('click');
        $("#pusherError").html("Please enter pusher app id");
        $("#SitesettingPusherId").focus();
        return false;
    }
}

function validateYouTubeUrl(url)
{
    if (url != undefined || url != '') {
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
        var match = url.match(regExp);
        if (match && match[2].length == 11) {
            return false;
        } else {
            return true;
        }
    }
}

function save() {
    //alert('You cannot access payment setting at the moment');
    //return false;
}

function storeProducts() {
    var id = $('#Storeproduct').val();
    if (id != '') {
        window.location.href = rp + '/admin/products/index/' + id;
    } else {
        $("#storeProductError").html("Sélectionnez le restaurant svp");
    }
    return false;
}

function storeOrders() {
    var id = $('#StoreOrder').val();
    var StoreRange = $('#StoreRange').val();
    var StoreDriver = $('#StoreDriver').val();

    id = (id != '') ? id : 0;
    StoreRange = (StoreRange != '') ? StoreRange : 0;
    StoreDriver = (StoreDriver != '') ? StoreDriver : 0;

    window.location.href = rp + '/admin/orders/reportIndex/' + id + '/' + StoreRange + '/' + StoreDriver;

    /*if (id != '') {
     window.location.href = rp+'/admin/orders/reportIndex/'+id+'/'+StoreRange+'/'+$StoreDriver;
     } else {
     $("#StoreOrderError").html("Please select restaurant");
     }
     return false;*/
}

$('#sample_12').dataTable({
    columnDefs: [
        {
            "bSortable": false,
            "aTargets": ["no-sort"]
        }
    ]
});

$('.sample_12').dataTable({
    columnDefs: [
        {
            "bSortable": false,
            "aTargets": ["no-sort"]
        }
    ]
});

$('#sample_11').dataTable({
    columnDefs: [
        {
            "bSortable": false,
            "aTargets": ["no-sort"]
        }
    ],
    order: [[3, 'asc']]
});

function importValidate() {

    var ProductStoreId = $.trim($("#ProductStoreId").val());
    var excel = $.trim($("#excel").val());
    var error = 0;

    if (ProductStoreId == '') {
        error = 1;
        $("#storeError").html("Sélectionnez le restaurant svp");
    }

    if (excel == '') {
        error = 1;
        $("#excelError").html("Please select xls file");
    }

    if (error == 1) {
        return false;
    }
}