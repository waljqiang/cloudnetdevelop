<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository as Cache;

class RateLimiter{
    /**
     * The cache store implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Create a new rate limiter instance.
     *
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @return void
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Determine if the given key has been "accessed" too many times.
     *
     * @param  string  $key
     * @param  int  $dayMax
     * @param  int  $monthMax
     * @return bool
     */
    public function tooManyAttempts($key, $dayMax = 100, $monthMax = 2000){
    	$dayKey = $key . ':day';
    	$monthKey = $key . ':month';
        if ($this->cache->has($dayKey.':lockout') || $this->cache->has($monthKey.':lockout')) {
            return true;
        }

        if($this->attempts($dayKey) >= $dayMax){
        	$time = Carbon::tomorrow()->diffInMinutes(Carbon::now());
        	$this->lockout($dayKey,$time);
        	$this->resetAttempts($dayKey);
        	return true;
        }

        if($this->attempts($monthKey) >= $monthMax){
        	$time = Carbon::now()->addMonth()->firstOfMonth()->diffInMinutes(Carbon::now());
        	$this->lockout($monthKey,$time);
        	$this->resetAttempts($monthKey);
        	return true;
        }

        return false;
    }

    /**
     * Add the lockout key to the cache.
     *
     * @param  string  $key
     * @param  int  $decayMinutes
     * @return void
     */
    protected function lockout($key,$time){
        $this->cache->add(
            $key.':lockout', Carbon::now()->getTimestamp() + ($time * 60), $time
        );
    }

    /**
     * Increment the counter for a given key for a given decay time.
     *
     * @param  string  $key
     * @param  float|int  $decayMinutes
     * @return int
     */
    public function hit($key){
    	$dayKey = $key . ':day';
    	$monthKey = $key . ':month';

    	$dayDecayMinutes = Carbon::tomorrow()->diffInMinutes(Carbon::now());
    	$monthDecayMinutes = Carbon::now()->addMonth()->firstOfMonth()->diffInMinutes(Carbon::now());

    	$dayAdded = $this->cache->add($dayKey,0,$dayDecayMinutes);
    	$monthAdded = $this->cache->add($monthKey,0,$monthDecayMinutes);

    	$dayHits = (int) $this->cache->increment($dayKey);
    	$monthHits = (int) $this->cache->increment($monthKey);

    	if(!$dayAdded && $dayHits == 1){
    		$this->cache->put($dayKey, 1, $dayDecayMinutes);
    	}

    	if(!$monthAdded && $monthHits == 1){
    		$this->cache->put($monthKey, 1, $monthDecayMinutes);
    	}

        return compact('dayHits','monthHits');
    }

    /**
     * Get the number of attempts for the given key.
     *
     * @param  string  $key
     * @return mixed
     */
    private function attempts($key){
        return $this->cache->get($key, 0);
    }

    /**
     * Reset the number of attempts for the given key.
     *
     * @param  string  $key
     * @return mixed
     */
    private function resetAttempts($key){
        return $this->cache->forget($key);
    }

    /**
     * Clear the hits and lockout for the given key.
     *
     * @param  string  $key
     * @return void
     */
    public function clear($key){
    	$dayKey = $key . ':day';
    	$monthKey = $key . ':month';

        $this->resetAttempts($dayKey);
        $this->resetAttempts($monthKey);

        $this->cache->forget($dayKey.':lockout');
        $this->cache->forget($monthKey.':lockout');
    }
}
