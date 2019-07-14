# Simple SoundCloud API Wrapper

## Get started

The quickest way to get going is by using Composer (it's 2019).

Do so by executing the command below in your cli:
    composer require domlip94/soundcloudfetcher


To use, firstly we instantiate the class

    $soundCloud = new SoundCloud([
        'client_id'     => null,
        'client_secret' => null
    ]);
    
The **client_secret** is not usually required, but **client_id** is.

## Available Methods

At the minute the only available methods are resolve and fetch track data, I intend to add more methods soon.

Alternatively, you can call an undefined API method yourself (see below.)


### Resolve a Soundcloud URL

This resolves a SoundCloud URL to a track ID as an integer, which you may need for other API calls. 

It is called like so:

    $soundCloud->resolveSoundCloudUrl(TRACK_URL);

The **TRACK_URL** argument would obviously be the link to the track, like below.

    $soundCloud->resolveSoundCloudUrl('https://soundcloud.com/adamstuartfunston/af-podcast-014');

    $soundCloud->resolveSoundCloudUrl($trackUrl);
    
This then returns

    (int) 649287689
    
### Get track data

This returns the track data as an array.

You can either choose certain array keys to return, or return everything.

It is called like so:

    $soundCloud->fetchTrackData(TRACK_URL, ARRAY_KEYS_TO_RETURN);

- **ARRAY_KEYS_TO_RETURN** must be an array or null.

So an example would be,

###### Get all track data
    $soundCloud->fetchTrackData('https://soundcloud.com/adamstuartfunston/af-podcast-014');

###### Get track length
    $soundCloud->fetchTrackData('https://soundcloud.com/adamstuartfunston/af-podcast-014', [
        'duration'
    ]);
        
###### Get track title and length
   
    $soundCloud->fetchTrackData('https://soundcloud.com/adamstuartfunston/af-podcast-014', [
        'title', duration'
    ]);
        
        
### Other methods

While I build this out, only the basics are available. However, the two methods above call the same method to execute their call.

That method is:

    $soundCloud->doApiCall(API_METHOD, METHOD_ARGUMENTS);
    
- **API_METHOD** is the remote API method endpoint.
- **METHOD_ARGUMENTS** is an array of arguments we will provide to the SoundCloud API.
        