<?php

namespace CrudGenerator;

use Illuminate\Support\Str;

class Helpers
{
  public static function types($name) {
    if (Str::contains($name, ['varchar', 'character', 'uuid'])) return 'string';
    if (Str::contains($name, ['int', 'float', 'double'])) return 'number';
    if (Str::contains($name, ['date', 'time'])) return 'date';
    if (Str::contains($name, ['text', 'json'])) return 'text';
    if (Str::contains($name, ['bool', 'check'])) return 'boolean';
    return 'unknown';
  }
}
