<?php

/**
* Saika - The PHP Framework for KIDS
*
* The File Cache Class
*
* @version 1.0
* @since 1.0
*/

class FileCache
{

    /**
     * Save something to cache
     *
     * @param  string $id   The unique identifier, you will need this
     *                      id when retrieving it back.
     * @param  mixed $data  The data, could be anything! String, object even array!
     * @return boolean
     */
    public static function save($id, $data)
    {
        $id = md5($id);
        $data = serialize($data);
        return file_put_contents(Config::get('CACHE_DIR'). ' ' .$id. '.cache', $data, LOCK_EX);
    }

    /**
     * Retrieve data from cache
     *
     * @param  string  $id         The unique identifier for the cache, @see self::save()
     * @param  integer|boolean $older_than Allows you to only return the cache if
     *                                     it is not older than a specific time(in seconds).
     *                                     Set to false by default.
     * @return mixed
     */
    public static function get($id, $older_than = false)
    {
        $id = md5($id);
        $cache_file = Config::get('CACHE_DIR').''.$id.'.cache';

        if (is_file($cache_file)) {
            if (!$older_than) {
                return unserialize(file_get_contents($cache_file));
            }

            if (time() - filemtime($cache_file) >= $older_than) {
                return false;
            }
            else {
                return unserialize(file_get_contents($cache_file));
            }
        }
        return false;
    }

    /**
     * Delete data from cache
     *
     * @param  string  $id         The unique identifier for the cache, @see self::save()
     * @param  integer|boolean $older_than Allows you to only delete the cache if
     *                                     it is older than a specific time(in seconds).
     * @return boolean
     */
    public static function delete($id, $older_than = false)
    {
        $id = md5($id);
        $cache_file = Config::get('CACHE_DIR').''.$id.'.cache';

        // Why the fuck should i even try? -_-
        if (!is_file($cache_file))
            return false;

        // You want to delete immediately? k then -_-
        if (!$older_than)
            return unlink($cache_file);

        // Ah! Tricky Part! <3
        if (time() - filemtime($cache_file) >= $older_than)
            return unlink($file);

        return false;
    }


    /**
     * Clear all caches
     *
     * @param  integer|boolean $older_than Allows you to only delete the cache if
     *                                     it is older than a specific time(in seconds).
     * @return boolean
     */
    public static function clearAll($older_than = false)
    {
        $cache_files = glob(Config::get('CACHE_DIR').'*.cache');
        // Why the fuck should i even try? -_-
        if (empty($cache_files))
            return false;

        foreach ($cache_files as $cache_file) {
            // If there is a older than value set!
            if ($older_than) {
                // restricted area, mature only!
                if (time() - filemtime($cache_file) >= $older_than) {
                    unlink($cache_file);
                    continue; // Lets do it again!
                }
                else {
                    continue; // Just like I said! :D
                }
            }
            // No timespan? simply delete it then -_-
            unlink($cache_file);
        }
        return true; // It's a lie, maybe! :/ -_-
    }
}
