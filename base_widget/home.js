function getNearFriends()
{
	if(navigator.geolocation)
	{
		navigator.geolocation.getCurrentPosition(
		function(position)
		{
			$.ajax({
				url: "../../api/base_widget/nearme",
				async: false,
				dataType: "json",
				data: {'location': position},
				type: 'POST',
				success: function(data)
				{
					populateListView(data);
				}
			});
		},
		function(error){},
		{timeout: 30000});
	}
	else
	{
		
	}
}

function populateListView(data)
{	
	$(".friend_row_div").remove();
	$(".friend_row").remove();
	
	var dataLoc = {};
	
	for(var d in data)
	{
		if(dataLoc[data[d].location] == undefined)
			dataLoc[data[d].location] = [];
			
		dataLoc[data[d].location].push(data[d]);
	}
	
	console.log(dataLoc);
	
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