THANKS FOR LOOKING

To install, you can use composer

	composer require domlip94/soundcloudfetcher

This code is mostly undocumented.

You start by creating the object, as below

	$sc = new soundcloud('API_KEY','CLIENT_ID');

API_KEY can be left blank.
CLIENT_ID must be set to make valid API calls.

Usually, you'll be given a direct Track link. We need to resolve that to a track ID first so we can get the full information on it. The syntax is:

	$sc->resolve('SOUNDCLOUD_TRACK_URL');

So, for example 

	$sc->resolve('https://soundcloud.com/rodaah/lorde-flume-vs-tommy-trash-ingrosso-tennis-court-reload-henry-fong-mashup');

cURL then gets a response from the server with a redirection. It then stores the new URL in the variables.
To then load the track information, use the fetch() method. This accepts no arguments.

Finally, we have track_data().

This can be used one of 3 ways - no arguments 

	$sc->track_data();
The above code will return the whole array of data.

	$sc->track_data('user_id');
The above code will return the user id of the user that created the track as a single string.

	$sc->track_data('user_id','title',...);
The track_data() method accepts an unlimited number of arguments and will return each value as an array.