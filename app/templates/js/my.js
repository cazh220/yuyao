//自定义js
$(function(){
	
	
	
});

function add_same()
{
	//先保存数据
	$("div").data("name","豆腐111");
	$("div").data("url","http://www.me.com");
	$("div").data("desc","xxxx3345");
	$("input[name=category_name]").val('');
	$("input[name=category_link]").val('');
	$("input[name=category_desc]").val('');
}

function add_child()
{
	//先保存数据
	$("div").data("name","豆腐111");
	$("div").data("url","http://www.me.com");
	$("div").data("desc","xxxx3345");
	$("input[name=category_name]").val('');
	$("input[name=category_link]").val('');
	$("input[name=category_desc]").val('');
}

function edit_cat()
{
	//先保存数据
	var name = $("div").data("name");
	var urls = $("div").data("url");
	var desc = $("div").data("desc");

	$("input[name=category_name]").val(name);
	$("input[name=category_link]").val(urls);
	$("input[name=category_desc]").val(desc);
}


function delete_cat()
{
	//先保存数据
	var name = $("div").data("name");
	var urls = $("div").data("url");
	var desc = $("div").data("desc");

	$("input[name=category_name]").val(name);
	$("input[name=category_link]").val(urls);
	$("input[name=category_desc]").val(desc);
}