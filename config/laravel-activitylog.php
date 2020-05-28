<?php

return [

    /**
     * When running the clean-command all recording activities older than
     * the number of days specified here will be deleted.
     */
    'delete_records_older_than_days' => 365,


    /**
     * When not specifying a log name when logging activity
     * we'll using this log name.
     */
    'default_log_name' => 'default'
];


//activity()
//    ->performedOn($anEloquentModel)
//    ->causedBy($user)
//    ->withProperties(['customProperty' => 'customValue'])
//    ->log('Look, I logged something');
//
//$lastLoggedActivity = Activity::all()->last();
//
//$lastLoggedActivity->subject; //returns an instance of an eloquent model
//$lastLoggedActivity->causer; //returns an instance of your user model
//$lastLoggedActivity->getExtraProperty('customProperty'); //returns 'customValue'
//$lastLoggedActivity->description; //returns 'Look, I logged something'