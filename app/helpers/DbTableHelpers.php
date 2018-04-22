<?php

class DbTableHelpers {

    public static function emptyTable($table)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table($table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

}