function getNearFriends()
{
	console.log("START CALL");
	$.ajax({
		url: "../../api/base_widget/nearme",
		async: false,
		dataType: "json",
		data: {'location': 'testItemValue'},
		type: 'POST',
		success: function(data)
		{
			console.log("WIN CALL");
			populateListView(data);
		}
	});
}

function populateListView(data)
{
	$("#friends_list").remove();	
	
	var dataLoc = {};
	
	for(var d in data)
	{
		if(dataLoc[data[d].location] == undefined)
			dataLoc[data[d].location] = [];
			
		dataLoc[data[d].locid].push(data[d]);
	}
	
	for(var d in dataLoc)
	{
		var loc = {"name":d};
	
		$("#nearby_friends_row_div_template").tmpl(loc).appendTo("#friends_list");
		$("#nearby_friends_row_template").tmpl(dataLoc[d]).appendTo("#friends_list");
	}

	$('#friends_list').listview('refresh');
}

$(function()
{
	$('#home_page').bind('pagebeforeshow',function(event, ui)
	{	
		getNearFriends();
	});
});