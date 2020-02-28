var chart;

$(document).ready(function ()
{
	render();

	$("#progress-pie-chart").hide();
	$("#sec").hide();

    $("#count").on("input", changeRangeInput);
	$("input[type='range']").on("input", changeRange);
	$("#advanced").on("input", function(){
		if(!$(this).is(':checked'))
		{
			$("#multiplier").prop('disabled', true);
			$("#addend").prop('disabled', true);
			$("#mask").prop('disabled', true);
		}
		else
		{
			$("#multiplier").removeAttr('disabled');
			$("#addend").prop('disabled', false);
			$("#mask").prop('disabled', false);
		}
		
	});
	$("#seed_auto").on("input", function(){
		if($(this).is(':checked'))
		{
			$("#seed").prop('disabled', true);
		}
		else
		{
			$("#seed").prop('disabled', false);
		}
		
	});
	$("#unlimited").on("input", function(){
		if($(this).is(':checked'))
		{
			$("#right").prop('disabled', true);
			$("#left").prop('disabled', true);
		}
		else
		{
			$("#right").prop('disabled', false);
			$("#left").prop('disabled', false);
		}
		
	});
	$(".item").click(select);
	$(".generate").click(generate);


	counterFunction();
});
function render()
{
	var $ppc = $('.progress-pie-chart'),
	  percent = parseInt($ppc.data('percent')),
	  deg = 360*percent/100;
	if (percent > 50) 
	{
  		$ppc.addClass('gt-50');
	}
	else
	{
		$ppc.removeClass('gt-50');
	}
	$('.ppc-progress-fill').css('transform','rotate('+ deg +'deg)');
	$('.ppc-percents span').html(percent+'%');
}
function changeRange()
{
	$("#count").val($(this).val());
}
function changeRangeInput()
{
	var val = $(this).val();
    if(val < 1)
    {
        val = 1;
        $(this).val(val);
    }

    if(val > 9999999)
    {
        val = 9999999;
        $(this).val(val);
    }
        

    $("input[type='range']").val(val);
}

function select()
{
	$(this).siblings().each(function(){
		if($(this).attr("class") == "item" || $(this).attr("class") == "item selected")
			$(this).attr("class", "item");
	});
	
	$(this).attr("class", "item selected");
}

function get(seed, a, c, m, seed_auto, advanced, mod, print_mod, count, left, right, unlimited, lagA, lagB, __part = 1)
{
	$("#progress-pie-chart").attr("data-percent", __part);

	if (__part > 50) 
	{
		$('.progress-pie-chart').addClass('gt-50');
	}
	else
	{
		$('.progress-pie-chart').removeClass('gt-50');
	}
	deg = 360*__part/100;
	$('.ppc-progress-fill').css('transform','rotate('+ deg +'deg)');
	$('.ppc-percents span').html(__part+'%');




    $.post("/ajax/rand", { 
    	"seed": seed, //начальное значение
    	"multiplier": a, //множитель a
    	"addend": c, //приращение c
    	"mask": m, //модуль m

    	"seed_auto": seed_auto, //Формировать "начальное значение"" автоматически
    	"advanced": advanced, //Задать "множитель a", "приращение c", "модуль m" вручную

    	"mod": mod, //Счетчик
    	"print_mod": print_mod, //Способ вывода

    	"count": count, //количество интераций

    	"left": left, //левая граница
    	"right": right, //правая граница
    	"Unlimited": unlimited, //Неограниченый диапазон генерируемых чисел

    	"lagA": lagA, //LagA
    	"lagB": lagB, //LagB

    	"__part": __part //Часть данных
    }, function(data){


        if(data['status'] == "ok")
        {
        	data['data'].forEach(function(item, index){
        			if(index != 0)
        			{
        				$("#sec").val($("#sec").val() + "\n" + item);
        			}
                	else
                	{
                		$("#sec").val(item);
                	}
                	counterFunction.counter.push(item);
            	});

        	if(__part != 100)
        	{
            	__part++;
            	get(data['seed'], a, c, m, false, advanced, mod, print_mod, count, left, right, unlimited, lagA, lagB, __part);
        	}
        	else
        	{
            	if(print_mod == "File")
        		{
        			download();
        		}
        		else
        		{
        			$("#sec").show();
        			
        			let sorted = counterFunction.counter.sort();
        			counterFunction.counter = [];
					
        			Categories = Array.from(new Set(sorted));
        			
        			let count = [];	
        			
        			for (let i = 0; i < Categories.length; i++) 
        			{
        				var f = sorted.indexOf(Categories[i]);
        				var l = sorted.lastIndexOf(Categories[i]);
    					count[i] = l - f + 1;
        			}

        			var title = "Количество случайных чисел: " + sorted.length;
        			var subtitle = "Количество уникальных значений: " + Categories.length;
        			var methodName = "";
        			$(".row.items.mod").find(".item.selected").each(function(index)
        			{
        				methodName = $(this).text();
        			});

        			//var resultData = JSON.parse(count);

        			if(chart != null)
        			{
    				    chart.update({
	    				    title: 
	    				    {
	    				        text: title
	    				    },	
	    				    subtitle: 
	    				    {
	    				        text: subtitle
	    				    },
	    				    xAxis: 
	    				    {
	    				        categories: Categories
	    				    },
	    				    series: [{
	    				    	name: methodName,
	    				    	data: count
	    				    }]
    					});
        				console.log("createChart: updated");
        			}
        			else
        			{
	    			    chart = Highcharts.chart('container', {
	    			       chart: 
	    			       {
	    			           type: "spline",
	    			           spacingTop: 10,
	    			           spacingRight: 0,
	    			           spacingBottom: 3,
	    			           spacingLeft: 0
	    			       },
	    			       title: 
	    			       {
	    			           text: title
	    			       },
	    			       subtitle: 
	    			       {
	    			           text: subtitle
	    			       },
	    			       xAxis: 
	    			       {
	    			           categories: Categories
	    			       },
	    			       yAxis: 
	    			       {
	    			           title: 
	    			           {
	    			               text: "Частота"
	    			           }
	    			       },
	    			       legend: 
	    			       {
	    			           layout: "vertical",
	    			           align: "right",
	    			           verticalAlign: "middle",
	    			           borderWidth: 0
	    			       },
	    			       series: [{
	    			       	name: methodName,
	    			       	data: count
	    			       }],
	    			       exporting: {
	    			 			menuItemDefinitions: {
	    							downloadPNG: {
	    			                	text: 'Скачать график в формате .png'
	    			                },
	    							downloadPDF: {
	    			                	text: 'Скачать график в формате .pdf'
	    			                },
	    							downloadJPEG: {
	    			                	text: 'Скачать график в формате .jpeg'
	    			                },
	    			                hideLegend: {
	    			                	text: 'Скрыть легенду',
	    			                    textKey: "hideLegend",
	    			                    onclick: hideLegend
	    			                },
	    			                showLegend: {
	    			                	text: 'Показать легенду',
	    			                    textKey: "hideLegend",
	    			                    onclick: showLegend
	    			                },

	    			               },
	    			               buttons: {
	    			                   contextButton: {
	    			                        menuItems: ['hideLegend', 'separator', 'downloadPNG', 'downloadPDF', 'downloadJPEG']
	    			                   }
	    			               }
	    			           }
	    			    });
					}
					console.log("createChart: created");
        			//console.log(Categories);
        			//console.log(count);

        		}
            	//console.log(counterFunction.counter);
            	//counterFunction.counter = [];
        	}
        }
        else
        {
        	console.log(data);
            data['errors'].forEach(function(item){
                alert("Операция отменена: " + item['message']);
            });
        }
        
        
    }, 'json');
}
function hideLegend()
{
    chart.update({	
		legend: {
			enabled: false	
		},
		exporting: {
			buttons: {
			    contextButton: {
			         menuItems: ['showLegend', 'separator', 'downloadPNG', 'downloadPDF', 'downloadJPEG']
			    }
			}
		}
	});
}

function showLegend()
{
	chart.update({	
		legend: {
			enabled: true	
		},
		exporting: {
			buttons: {
			    contextButton: {
			         menuItems: ['hideLegend', 'separator', 'downloadPNG', 'downloadPDF', 'downloadJPEG']
			    }
			}
		}
	});
}

function counterFunction() {
	// проверяем не задана ли уже эта переменная значением
	if ( typeof (counterFunction.counter) == 'undefined' ) {
	// если нет ставим в ноль
	counterFunction.counter = [];
	}
}

function generate()
{
	$("#progress-pie-chart").show();

	var seed = $("#seed").val();
	var a = $("#multiplier").val();
	var c = $("#addend").val();
	var m = $("#mask").val();

	var seed_auto = $("#seed_auto").is(":checked");
	var advanced = $("#advanced").is(":checked");

	var count = $("#count").val();
	var mod = $(".items.mod").find(".item[class='item selected']").attr("data-mode");

	var print_mod = $(".items.print_mod").find(".item[class='item selected']").attr("data-mode");
	//var pas = $("#password").val();

	switch(mod)
	{
		case "Line":
			break;
		case "Fib":
			break;
		case "Pi":
			break;
		default:
			alert("Неверный формат запроса.");
			return false;
			break;
	}

	switch(print_mod)
	{
		case "File":
			break;
		case "Screen":
			break;
		default:
			alert("Неверный формат запроса.");
			return false;
			break;
	}
	
	var left = $("#left").val();
	var right = $("#right").val();
	var unlimited = $("#unlimited").is(":checked");
	
	var lagA = $("#lagA").val();
	var lagB = $("#lagB").val();

	get(seed, a, c, m, seed_auto, advanced, mod, print_mod, count, left, right, unlimited, lagA, lagB);

	return false;
}


function download()
{
	var downloadURL = function(url, name) {
	    var link = document.createElement('a');
	    if(name == undefined || name == ''){name = url};
	    link.setAttribute('href',url);
	    link.setAttribute('download',name);
		onload = link.click();
	};

	var str = "";
	counterFunction.counter.forEach(function(item){
		str += item + "%0A";
	});
    downloadURL('data:text/plain;charset=UTF-8,' + str, $("#count").val() + " случайных чисел.txt");

    $("#sec").val("");
}
