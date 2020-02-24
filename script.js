$(document).ready(function ()
{

    $("#count").on("input", changeRangeInput);
	$("input[type='range']").on("input", changeRange);
	$(".item").click(select);
	$(".generate").click(generate);
});
function changeRange()
{
	$("#count").val($(this).val());
}
function changeRangeInput()
{
	var val = $(this).val();
    if(val < 6)
    {
        val = 6;
        $(this).val(val);
    }

    if(val > 255)
    {
        val = 255;
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


function generate()
{
	var chars = $("#chars").val();
	var count = $("#count").val();
	var item = $(".items").find(".item[class='item selected']").attr("data-mode");
	var pas = $("#password").val();

	switch(item)
	{
		case "new":
			pas = get(chars, count);
			break;
		case "end":
			pas = pas + get(chars, count);
			break;
		case "start":
			pas = get(chars, count) + pas;
			break;
		case "on-the-sides":
			pas = get(chars, count) + pas + get(chars, count);
			break;
		default:
			alert("Неверный формат запроса.");
			return false;
			break;
	}
	$("#password").val(pas);

	return false;
}

function get(chars, count)
{
	var result = "";
	var length = chars.length;

	for (var i = 0; i < count; i++)
	{
		result += chars[getRandomInt(0, length)];
	}

	return result;
}



function getRandomInt(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min)) + min; //Максимум не включается, минимум включается
}