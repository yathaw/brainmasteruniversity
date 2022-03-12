$(document).ready(function() 
{
    $.ajax({
        type: 'POST',
        url: 'getAnalytics.php',
        success: function(response) {
            var result = JSON.parse(response);
            console.log(result);

            var categories = result.categories;
            var ideas = result.ideas;
            var comments = result.comments;
            var likes = result.likes;
            var dislikes = result.dislikes;


            var options = {
                series: [{
                    name: 'Ideas',
                    data: ideas,
                    fillColor: '#008FFB'
                }, {
                    name: 'Comments',
                    data: comments
                }, {
                    name: 'Likes',
                    data: likes
                }, {
                    name: 'Dislikes',
                    data: dislikes
                }],
                chart: {
                    type: 'bar',
                    height: 500,
                    stacked: true,
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                    },
                },
                colors: [config.colors.info, config.colors.primary, config.colors.success, config.colors.danger],
                stroke: {
                    width: 1,
                    colors: ['#fff']
                },
                title: {
                    text: 'Brain Master University Analytics'
                },
                xaxis: {
                    categories: categories,
                    labels: {
                        formatter: function(val) {
                            return val
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: undefined
                    },
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    offsetX: 40
                }
            };

            var chart = new ApexCharts(document.querySelector("#totalanalyticsbycategoryChart"), options);
            chart.render();
  

        }

    });

    function getAllideas(){
        console.log(calendar_path);
        if (bid) {
                calendar_path = "getschedule_byBatch/:id";
                calendar_path=calendar_path.replace(':id',bid);

                console.log(calendar_path);
                change_eventpath(calendar_path);
        }
        else{
            
            calendar_path = 'getallSchedules';
            

        }
    }

    var calendarEl = document.getElementById('calendar');

    //===================================================================================
    // Calendar 
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'Asia/Yangon',
        // droppable: true,
        initialView: 'dayGridMonth',
        headerToolbar: { 
            left: 'prev',
            center: 'title',
            right: 'next' 
        },
        buttonText: {
            dayGridMonth: 'Month',
            timeGridWeek: 'Week',
            dayGridDay: 'Day',
            listWeek: 'List Week'
        },
        editable: false,
        eventTimeFormat: { // like '14:30:00'
            hour: 'numeric',
            minute: '2-digit',
            meridiem: false
        },
        events: 'getAllideas.php'


    });
    calendar.render();

});