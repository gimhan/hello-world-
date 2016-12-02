<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SphereCountEventHandler
 *
 * @author Gimhan
 */
class SphereCountEventHandler {
    CONST EVENT = 'sphere.count';
     CONST CHANNEL = 'sphere.count';

    public function handle($data)
    {
        $storage = Redis::connection();
        $logged_user = Auth::user()->id;
        $data = $storage->incr('sphere'. $logged_user .'_count');
        $storage->publish(self::CHANNEL, $data);

    }
}
