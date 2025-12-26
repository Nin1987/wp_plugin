<?php
namespace PluginName;

class ClassDb
{
    protected function getOneRecords(string $tableName, string $row_to_get, string $where, array $value): mixed
    {
        global $wpdb;
        $tableName = $wpdb->prefix.$tableName;

        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT ".$row_to_get." 
                FROM ".$tableName." 
                ".$where." 
                ",
                $value
            )
        );
    }
    protected function getManyRecords(string $tableName, string $where, string $value): mixed
    {
        global $wpdb;
        $tableName = $wpdb->prefix.$tableName;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * 
                FROM ".$tableName." 
                ".$where." 
                ",
                $value,
                OBJECT
            )
        );
                
    }
    protected function update_records(string $tableName, array $rows, array $prepare, array $where, array $prepare_where): mixed
    {

        global $wpdb;
        $tableName = $wpdb->prefix.$tableName;
        if(is_array($rows) && is_array($prepare))
        {
            return $wpdb->update(
                    $tableName,
                    $rows,
                    $where,
                    $prepare,
                    $prepare_where
            );
        }
        return false;
    }
    protected function delete_all_from_table(string $tableName): mixed
    {
        global $wpdb;
        $tableName = $wpdb->prefix.$tableName;
        $wpdb->query(
            $wpdb->prepare(
            "
            DELETE FROM $tableName
            WHERE id > %d
            ",
            0
            )
        );
    }

    protected function insert_table(string $tableName, array $rows, array $prepare): mixed
    {
        global $wpdb;
        $tableName = $wpdb->prefix.$tableName;
        if(is_array($rows) && is_array($prepare))
        {
            return $wpdb->insert(
                    $tableName,
                    $rows,
                    $prepare
            );
        }
        return false;
    }

    protected function create_table(string $tableName, array $rows = array()): mixed
    {
        
        global $wpdb;
        $tableName = $wpdb->prefix.$tableName;
        if(is_array($rows))
        {
            $count = count($rows);
            if($count>0)
            {
                $make_rows ='';
                $i=0;
                foreach ($rows as $row) {
                    $i++;
                    if($count == $i)
                    {
                        $make_rows .= $row;
                    }
                    else
                    {
                        $make_rows .= $row.',';
                    }
                    
                }
              
                $wpdb->query(
                    $wpdb->prepare(
                        "CREATE TABLE if not exists ".$tableName." (
                            id BIGINT(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            ".$make_rows."
                        );"
                    ,)
                );
            }
        }
    }
}