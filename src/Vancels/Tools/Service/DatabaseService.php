<?php
namespace Vancels\Tools\Service;

class DatabaseService
{

    public function autoInsertColumn($table_name, $data)
    {
        $keys = array_keys($data);

        foreach ($keys as $value) {
            if (!\Schema::hasColumn($table_name, $value)) {
                \Schema::table($table_name, function (\Illuminate\Database\Schema\Blueprint $table) use ($value) {
                    $table->string($value)->nullable();
                });
            }
        }

    }
}