/**
 * Function utilized to obtain the user's location.
 */
function getUserLocation(callBack)
{
	if(navigator.geolocation)
	{
		navigator.geolocation.getCurrentPosition(
			callBack,
			function(error) { userSelectLocation(callBack) },
			{timeout: 30000}
		);
	}
	else
	{
		userSelectLocation(callBack);
	}
}

/**
 * Used when location is unavailable.  Allow user to select location.
 */
function userSelectLocation(callBack)
{

}

/**
 * Function which uses the specified location to obtain nearby friends.
 */
function getNearFriendsHome()
{
	getUserLocation(function(pos)
	{
		$.ajax({
			url: "../../api/base_widget/nearFriends",
			async: false,
			dataType: "json",
			data: {'location': pos.coords},
			type: 'POST',
			success: function(data)
			{
				populateHomeListView(data);
			}
		});
	});
}

/**
 * Specialized function for populating the data of the home page list view.
 */
function populateHomeListView(data)
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
	
	for(var d in dataLoc)
	{
		var loc = {"name":d};
	
		$("#nearby_friends_row_div_template").tmpl(loc).appendTo("#friends_list");
		$("#nearby_friends_row_template").tmpl(dataLoc[d]).appendTo("#friends_list");
	}

	$('#friends_list').listview('refresh');
}

/**
 * Gets the nearby locations for use in check in.
 */
function getNearLocsCheckin()
{
	getUserLocation(function(pos)
	{
		$.ajax({
			url: "../../api/base_widget/nearLocations",
			async: false,
			dataType: "json",
			data: {'location': pos.coords},
			type: 'POST',
			success: function(data)
			{
				console.log("HERE");
				populateCheckinLocations(data);
			}
		});
	});
}

function populateCheckinLocations(data)
{
	$('#nearby_location_row_template').tmpl(data).appendTo('#locations_list');
	
	$('#locations_list').listview('refresh');
}

/**
 * Bind all of the appropriate functions before showing the pages.
 */
$(function()
{
	$('#home_page').bind('pagebeforeshow',function(event, ui)
	{	
		getNearFriendsHome();
	});
	
	$('#check_in_loc_page').bind('pagebeforeshow', function(event, ui)
	{
		getNearLocsCheckin();
	});
});