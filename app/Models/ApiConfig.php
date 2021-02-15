<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class ApiConfig extends Model {

    protected $androidMinimumVersion = 1;
    protected $androidMinimumVersionMessage = "Om de app te gebruiken heb je de laatste versie uit de store nodig.";
    protected $playStoreURL = "https://www.google.com";
    protected $iosMinimumVersion = "1.0.0";
    protected $iosMinimumVersionMessage = "Om de app te gebruiken heb je de laatste versie uit de store nodig.";
    protected $iosAppStoreURL = "https://apps.apple.com/nl/app/id1548269870";
    protected $appDeactivated = false;
    protected $informationURL = "https://coronatester.nl";

}
