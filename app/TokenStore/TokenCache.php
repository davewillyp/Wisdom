<?php

namespace App\TokenStore;
use DB;

class TokenCache {
  public function storeTokens($accessToken, $user, $groups) {    
    $userType='none';

    foreach ($groups['value'] as $group){
        if ($group['id'] == env('ALLSTUDENTS_GROUP_ID')){
            $userType = 'student';
        }
        if ($group['id'] == env('ALLSTAFF_GROUP_ID')){
            $userType = 'staff';
        }
        $allGroups[] = $group['id'];
    }
    
    if($userType == 'student'){
      $allGroups = false;
    }

    $seqtaId = DB::connection('seqta')->table($userType)->where('email',$user['mail'])->value('id');

    session([
      'accessToken' => $accessToken->getToken(),
      'refreshToken' => $accessToken->getRefreshToken(),
      'tokenExpires' => $accessToken->getExpires(),
      'userName' => $user['givenName'] . " " . $user['surname'],
      'givenName' => $user['givenName'],
      'surname' => $user['surname'],
      'userEmail' => $user['mail'],
      'seqtaId' => $seqtaId,
      'userType' => $userType,
      'groups' => $allGroups
    ]);
  }

  public function clearTokens() {
    session()->forget('accessToken');
    session()->forget('refreshToken');
    session()->forget('tokenExpires');
    session()->forget('userName');
    session()->forget('givenName');
    session()->forget('surname');
    session()->forget('userEmail');
    session()->forget('seqtaId');
    session()->forget('userType');
    session()->forget('groups');
  }

  public function getAccessToken() {
    // Check if tokens exist
    if (empty(session('accessToken')) ||
        empty(session('refreshToken')) ||
        empty(session('tokenExpires'))) {
      return '';
    }
  
    // Check if token is expired
    //Get current time + 5 minutes (to allow for time differences)
    $now = time() + 300;
    if (session('tokenExpires') <= $now) {
      // Token is expired (or very close to it)
      // so let's refresh
  
      // Initialize the OAuth client
      $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => env('OAUTH_APP_ID'),
        'clientSecret'            => env('OAUTH_APP_PASSWORD'),
        'redirectUri'             => env('OAUTH_REDIRECT_URI'),
        'urlAuthorize'            => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
        'urlAccessToken'          => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
        'urlResourceOwnerDetails' => '',
        'scopes'                  => env('OAUTH_SCOPES')
      ]);
  
      try {
        $newToken = $oauthClient->getAccessToken('refresh_token', [
          'refresh_token' => session('refreshToken')
        ]);
  
        // Store the new values
        $this->updateTokens($newToken);
  
        return $newToken->getToken();
      }
      catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        return '';
      }
    }
  
    // Token is still valid, just return it
    return session('accessToken');
  }

  public function updateTokens($accessToken) {
    session([
      'accessToken' => $accessToken->getToken(),
      'refreshToken' => $accessToken->getRefreshToken(),
      'tokenExpires' => $accessToken->getExpires()
    ]);
  }
}