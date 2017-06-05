<?php
// Steve
// 30-Apr-2008 05:35
// (corrected)
// I've modified jjoss' php2js function to remove the extra whitespace (not needed for machine readable output) and leave quotes off of non-string variables so that it more closely resembles the output of json_encode.  In my testing this function and json_encode return the exact same result.  I use this as a substitute if json_encode is not defined, so if anyone finds any differences between this and json_encode I would like to see the corrections.


if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}


// jfdsmit at gmail dot com
// 23-Oct-2007 07:31

// json_encode also won't handle objects that do not directly expose their internals but through the Iterator interface. These two function will take care of that:


/**
 * Convert an object into an associative array
 *
 * This function converts an object into an associative array by iterating
 * over its public properties. Because this function uses the foreach
 * construct, Iterators are respected. It also works on arrays of objects.
 *
 * @return array
 */
function object_to_array($var) {
    $result = array();
    $references = array();

    // loop over elements/properties
    foreach ($var as $key => $value) {
        // recursively convert objects
        if (is_object($value) || is_array($value)) {
            // but prevent cycles
            if (!in_array($value, $references)) {
                $result[$key] = object_to_array($value);
                $references[] = $value;
            }
        } else {
            // simple values are untouched
            $result[$key] = $value;
        }
    }
    return $result;
}

/**
 * Convert a value to JSON
 *
 * This function returns a JSON representation of $param. It uses json_encode
 * to accomplish this, but converts objects and arrays containing objects to
 * associative arrays first. This way, objects that do not expose (all) their
 * properties directly but only through an Iterator interface are also encoded
 * correctly.
 */
function json_encode2($param) {
    if (is_object($param) || is_array($param)) {
        $param = object_to_array($param);
    }
    return json_encode($param);
}

?>
