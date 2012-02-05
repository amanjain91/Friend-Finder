/**
 * Used to validate the user if he has not been.
 */
validUser = false;
function validate(callback)
{
	if(validUser)
	{
		callback();
	}
	else
	{
		$.ajax({
				url: "../../api/base_widget/testValidation",
				async: false,
				dataType: "json",
				success: function(data)
				{
					console.log("User Successfully Validated");
					callback();
				},
				error: function(jqXHR, textStatus, errorThrown)
				{
					console.log("Validation Failed - Redirecting");
					$.mobile.changePage("#create_initial_profile_page");
				}
		});
	}
}

/**
 * Attempts to create a profile for the current user.
 */
function createProfilePage(fname, lname, phone_num, email_add)
{
	if(validUser)
		return;

	$.ajax({
		url: "../../api/base_widget/profile",
		async: false,
		data: {"fname": fname, "lname": lname, "phone_num": phone_num, "email_add": email_add},
		headers: {'X-HTTP-Method-Override': 'PUT'},
		type: 'POST',
		success: function(data)
		{
			validUser = true;
			$.mobile.changePage("#home_page");
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
		
		}
	});
}

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
			data: {'lat': pos.coords.latitude, 'long': pos.coords.longitude},
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
	$('#friends_list').empty();
	
	for(var d in data)
	{
		var loc = {"name":d};
		
		for(var i in data[d])
		{
			data[d][i].loc = d;
		}
		
		$("#nearby_friends_row_div_template").tmpl(loc).appendTo("#friends_list");
		$("#nearby_friends_row_template").tmpl(data[d]).appendTo("#friends_list");
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
			data: {'lat': pos.coords.latitude, 'long': pos.coords.longitude},
			type: 'POST',
			success: function(data)
			{
				populateCheckinLocations(data);
			}
		});
	});
}

function populateCheckinLocations(data)
{
	$('#locations_list').empty();

	$('#nearby_location_row_template').tmpl(data).appendTo('#locations_list');
	
	$('#locations_list').listview('refresh');
}

/**
 * Get a list of the users friends.
 */
function getUserFriends(callback)
{
	$.ajax({
			url: "../../api/base_widget/friends",
			async: false,
			dataType: "json",
			success: function(data)
			{
				callback(data);
			}
	});
}

function populateFriendList(data)
{
	$('#friend_page_list').empty();

	$('#friends_row_template').tmpl(data).appendTo('#friend_page_list');
	
	$('#friend_page_list').listview('refresh');
}

function getFriendProfile(id, callback)
{
	$.ajax({
			url: "../../api/base_widget/friends/" + id,
			async: false,
			dataType: "json",
			success: function(data)
			{
				callback(data);
			}
	});
}

function populateProfilePage(data)
{
	$('#profile_content').empty();
	
	$('#profile_template').tmpl(data).appendTo('#profile_content');
}

/**
 * Bind all of the appropriate functions before showing the pages.
 */
$(function()
{
	$('#home_page').bind('pagebeforeshow',function(event, ui)
	{
		validate(getNearFriendsHome);
	});
	
	$('#check_in_loc_page').bind('pagebeforeshow', function(event, ui)
	{
		validate(getNearLocsCheckin);
	});
	
	$('#friends_page').bind('pagebeforeshow', function(event, ui)
	{
		validate(function()
		{
			getUserFriends(populateFriendList)
		});
	});
	
	$('#profile_page').bind('pagebeforeshow', function(event, ui)
	{
		var id = $.url().fparam("friend_id");
		
		validate(function()
		{
			getFriendProfile(id, populateProfilePage)
		});
	});
});