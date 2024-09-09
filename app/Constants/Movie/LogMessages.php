<?php

namespace App\Constants\Movie;

class LogMessages
{
    const COLLECTION_NOT_RETRIEVED = 'Movie collection has not been retreived.';
    const COLLECTION_RETRIEVED = 'Movie collection has been retreived.';
    const COLLECTION_ERROR = 'Movie collection has not been provided. Error: ';
    const MODEL_NOT_RETRIEVED = 'Movie model has not been retreived.';
    const MODEL_RETRIEVED = 'Movie model has been retreived.';
    const MODEL_ERROR = 'Movie model has not been provided. Error: ';
    const MODEL_CREATED = 'Movie model has been created.';
    const MODEL_UPDATED = 'Movie model has been updated.';
    const MODEL_DELETED = 'Movie model has been deleted.';
    const POLICY_SHOW_MOVIE = 'User has been tried to see others data. ';
    const POLICY_UPDATE_MOVIE = 'User has been tried to update others data. ';
    const POLICY_DELETE_MOVIE = 'User has been tried to delete others data. ';

}