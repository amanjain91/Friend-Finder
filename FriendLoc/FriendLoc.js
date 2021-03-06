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
				url: "api/testUser",
				async: false,
				dataType: "json",
				success: function(data)
				{
					console.log("User Successfully Validated");
					validUser = true;
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
function createProfilePage()
{
	if(validUser)
	{
		$.mobile.changePage("#home_page");
		return;
	}
		
	var fname = $('#fname').val();
	var lname = $('#lname').val();
	var phone_num = $('#pnum').val();
	var email_add = $('#email').val();
		
	$.ajax({
		url: "api/profile",
		async: false,
		data: {"fname": fname, "lname": lname, "phone_num": phone_num, "email_add": email_add},
		headers: {'X-HTTP-Method-Override': 'PUT'},
		type: 'POST',
		success: function(data)
		{
			console.log(data);
			console.log("Profile Created Successfully");
			validUser = true;
			$.mobile.changePage("#home_page");
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			console.log("Error Creating Profile");
			writeErrorMessage("An error occured while attempting to create profile.");
		}
	});
}

// Writes an error message to a list.
function writeErrorList(list, message)
{
	list.empty();

	var data = {"message": message};
	list.append($('#error_list_template').tmpl(data));
	
	list.listview('refresh');
}

function writeErrorMessage(message)
{
	$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all'><h1>" + message + "</h1></div>").css({ "display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 })
	.appendTo( $.mobile.pageContainer )
	.delay( 1500 )
	.fadeOut( 400, function()
	{
		$(this).remove();
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
			url: "api/closeFriends",
			async: false,
			dataType: "json",
			data: {'lat': pos.coords.latitude, 'long': pos.coords.longitude},
			type: 'POST',
			success: function(data)
			{   
				populateHomeListView(data);
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				writeErrorList($('#friends_list'), "None of your friends are checked in.");
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
	$('#home_page').trigger('create');
}

/**
 * Gets the nearby locations for use in check in.
 */
function getNearLocsCheckin()
{
	getUserLocation(function(pos)
	{
		$.ajax({
			url: "api/closeLocations",
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
 * Used for transitioning from the location check in page to the activity check in page.
 */
function continueCheckInAt(loc, id)
{
	$('#status_checkin').val("");
	$('#tag_checkin').val("");
	$('#checkin_loc_name_header').text(loc);
	$('#loc_checkin').val(id);
	
	$.mobile.changePage("#check_in_activity_page", {changeHash: false});
} 

/**
 * Append the given tag text to the tag list.
 */
function appendTag(tag)
{
	var tagList = $('#tag_checkin');
	var oldStr = $.trim(tagList.val());
	tag = $.trim(tag);
	tagList.val($.trim(oldStr + ' ' + tag));
}

/**
 * Retrive relevant tag data.
 */
function getPopularTags(id)
{
	$.ajax({
		url: "api/tags/" + id,
		async: false,
		dataType: "json",
		success: function(data)
		{   
			var near = data.nearby;
			var friends = data.friends;
			
			$('#nearby_tag_list').empty();
			$('#friend_tag_list').empty();
			
			if(near.length == 0)
			{
				writeErrorList($('#nearby_tag_list'), "No users are checked in nearby.");
			}
			else
			{
				$('#nearby_tag_list').append($('#tag_list_tmpl').tmpl(near));
			}
			
			if(friends.length == 0)
			{
				writeErrorList($('#friend_tag_list'), "None of your friends are checked in.");
			}
			else
			{
				$('#friend_tag_list').append($('#tag_list_tmpl').tmpl(friends));
			}
			
			$('#nearby_tag_list').listview('refresh');
			$('#friend_tag_list').listview('refresh');
		}
	});
}

/**
 * Attempts to file a checkin with the server.
 */
function checkIn()
{
	var status = $('#status_checkin').val();
	var tags = $('#tag_checkin').val();
	var location = $('#loc_checkin').val();
	
	$.ajax({
		url: "api/checkin",
		data: {"status": status, "tags": tags, "location": location},
		dataType: "json",
		type: 'POST',
		error: function(jqXHR, textStatus, errorThrown)
		{
			writeErrorMessage("An error occured while attempting to check in.")
		}
	});
}

/**
 * Get a list of the users friends.
 */
function getUserFriends(callback)
{
	$.ajax({
			url: "api/friends",
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
	
	if(data.received.length > 0)
	{
		$('#friends_row_divider').tmpl({text: "Recieved Requests"}).appendTo('#friend_page_list');
		$('#friends_row_recv_template').tmpl(data.received).appendTo('#friend_page_list');
	}
	
	if(data.sent.length > 0)
	{
		$('#friends_row_divider').tmpl({text: "Sent Requests"}).appendTo('#friend_page_list');
		$('#friends_row_sent_template').tmpl(data.sent).appendTo('#friend_page_list');
	}
	
	if(data.friends.length > 0)
	{
		$('#friends_row_divider').tmpl({text: "Friends"}).appendTo('#friend_page_list');
		$('#friends_row_template').tmpl(data.friends).appendTo('#friend_page_list');
	}
	
	$('#friend_page_list').listview('refresh');
}

/**
 * Rejects the friend request for the specified user.
 */
function removeFriendRequest(id)
{
	$.ajax({
			url: "api/removeFriend/" + id,
			async: true,
			dataType: "json",
			success: function(data)
			{
				$.mobile.changePage("#friends_page", {allowSamePageTransition: true, transition: "fade"});
			}
	});
}

/**
 * Accept the friend request for the specified user.
 */
function addFriendRequest(id)
{
	$.ajax({
			url: "api/addFriend/" + id,
			async: true,
			dataType: "json",
			success: function(data)
			{
				$.mobile.changePage("#friends_page", {allowSamePageTransition: true, transition: "fade"});
			}
	});
}

/**
 * Updates the add friend page by searching for friends with the given name data.
 */
function searchForFriends(text)
{
	$.ajax
	({
		url: "api/findFriend/",
		dataType: "json",
		data: {"text": text},
		type: 'POST',
		success: function(data)
		{
			if(data.length > 0)
			{
				$('#add_friend_page_list').empty();
				$('#add_friend_row_tmpl').tmpl(data).appendTo('#add_friend_page_list');
				$('#add_friend_page_list').listview('refresh');
			}
			else
				writeErrorList($('#add_friend_page_list'), "No users found matching the given criteria.");
		}
	});
}

function getUserProfile()
{
	$.ajax({
			url: "api/profile/",
			async: false,
			dataType: "json",
			success: function(data)
			{
				$('#user_profile_content').empty();
				$('#user_profile_template').tmpl(data).appendTo('#user_profile_content');
				$('#settings_page').trigger('create');
			}
	});
}

function updateProfile()
{
	var fname = $('#mod_fname').val();
	var lname = $('#mod_lname').val();
	var phone_num = $('#mod_pnum').val();
	var email_add = $('#mod_email').val();
	var img_url = $('#mod_img').val();
		
	$.ajax({
		url: "api/profile",
		async: false,
		data: {"fname": fname, "lname": lname, "phone_num": phone_num, "email_add": email_add, "img_url": img_url},
		type: 'POST',
		success: function(data)
		{
			writeErrorMessage("Profile updated successfully");
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			writeErrorMessage("An error occured while trying to update your profile.");
		}
	});
}

/**
 * Obtains relevant data about a specified friend.
 */
function getFriendProfile(id, callback)
{
	$.ajax({
			url: "api/friends/" + id,
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
	$.mobile.fixedToolbars.setTouchToggleEnabled(false);

	$('#home_page').bind('pagebeforeshow',function(event, ui)
	{
		validate(getNearFriendsHome);
	});
	
	$('#check_in').bind('pagebeforeshow', function(event, ui)
	{
		validate(getNearLocsCheckin);
	});
	
	$('#check_in_activity_page').bind('pagebeforeshow', function(event, ui)
	{
		validate(function()
		{
			getPopularTags($('#loc_checkin').val());
		});
	});
	
	$('#friends_page').bind('pagebeforeshow', function(event, ui)
	{
		validate(function()
		{
			getUserFriends(populateFriendList)
		});
	});
	
	$('#add_friend_page').bind('pagebeforeshow', function(event, ui)
	{
		validate(function()
		{
			$('#add_friend_page_list').empty();
		});
	});

	$('#settings_page').bind('pagebeforeshow', function(event, ui)
	{
		validate(getUserProfile);
	});
	
	$('#profile_page').bind('pagebeforeshow', function(event, ui)
	{
		var id = $.url().fparam("friend_id");
		
		validate(function()
		{
			getFriendProfile(id, populateProfilePage)
		});
	});
	
	$('#profile_page').bind('pagehide', function() 
	{
		$(this).attr("data-url",$(this).attr("id"));
		delete $(this).data()['url'];
	});
});