<?php

Broadcast::channel('appointments.{id}', function ($user, $id) {
    // Only the doctor or the patient can listen to the appointment channel
    return (int) $user->id === (int) $id;
});

