
// The Browser API key obtained from the Google Developers Console.
var developerKey = 'AIzaSyBBRFrBKlGpVfIchNQPIBgyc5tz5DvLxhs';

// The Client ID obtained from the Google Developers Console. Replace with your own Client ID.
var clientId = "995339075188-a281h6e0f7848g6cj5pfh6rs6n25mjkl.apps.googleusercontent.com";

// Scope to use to access user's photos.
var scope = ['https://www.googleapis.com/auth/drive.readonly'];

var pickerApiLoaded = false;
var oauthToken;

// Use the API Loader script to load google.picker and gapi.auth.
function onApiLoad() {
    gapi.load('auth', {'callback': onAuthApiLoad});
    gapi.load('picker', {'callback': onPickerApiLoad});
}

function onAuthApiLoad() {
    window.gapi.auth.authorize(
            {
                'client_id': clientId,
                'scope': scope,
                'immediate': false
            },
    handleAuthResult);
}

function onPickerApiLoad() {
    pickerApiLoaded = true;
    createPicker();
}

function handleAuthResult(authResult) {
    if (authResult && !authResult.error) {
        oauthToken = authResult.access_token;
        console.log(oauthToken);
        createPicker();
    }
}

// Create and render a Picker object for picking user Photos.
function createPicker() {
    if (pickerApiLoaded && oauthToken) {
        console.log(oauthToken);
        var picker = new google.picker.PickerBuilder().
                addViewGroup(
                        new google.picker.ViewGroup(google.picker.ViewId.DOCS).
                        addView(google.picker.ViewId.DOCUMENTS).
                        addView(google.picker.ViewId.PRESENTATIONS)).
                setOAuthToken(oauthToken).
                setDeveloperKey(developerKey).
                setCallback(pickerCallback).
                enableFeature(google.picker.Feature.MULTISELECT_ENABLED).
                enableFeature(google.picker.Feature.NAV_HIDDEN).

                build();
        picker.setVisible(true);
    }
}

